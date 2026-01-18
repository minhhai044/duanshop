<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Gallery;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService
{
    /**
     * Get all products with relations
     */
    public function getAllProduct($paginate = 0, $filters = [], $relation = [], $is_active = false)
    {
        $query = Product::with($relation)->latest('id');

        $dateRangeable = ['page'];

        // Áp dụng các bộ lọc nếu có
        foreach ($filters as $field => $value) {


            if (isset($value) && $value !== '' && !in_array($field, $dateRangeable, true)) {
                $query->where($field, 'like', '%' . $value . '%');
            }


            if (isset($value) && $value !== '' && in_array($field, $dateRangeable, true)) {
                if ($field === 'created_at_start') {
                    $query->whereDate('created_at', '>=', $value);
                } elseif ($field === 'created_at_end') {
                    $query->whereDate('created_at', '<=', $value);
                }
            }
        }

        // Nếu có is_active thì sẽ lọc những user đang kích hoạt
        if ($is_active) {
            $query->where('is_active', 1);
        }

        // Nếu có paginate sẽ thực hiện phân trang
        if ($paginate > 0) {
            return $query->paginate($paginate);
        } else {
            return $query->get();
        }
    }

    /**
     * Get featured products
     */
    public function getFeaturedProduct($limit = 10)
    {
        return Product::where('pro_featured', true)
            ->where('is_active', true)
            ->limit($limit)
            ->get();
    }

    /**
     * Create a new product
     */
    public function createProduct($data)
    {
        $product = Product::create($data);
        Cache::forget('products');
        return $product;
    }

    /**
     * Find product by ID with relations
     */
    public function findIDRelationProduct($id, $relations = [])
    {
        return Product::with($relations)->findOrFail($id);
    }

    /**
     * Find product by ID
     */
    public function findIDProduct($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Paginate products
     */
    public function paginateProduct($paginate = 15)
    {
        return Product::with(['category'])->paginate($paginate);
    }

    /**
     * Get product variants by product ID
     */
    public function productvariantFindbyProduct_id($id, $relations = [])
    {
        return ProductVariant::with($relations)
            ->where('product_id', $id)
            ->get();
    }

    /**
     * Update product
     */
    public function updateProduct($id, $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        Cache::forget('products');
        return $product;
    }

    /**
     * Toggle product status
     */
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        Cache::forget('products');
        return $product;
    }

    /**
     * Get active products
     */
    public function getActiveProducts()
    {
        return Cache::remember('active_products', 3600, function () {
            return Product::where('is_active', true)
                ->with(['category'])
                ->orderBy('pro_name')
                ->get();
        });
    }

    /**
     * Search products
     */
    public function searchProducts($query, $filters = [])
    {
        $products = Product::query();

        if (!empty($query)) {
            $products->where('pro_name', 'like', "%{$query}%")
                ->orWhere('pro_description', 'like', "%{$query}%");
        }

        if (!empty($filters['category_id'])) {
            $products->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['is_active'])) {
            $products->where('is_active', $filters['is_active']);
        }

        return $products->with(['category'])->get();
    }

    /**
     * Store product with variants and galleries
     */
    public function storeProduct($data, $request)
    {
        list(
            $dataProduct,
            $dataProductVariants,
            $dataProductGalleries
        ) = $this->processProductRequest($data, $request);

        return DB::transaction(function () use ($dataProduct, $dataProductVariants, $dataProductGalleries) {
            $product = $this->createProduct($dataProduct);

            foreach ($dataProductVariants as $item) {
                $product->productVariants()->create($item);
            }

            foreach ($dataProductGalleries as $item) {
                $product->galleries()->create(['image' => $item]);
            }

            return $product;
        });
    }

    /**
     * Update product with variants and galleries
     */
    public function updateProductWithVariantsAndGalleries($id, $data, $request)
    {
        list(
            $dataProduct,
            $dataProductVariants,
            $dataProductGalleries,
            $dataGalleriesUpdate
        ) = $this->processProductRequest($data, $request, true);

        return DB::transaction(function () use ($id, $dataProduct, $dataProductVariants, $dataProductGalleries, $dataGalleriesUpdate) {
            $product = $this->findIDProduct($id);

            // Delete old thumbnail if new one is uploaded
            if (!empty($dataProduct['pro_img_thumbnail']) && $product->pro_img_thumbnail) {
                deleteImageStorage($product->pro_img_thumbnail);
            }

            $product->update($dataProduct);

            // Update or create variants
            foreach ($dataProductVariants as $item) {
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $id,
                        'capacity_id' => $item['capacity_id'],
                        'color_id' => $item['color_id']
                    ],
                    [
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'price_sale' => $item['price_sale']
                    ]
                );
            }

            // Update existing galleries
            foreach ($dataProductGalleries ?? [] as $key => $image) {
                $gallery = Gallery::findOrFail($key);

                if (!empty($image) && $gallery->image) {
                    deleteImageStorage($gallery->image);
                }

                $product->galleries()->where('id', $key)->update(['image' => $image]);
            }

            // Add new galleries
            if (!empty($dataGalleriesUpdate)) {
                foreach ($dataGalleriesUpdate as $item) {
                    $product->galleries()->create(['image' => $item]);
                }
            }

            return $product;
        });
    }

    /**
     * Process product request data
     */
    private function processProductRequest($data, $request, $isUpdate = false)
    {
        // Process product data
        $dataProduct = $request->except(['product_variants', 'image_galleries', 'add_galleries']);

        // Auto generate slug if empty
        if (empty($dataProduct['pro_slug'])) {
            $dataProduct['pro_slug'] = generateSlug($dataProduct['pro_name']);
        }

        // Set default values
        $dataProduct['pro_featured'] = $dataProduct['pro_featured'] ?? false;
        $dataProduct['is_hot'] = $dataProduct['is_hot'] ?? false;
        $dataProduct['is_active'] = $dataProduct['is_active'] ?? true;
        $dataProduct['pro_views'] = $dataProduct['pro_views'] ?? 0;
        $dataProduct['pro_prating'] = $dataProduct['pro_prating'] ?? 0;
        $dataProduct['pro_price_sale'] = !empty($dataProduct['pro_price_sale']) ? $dataProduct['pro_price_sale'] : 0;
        $dataProduct['pro_price_regular'] = !empty($dataProduct['pro_price_regular']) ? $dataProduct['pro_price_regular'] : 0;

        // Handle thumbnail upload
        if ($request->hasFile('pro_img_thumbnail')) {
            $dataProduct['pro_img_thumbnail'] = createImageStorage('products', $request->file('pro_img_thumbnail'));
        }

        // Process variants
        $dataProductVariantsTmp = $request->product_variants ?? [];
        $dataProductVariants = [];

        foreach ($dataProductVariantsTmp as $key => $value) {
            $tmp = explode('-', $key);

            if ((!empty($value['quantity']) && $value['quantity'] > 0) ||
                (!empty($value['price']) && $value['price'] > 0)
            ) {

                $dataProductVariants[] = [
                    'capacity_id' => $tmp[0],
                    'color_id' => $tmp[1],
                    'quantity' => $value['quantity'] ?? 0,
                    'price' => !empty($value['price']) ? $value['price'] : 0,
                    'price_sale' => !empty($value['price_sale']) ? $value['price_sale'] : 0,
                ];
            }
        }

        // Process galleries
        $dataProductGalleriesTmp = $request->image_galleries ?? [];
        $dataProductGalleries = [];

        foreach ($dataProductGalleriesTmp as $key => $image) {
            if (!empty($image)) {
                $dataProductGalleries[$key] = createImageStorage('galleries', $image);
            }
        }

        // Process additional galleries for update
        $dataGalleriesUpdate = [];
        if ($isUpdate) {
            $dataGalleriesUpdateTmp = $request->add_galleries ?? [];
            foreach ($dataGalleriesUpdateTmp as $image) {
                if (!empty($image)) {
                    $dataGalleriesUpdate[] = createImageStorage('galleries', $image);
                }
            }
        }

        return [$dataProduct, $dataProductVariants, $dataProductGalleries, $dataGalleriesUpdate];
    }
}

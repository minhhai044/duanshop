<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\ProductVariant;
use App\Services\CapacityService;
use App\Services\CategoryService;
use App\Services\ColorService;
use App\Services\GalleryService;
use App\Services\ProductService;
use App\Services\TagService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $colorService;
    protected $tagService;
    protected $capacityService;
    protected $galleryService;
    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
        ColorService $colorService,
        TagService $tagService,
        CapacityService $capacityService,
        GalleryService $galleryService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->colorService = $colorService;
        $this->tagService = $tagService;
        $this->capacityService = $capacityService;
        $this->galleryService = $galleryService;
    }
    public function index()
    {

        $products = $this->productService->getProduct(['category']);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories =   $this->categoryService->pluckCategory('cate_name', 'id');
        $colors     =   $this->colorService->pluckColor('color_name', 'id');
        $capacities =   $this->capacityService->pluckCapacity('cap_name', 'id');
        $tags       =   $this->tagService->pluckTag('tag_name', 'id');

        return view('admin.products.create', compact(['categories', 'colors', 'capacities', 'tags']));
    }
    /**
     * Store a newly created resource in storage.
     */
    private function ProductLogicRequest($request)
    {
        //dataProduct
        $dataProduct = $request->except(['product_variants', 'tags', 'image_galleries']);

        if ($request->hasFile('pro_img_thumbnail')) {

            $dataProduct['pro_img_thumbnail'] = Storage::put('products', $request->file('pro_img_thumbnail'));
        }
        //Variant
        $dataProductVariantsTmp = $request->product_variants;

        $dataProductVariants = [];

        foreach ($dataProductVariantsTmp as $key => $value) {

            $tmp = explode('-', $key);
            $dataProductVariants[] = [
                'capacity_id' => $tmp[0],
                'color_id' => $tmp[1],
                'quantity' => $value['quantity'],
            ];
        }

        //Galleries

        $dataProductGalleriesTmp = $request->image_galleries ?? [];

        $dataProductGalleries = [];

        foreach ($dataProductGalleriesTmp as $key => $image) {

            if (!empty($image)) {

                $dataProductGalleries[$key] = Storage::put('galleries', $image);
            }
        }
        // Thêm gallary khi update
        $dataGalleriesUpdateTmp = $request->add_galleries ?? [];

        $dataGalleriesUpdate = [];

        foreach ($dataGalleriesUpdateTmp as $image) {

            if (!empty($image)) {

                $dataGalleriesUpdate[] = Storage::put('galleries', $image);
            }
        }
        //Tag
        $dataProductTags = $request->tags;

        return [$dataProduct, $dataProductVariants, $dataProductTags, $dataProductGalleries, $dataGalleriesUpdate];
    }
    public function store(StoreProductRequest $request)
    {
        // Lay data

        try {
            list(
                $dataProduct,
                $dataProductVariants,
                $dataProductTags,
                $dataProductGalleries
            ) = $this->ProductLogicRequest($request);


            DB::transaction(function () use ($dataProduct, $dataProductVariants, $dataProductGalleries, $dataProductTags) {

                $product = $this->productService->createProduct($dataProduct);

                foreach ($dataProductVariants as $item) {
                    $product->product_variant()->create($item);
                }

                $product->tags()->attach($dataProductTags);

                foreach ($dataProductGalleries as $item) {

                    $product->galleries()->create(['image' => $item]);
                }
            });
            return redirect()
                ->route('products.index')
                ->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            if (!empty($dataProduct['pro_img_thumbnail']) && Storage::exists($dataProduct['pro_img_thumbnail'])) {
                Storage::delete($dataProduct['pro_img_thumbnail']);
            }

            foreach ($dataProductGalleries as $item) {
                if (!empty($item) && Storage::exists($item)) {
                    Storage::delete($item);
                }
            }
            Log::error(__CLASS__ . '@' . __FUNCTION__, context: [$th->getMessage()]);
            return back()->with('error', 'Thêm mới không thành công !!!');
        }
    }
    public function edit(string $id)
    {
        $data       =   $this->productService->findIDRelationProduct($id, ['product_variant']);

        $categories =   $this->categoryService->pluckCategory('cate_name', 'id');
        $colors     =   $this->colorService->pluckColor('color_name', 'id');
        $capacities =   $this->capacityService->pluckCapacity('cap_name', 'id');
        $tags       =   $this->tagService->pluckTag('tag_name', 'id');


        $product_tags = $data->tags->pluck('id')->all();

        return view('admin.products.edit', compact(
            'data',
            'categories',
            'colors',
            'capacities',
            'tags',
            'product_tags'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            // Lay data
            list(
                $dataProduct,
                $dataProductVariants,
                $dataProductTags,
                $dataProductGalleries,
                $dataGalleriesUpdate
            ) = $this->ProductLogicRequest($request);

            DB::transaction(function () use ($dataProduct, $dataProductVariants, $dataProductTags, $dataProductGalleries, $id, $dataGalleriesUpdate) {

                $product = $this->productService->findIDProduct($id);

                if (!empty($dataProduct['pro_img_thumbnail']) && Storage::exists($product->pro_img_thumbnail)) {
                    Storage::delete($product->pro_img_thumbnail);
                }

                $product->update($dataProduct);



                foreach ($dataProductVariants as $item) {
                    ProductVariant::query()->where([
                        ['product_id', $id],
                        ['capacity_id', $item['capacity_id']],
                        ['color_id', $item['color_id']]
                    ])
                        ->update(['quantity' => $item['quantity']]);
                }

                $product->tags()->sync($dataProductTags);


                foreach ($dataProductGalleries ?? [] as $key => $image) {

                    $gallery = $this->galleryService->findIdGallery($key);

                    if (!empty($image) && Storage::exists($gallery->image)) {
                        Storage::delete($gallery->image);
                    }

                    $product->galleries()->where('id', $key)->update(['image' => $image]);
                }
                if (!empty($dataGalleriesUpdate)) {
                    foreach ($dataGalleriesUpdate ?? [] as $item) {

                        $product->galleries()->create(['image' => $item]);
                    }
                }
            });
            return redirect()
                ->route('products.index')
                ->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            if (!empty($dataProduct['pro_img_thumbnail']) && Storage::exists($dataProduct['pro_img_thumbnail'])) {
                Storage::delete($dataProduct['pro_img_thumbnail']);
            }
            foreach ($dataProductGalleries as $item) {
                if (!empty($item) && Storage::exists($item)) {
                    Storage::delete($item);
                }
            }
            Log::error(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);

            return back()->with('error', 'Update không thành công !!!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {

                $products = $this->productService->findIDProduct($id);

                $products->tags()->sync([]);

                $products->galleries()->delete();

                $products->product_variant()->delete();

                $products->delete();

                foreach ($products->galleries ?? [] as $item) {
                    if (!empty($item) && Storage::exists($item)) {
                        Storage::delete($item);
                    }
                }
            });
            return redirect()->route('products.index')->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            return redirect()->route('products.index')->with('error', 'Thao tác không thành công!');
        }
    }
}

<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductVariant;

class ProductRepocitory
{
    protected $product;
    protected $productVariant;
    public function __construct(
        Product $product,
        ProductVariant $productVariant
    ) {
        $this->product = $product;
        $this->productVariant = $productVariant;
    }
    public function get($relation)
    {
        return $this->product->with($relation)->get();
    }
    public function getFeatured($limit)
    {
        return $this->product->where('pro_featured', 1)->latest('id')->limit($limit)->get();
    }
    public function create($data)
    {
        return $this->product->create($data);
    }

    public function findIDRelation($id, $relation)
    {
        return $this->product->with($relation)->find($id);
    }
    public function findId($id)
    {
        return $this->product->find($id);
    }
    public function paginate($paginate)
    {
        return $this->product->latest('id')->paginate($paginate);
    }

    // ProductVariant
    public function createVariant($data)
    {
        return $this->productVariant->create($data);
    }
    public function variantFindbyProduct_id($id, $relation)
    {
        return $this->productVariant->with($relation)->where('product_id', $id)->get();
    }
}

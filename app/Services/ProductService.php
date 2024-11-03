<?php

namespace App\Services;

use App\Repositories\ProductRepocitory;

class ProductService
{
    protected $productRepocitory;
    public function __construct(
        ProductRepocitory $productRepocitory
    ) {
        $this->productRepocitory = $productRepocitory;
    }
    public function getProduct($relation)
    {
        return $this->productRepocitory->get($relation);
    }

    public function getFeaturedProduct($limit)
    {
        return $this->productRepocitory->getFeatured($limit);
    }
    public function createProduct($data)
    {
        return $this->productRepocitory->create($data);
    }
    public function findIDRelationProduct($id, $relation)
    {
        return $this->productRepocitory->findIDRelation($id, $relation);
    }
    public function findIDProduct($id)
    {
        return $this->productRepocitory->findId($id);
    }
    public function paginateProduct($paginate)
    {
        return $this->productRepocitory->paginate($paginate);
    }
    public function productvariantFindbyProduct_id($id, $ralation)
    {
        return $this->productRepocitory->variantFindbyProduct_id($id, $ralation);
    }
}

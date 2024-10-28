<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

/**
 * Class CategoryService
 * @package App\Services
 */
class CategoryService
{
    public $CategoryRepository;
    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->CategoryRepository = $categoryRepository;
    }
    public function getCategory()
    {
        return $this->CategoryRepository->get();
    }
    public function createCategory($data)
    {
        return $this->CategoryRepository->create($data);
    }
    public function findIdCategory($id){
        return $this->CategoryRepository->find($id);
    }
    public function updateCategory($id,$data){
        return $this->CategoryRepository->update($id,$data);
    }
    public function deleteCategory($id){
        return $this->CategoryRepository->delete($id);
    }
    public function pluckCategory($column,$key){
        return $this->CategoryRepository->pluck($column,$key);
    }
}

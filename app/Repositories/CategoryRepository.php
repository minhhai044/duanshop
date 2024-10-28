<?php

namespace App\Repositories;

use App\Models\Category;

/**
 * Class CategoryService
 * @package App\Services
 */
class CategoryRepository
{
    protected $category;
    public function __construct(
        Category $category
    ) {
        $this->category = $category;
    }
    public function get()
    {
        return $this->category->get();
    }
    public function create($data)
    {
        return $this->category->create($data);
    }
    public function find($id)
    {
        return $this->category->find($id);
    }
    public function update($id, $data)
    {
        $update = $this->category->find($id);
        $update->update($data);
        return $update;
    }
    public function delete($id){
        $delete = $this->category->find($id);
        $delete->delete();
        return $delete;
    }
    public function pluck($column, $key)
    {
        return $this->category->pluck($column, $key)->all();
    }
}

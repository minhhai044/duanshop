<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

/**
 * Class CategoryService
 * @package App\Services
 */
class CategoryService
{
    /**
     * Get all categories
     */
    public function getCategory()
    {
        return Category::orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a new category
     */
    public function createCategory($data)
    {
        // Auto generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['cate_name']);
        }
        
        // Set default is_active
        $data['is_active'] = $data['is_active'] ?? true;
        
        $category = Category::create($data);
        Cache::forget('categories');
        return $category;
    }

    /**
     * Find category by ID
     */
    public function findIdCategory($id)
    {
        return Category::findOrFail($id);
    }

    /**
     * Update category
     */
    public function updateCategory($id, $data)
    {
        $category = Category::findOrFail($id);
        
        // Auto generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['cate_name']);
        }
        
        // Set default is_active
        $data['is_active'] = $data['is_active'] ?? $category->is_active;
        
        $category->update($data);
        Cache::forget('categories');
        return $category;
    }

    /**
     * Delete category (soft delete if applicable)
     */
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $result = $category->delete();
        Cache::forget('categories');
        return $result;
    }

    /**
     * Get categories for dropdown/select options
     */
    public function pluckCategory($column, $key)
    {
        return Category::where('is_active', true)->pluck($column, $key);
    }

    /**
     * Get active categories
     */
    public function getActiveCategories()
    {
        return Cache::remember('active_categories', 3600, function () {
            return Category::where('is_active', true)
                ->orderBy('cate_name')
                ->get();
        });
    }

    /**
     * Create a new category with image handling
     */
    public function createCategoryWithImage($data, $request)
    {
        // Auto generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['cate_name']);
        }
        
        // Handle image upload
        if ($request->hasFile('cate_image')) {
            $data['cate_image'] = createImageStorage('categories', $request->file('cate_image'));
        }
        
        // Set default is_active
        $data['is_active'] = $data['is_active'] ?? true;
        
        $category = Category::create($data);
        Cache::forget('categories');
        return $category;
    }

    /**
     * Update category with image handling
     */
    public function updateCategoryWithImage($id, $data, $request)
    {
        $category = Category::findOrFail($id);
        
        // Auto generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['cate_name']);
        }
        
        // Handle image upload
        if ($request->hasFile('cate_image')) {
            // Delete old image if exists
            if ($category->cate_image) {
                deleteImageStorage($category->cate_image);
            }
            $data['cate_image'] = createImageStorage('categories', $request->file('cate_image'));
        }
        
        // Set default is_active
        $data['is_active'] = $data['is_active'] ?? $category->is_active;
        
        $category->update($data);
        Cache::forget('categories');
        return $category;
    }

    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        Cache::forget('categories');
        Cache::forget('active_categories');
        return $category;
    }
}
<?php

namespace App\Services;

use App\Models\Gallery;
use Illuminate\Support\Facades\Cache;

/**
 * Class GalleryService
 * @package App\Services
 */
class GalleryService
{
    /**
     * Find gallery by ID
     */
    public function findIdGallery($id)
    {
        return Gallery::findOrFail($id);
    }

    /**
     * Create gallery
     */
    public function createGallery($data)
    {
        $gallery = Gallery::create($data);
        Cache::forget('galleries');
        return $gallery;
    }

    /**
     * Update gallery
     */
    public function updateGallery($id, $data)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->update($data);
        Cache::forget('galleries');
        return $gallery;
    }

    /**
     * Delete gallery
     */
    public function deleteGallery($id)
    {
        $gallery = Gallery::findOrFail($id);
        
        // Delete image file if exists
        if ($gallery->image) {
            deleteImageStorage($gallery->image);
        }
        
        $result = $gallery->delete();
        Cache::forget('galleries');
        return $result;
    }

    /**
     * Get galleries by product ID
     */
    public function getGalleriesByProduct($productId)
    {
        return Gallery::where('product_id', $productId)->get();
    }
}

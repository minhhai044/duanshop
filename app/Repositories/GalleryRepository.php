<?php

namespace App\Repositories;

use App\Models\Gallery;

class GalleryRepository
{
    protected $gallery;
    public function __construct(
        Gallery $gallery
    ) {
        $this->gallery = $gallery;
    }

    public function create($data)
    {
        return $this->gallery->create($data);
    }

    public function findId($id){
        return $this->gallery->find($id);
    }
}

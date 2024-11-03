<?php

namespace App\Services;

use App\Repositories\GalleryRepository;

class GalleryService
{
    protected $galleryRepository;
    public function __construct(
        GalleryRepository $galleryRepository
    ) {
        $this->galleryRepository = $galleryRepository;
    }
    public function findIdGallery($id){
        return $this->galleryRepository->findId($id);
    }
}

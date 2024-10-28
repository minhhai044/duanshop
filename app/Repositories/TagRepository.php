<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository
{
    protected $tag;
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }
    public function pluck($column, $key)
    {
        return $this->tag->pluck($column, $key)->all();
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Tag as TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'author_id' => $this->author_id,
            'is_published' => $this->is_published,
            'category_id' => $this->parent_id,
            'category' => new CategoryResource($this->category),
            'tags' => TagResource::collection($this->tags),
            'published_at' => !empty($this->published_at) ? $this->published_at->timestamp : null,
            'created_at' => $this->created_at->timestamp,
            'updated_at' => $this->updated_at->timestamp,
            'deleted_at' => !empty($this->deleted_at) ? $this->deleted_at->timestamp : null,
        ];
    }
}

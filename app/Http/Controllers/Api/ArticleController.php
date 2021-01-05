<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Article as ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class ArticleController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function getList()
    {
        return ArticleResource::collection(Article::getPublished());
    }

    /**
     * @param int $id
     * @return ArticleResource
     */
    public function getDetail(int $id)
    {
        return new ArticleResource(Article::findOrFail($id));
    }
}

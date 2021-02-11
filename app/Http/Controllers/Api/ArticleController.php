<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResourceCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;


class ArticleController extends Controller
{
    /**
     * @return ArticleResourceCollection
     */
    public function getList(): ArticleResourceCollection
    {
        return new ArticleResourceCollection(Article::getPublished());
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

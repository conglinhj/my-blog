<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResourceCollection;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;


class ArticleController extends Controller
{

    /**
     * @var ArticleService
     */
    private $articleServices;

    function __construct(ArticleService $articleServices) {
        $this->articleServices = $articleServices;
    }

    /**
     * @return ArticleResourceCollection
     */
    public function getList(): ArticleResourceCollection
    {
        return $this->articleServices->getPublishedArticles();
    }

    /**
     * @param int $id
     * @return ArticleResource
     */
    public function getDetail(int $id): ArticleResource
    {
        return $this->articleServices->getPublishedArticle($id);
    }
}

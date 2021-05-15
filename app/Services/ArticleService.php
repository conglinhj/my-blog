<?php


namespace App\Services;

use App\Http\Requests\ArticleCollectionRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleResourceCollection;
use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;

class ArticleService
{

    /**
     * @var ArticleRepositoryInterface
     */
    private $articleRepository;

    function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return ArticleResourceCollection
     */
    function getPublishedArticles(): ArticleResourceCollection
    {
        return new ArticleResourceCollection($this->articleRepository->getAllPublished());
    }

    /**
     * @param int $id
     * @return ArticleResource
     */
    function getPublishedArticle(int $id): ArticleResource
    {
        return new ArticleResource($this->articleRepository->getOnePublished($id));
    }
}

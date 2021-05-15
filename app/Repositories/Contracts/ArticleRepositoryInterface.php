<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\ArticleCollectionRequest;
use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface {

    /**
     * @param int $id
     * @return Article
     */
    function getOne(int $id): Article;

    /**
     * @return Article[]
     */
    function getAll(): array;

    /**
     * @return Article
     */
    function create(): Article;

    /**
     * @return Article
     */
    function update(): Article;

    /**
     * @return boolean
     */
    function delete(): bool;

    /**
     * @param int $id
     * @return Article
     */
    function getOnePublished(int $id): Article;

    /**
     * @return Article[]
     */
    function getAllPublished(): array;

    function paginate(ArticleCollectionRequest $request): LengthAwarePaginator;
}

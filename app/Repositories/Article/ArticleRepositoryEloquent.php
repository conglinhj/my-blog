<?php


namespace App\Repositories\Article;


use App\Models\Article;

class ArticleRepositoryEloquent implements ArticleRepositoryInterface
{

    function getOne(int $id): Article
    {
        return Article::findOrFail($id);
    }

    function getOnePublished(int $id): Article {
        return Article::where([
            ['id', $id],
            ['is_published', true]
        ])->first();
    }

    function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @return Article[]
     */
    function getAllPublished(): array {
        return Article::where([['is_published', true]])->get()->all();
    }

    public function create(): Article
    {
        // TODO: Implement create() method.
    }

    public function update(): Article
    {
        // TODO: Implement update() method.
    }

    public function delete(): bool
    {
        // TODO: Implement delete() method.
    }
}

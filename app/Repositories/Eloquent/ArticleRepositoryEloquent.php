<?php


namespace App\Repositories\Eloquent;


use App\Http\Requests\ArticleCollectionRequest;
use App\Http\Resources\ArticleResourceCollection;
use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ArticleRepositoryEloquent implements ArticleRepositoryInterface
{

    /**
     * @param int $id
     * @param string[] $columns
     * @return Article
     */
    function getOne(int $id, $columns = ['*']): Article
    {
        return Article::findOrFail($id, $columns);
    }

    /**
     * @param int $id
     * @param string[] $columns
     * @return Article
     */
    function getOnePublished(int $id, $columns = ['*']): Article {
        return Article::where([
            ['id', $id],
            ['is_published', true]
        ])->first($columns);
    }

    /**
     * @param string[] $columns
     * @return array
     */
    function getAll($columns = ['*']): array
    {
        return Article::all($columns);
    }

    /**
     * @param ArticleCollectionRequest $request
     * @param string[] $columns
     * @return LengthAwarePaginator
     */
    function paginate(ArticleCollectionRequest $request, $columns = ['*']): LengthAwarePaginator
    {
        $query = Article::query();

        $with_deleted = $request->get('with_deleted', false);
        $sort_conditions = explode(',', $request->get('sort', ''));

        if ($with_deleted) {
            $query->withTrashed();
        }

        foreach ($sort_conditions as $condition) {
            $exploded = explode(':', $condition);
            if (count($exploded) == 2) {
                $query->orderBy($exploded[0], $exploded[1]);
            }
        }

        return $query->paginate($request->get('limit', 10), $columns);
    }

    /**
     * @param string[] $columns
     * @return Article[]
     */
    function getAllPublished($columns = ['*']): array {
        return Article::where([['is_published', true]])->get($columns)->all();
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

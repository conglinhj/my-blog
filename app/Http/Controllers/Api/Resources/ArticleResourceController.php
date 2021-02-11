<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCollectionRequest;
use App\Http\Resources\ArticleResourceCollection;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Requests\ArticleBulkDeleteRequest;
use App\Models\Article;

class ArticleResourceController extends Controller
{
    /**
     * @param ArticleCollectionRequest $request
     * @return ArticleResourceCollection
     */
    public function index(ArticleCollectionRequest $request): ArticleResourceCollection
    {
        $query = Article::query();

        if ($request->get('with_deleted', false)) {
            $query->withTrashed();
        }

        $sort_conditions = explode(',', $request->get('sort', ''));
        foreach ($sort_conditions as $condition) {
            $exploded = explode(':', $condition);
            if (count($exploded) == 2) {
                $query->orderBy($exploded[0], $exploded[1]);
            }
        }

        return new ArticleResourceCollection($query->paginate($request->get('limit', 10)));
    }

    /**
     * @param ArticleStoreRequest $request
     * @return ArticleResource
     */
    public function store(ArticleStoreRequest $request): ArticleResource
    {
        $article = new Article($request->only([
            'title',
            'description',
            'content',
            'category_id',
            'is_published'
        ]));
        $article->author_id = 1;
        $article->save();
        return new ArticleResource($article);
    }

    /**
     * @param int $id
     * @return ArticleResource
     */
    public function show(int $id): ArticleResource
    {
        return new ArticleResource(Article::with(['category', 'tags'])->findOrFail($id));
    }

    /**
     * @param ArticleUpdateRequest $request
     * @param int $id
     * @return ArticleResource
     */
    public function update(ArticleUpdateRequest $request, int $id): ArticleResource
    {
        $article = Article::findOrFail($id);
        $article->fill($request->only([
            'title',
            'description',
            'content',
            'category_id',
            'is_published'
        ]));
        $article->save();
        $article->tags()->sync($request->get('tags', []));
        return new ArticleResource($article);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return new JsonResponse(Article::findOrFail($id)->delete());
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function publish(int $id): JsonResponse
    {
        return new JsonResponse(Article::findOrFail($id)->publish());
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function draft(int $id): JsonResponse
    {
        return new JsonResponse(Article::findOrFail($id)->draft());
    }

    /**
     * @param ArticleBulkDeleteRequest $request
     * @return JsonResponse
     */
    public function bulkAction(ArticleBulkDeleteRequest $request): JsonResponse
    {
        switch ($request->get('action_name')) {
            case 'publish':
            case 'draft':
                return new JsonResponse(Article::whereIn('id', $request->get('article_ids'))->update([
                    'is_published' => $request->get('action_name') == 'publish'
                ]));
            case 'delete':
                return new JsonResponse(Article::whereIn('id', $request->get('article_ids'))->delete());
            default:
                return new JsonResponse(false);
        }
    }
}

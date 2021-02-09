<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Article as ArticleResource;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Requests\ArticleBulkDeleteRequest;
use App\Models\Article;

class ArticleResourceController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ArticleResource::collection(Article::all());
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

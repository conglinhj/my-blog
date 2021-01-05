<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Article as ArticleResource;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleResourceController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return ArticleResource::collection(Article::all());
    }

    /**
     * @param ArticleStoreRequest $request
     * @return ArticleResource
     */
    public function store(ArticleStoreRequest $request)
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
    public function show(int $id)
    {
        return new ArticleResource(Article::with(['category', 'tags'])->findOrFail($id));
    }

    /**
     * @param ArticleUpdateRequest $request
     * @param int $id
     * @return ArticleResource
     */
    public function update(ArticleUpdateRequest $request, $id)
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
    public function destroy(int $id)
    {
        return new JsonResponse(Article::findOrFail($id)->delete());
    }
}

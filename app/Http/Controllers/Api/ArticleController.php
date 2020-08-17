<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Article as ArticleResource;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;

class ArticleController extends Controller
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
        $article = new Article($request->only([]));
        $article->save();
        return new ArticleResource($article);
    }

    /**
     * @param int $id
     * @return ArticleResource
     */
    public function show($id)
    {
        return new ArticleResource(Article::findOrFail($id));
    }

    /**
     * @param ArticleUpdateRequest $request
     * @param int $id
     * @return ArticleResource
     */
    public function update(ArticleUpdateRequest $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->fill($request->only([]));
        $article->save();
        return new ArticleResource($article);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        Article::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}

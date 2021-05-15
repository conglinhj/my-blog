<?php

namespace App\Http\Controllers\Api\Resources;

use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleCollectionRequest;
use App\Http\Resources\ArticleResourceCollection;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Requests\ArticleBulkDeleteRequest;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleResourceController extends Controller
{

    /**
     * @var ArticleService
     */
    private $articleService;

    function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * @param ArticleCollectionRequest $request
     * @return ArticleResourceCollection
     */
    public function index(ArticleCollectionRequest $request): ArticleResourceCollection
    {
        $query = Article::query();
        $query->where('author_id', $request->user()->id);

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
        $article->author_id = $request->user()->id;
        $article->save();
        return new ArticleResource($article);
    }

    /**
     * @param int $id
     * @return ArticleResource
     */
    public function show(int $id): ArticleResource
    {
        $article = Article::with(['category', 'tags'])
            ->where('author_id', Auth::user()->id)
            ->findOrFail($id);

        return new ArticleResource($article);
    }

    /**
     * @param ArticleUpdateRequest $request
     * @param int $id
     * @return ArticleResource
     */
    public function update(ArticleUpdateRequest $request, int $id): ArticleResource
    {
        $article = Article::where('author_id', Auth::user()->id)->findOrFail($id);
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

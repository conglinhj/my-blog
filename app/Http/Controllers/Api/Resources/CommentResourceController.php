<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentResourceController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return CommentResource::collection(Comment::all());
    }

    /**
     * @param CommentStoreRequest $request
     * @return CommentResource
     */
    public function store(CommentStoreRequest $request): CommentResource
    {
        $comment = new Comment($request->only([
            'parent_id',
            'article_id',
            'content'
        ]));
        $comment->author_id = Auth::id();
        $comment->save();
        return new CommentResource($comment);
    }

    /**
     * @param int $id
     * @return CommentResource
     */
    public function show($id): CommentResource
    {
        return new CommentResource(Comment::findOrFail($id));
    }

    /**
     * @param CommentUpdateRequest $request
     * @param int $id
     * @return CommentResource
     */
    public function update(CommentUpdateRequest $request, $id): CommentResource
    {
        $comment = Comment::findOrFail($id);
        $comment->fill($request->only([ 'content' ]));
        $comment->save();
        return new CommentResource($comment);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        return new JsonResponse(Comment::findOrFail($id)->delete());
    }
}

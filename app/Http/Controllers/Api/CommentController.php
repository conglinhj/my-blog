<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return CommentResource::collection(Comment::all());
    }

    /**
     * @param CommentStoreRequest $request
     * @return CommentResource
     */
    public function store(CommentStoreRequest $request)
    {
        $comment = new Comment($request->only([]));
        $comment->save();
        return new CommentResource($comment);
    }

    /**
     * @param int $id
     * @return CommentResource
     */
    public function show($id)
    {
        return new CommentResource(Comment::findOrFail($id));
    }

    /**
     * @param CommentUpdateRequest $request
     * @param int $id
     * @return CommentResource
     */
    public function update(CommentUpdateRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->fill($request->only([]));
        $comment->save();
        return new CommentResource($comment);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        Comment::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}

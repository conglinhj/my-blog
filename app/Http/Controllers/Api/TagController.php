<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Http\Resources\Tag as TagResource;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return TagResource::collection(Tag::all());
    }

    /**
     * @param TagStoreRequest $request
     * @return TagResource
     */
    public function store(TagStoreRequest $request)
    {
        $tag = new Tag($request->only(['name']));
        $tag->save();
        return new TagResource($tag);
    }

    /**
     * @param int $id
     * @return TagResource
     */
    public function show($id)
    {
        return new TagResource(Tag::findOrFail($id));
    }

    /**
     * @param TagUpdateRequest $request
     * @param int $id
     * @return TagResource
     */
    public function update(TagUpdateRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->fill($request->only(['name']));
        $tag->save();
        return new TagResource($tag);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        Tag::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}

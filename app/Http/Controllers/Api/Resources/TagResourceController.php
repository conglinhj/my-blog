<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Http\Resources\Tag as TagResource;
use App\Models\Tag;

class TagResourceController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return TagResource::collection(Tag::all());
    }

    /**
     * @param TagStoreRequest $request
     * @return TagResource
     */
    public function store(TagStoreRequest $request): TagResource
    {
        $tag = new Tag($request->only(['name']));
        $tag->save();
        return new TagResource($tag);
    }

    /**
     * @param int $id
     * @return TagResource
     */
    public function show($id): TagResource
    {
        return new TagResource(Tag::findOrFail($id));
    }

    /**
     * @param TagUpdateRequest $request
     * @param int $id
     * @return TagResource
     */
    public function update(TagUpdateRequest $request, $id): TagResource
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
    public function destroy($id): JsonResponse
    {
        return new JsonResponse(Tag::findOrFail($id)->delete());
    }
}

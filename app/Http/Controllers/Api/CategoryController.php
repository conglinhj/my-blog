<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * @param CategoryStoreRequest $request
     * @return CategoryResource
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = new Category($request->only('name','parent_id', 'description'));
        $category->save();
        return new CategoryResource($category);
    }

    /**
     * @param $id
     * @return CategoryResource
     */
    public function show($id)
    {
        return new CategoryResource(Category::findOrFail($id));
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param int $id
     * @return CategoryResource
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->fill($request->only('name','parent_id', 'description'));
        $category->save();
        return new CategoryResource($category);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @return CategoryCollection
     */
    public function index()
    {
        return new CategoryCollection(Category::paginate(10));
    }

    /**
     * @param CategoryStoreRequest $request
     * @return CategoryResource
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = new Category($request->only([
            'name',
            'parent_id',
            'description'
        ]));
        $category->save();
        return new CategoryResource($category);
    }

    /**
     * @param int $id
     * @return CategoryResource
     */
    public function show(int $id)
    {
        return new CategoryResource(Category::findOrFail($id));
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param int $id
     * @return CategoryResource
     */
    public function update(CategoryUpdateRequest $request, int $id)
    {
        $category = Category::findOrFail($id);
        $category->fill($request->only([
            'name',
            'parent_id',
            'description'
        ]));
        $category->save();
        return new CategoryResource($category);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        return new JsonResponse(Category::findOrFail($id)->delete());
    }
}

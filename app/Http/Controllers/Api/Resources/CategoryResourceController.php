<?php

namespace App\Http\Controllers\Api\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResourceCollection;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;

class CategoryResourceController extends Controller
{
    /**
     * @return CategoryResourceCollection
     */
    public function index(): CategoryResourceCollection
    {
        return new CategoryResourceCollection(Category::paginate(10));
    }

    /**
     * @param CategoryStoreRequest $request
     * @return CategoryResource
     */
    public function store(CategoryStoreRequest $request): CategoryResource
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
    public function show(int $id): CategoryResource
    {
        return new CategoryResource(Category::findOrFail($id));
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param int $id
     * @return CategoryResource
     */
    public function update(CategoryUpdateRequest $request, int $id): CategoryResource
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
    public function destroy(int $id): JsonResponse
    {
        return new JsonResponse(Category::findOrFail($id)->delete());
    }

    /**
     * @param Request $request
     * @param int $id
     * @return CategoryResourceCollection
     */
    public function getPossibleParentCategories(Request $request, int $id): CategoryResourceCollection
    {
        $category = Category::findOrFail($id);
        return new CategoryResourceCollection(
            Category::query()
            ->where('id', '!=', $category->id)
            ->where('id', '!=', $category->parent_id)
            ->when($request->get('name'), function ($query, $name) {
                return $query->where('name', 'like', '%'.$name.'%');
            })
            ->when($category->level, function ($query, $level) {
                return $query->where('level', '<', $level);
            })
            ->get()
        );
    }
}

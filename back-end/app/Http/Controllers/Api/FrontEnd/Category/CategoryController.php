<?php

namespace App\Http\Controllers\Api\FrontEnd\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FrontEnd\Category\DeleteMultiCategoryRequest;
use App\Http\Requests\api\FrontEnd\Category\StoreCategoryRequest;
use App\Http\Requests\api\FrontEnd\Category\UpdateCategoryRequest;
use App\Http\Resources\Api\FrontEnd\Category\CategoryResource;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'categories' => CategoryResource::collection($categories)
        ], 200);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json(['message' => 'Category Has Been Created'], 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(
            [
                'category' => new CategoryResource($category)
            ],
            200
        );
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return response()->json(['message' => 'Category Has Been Updated'], 201);
    }

    public function destroy($id, $multi = null)
    {
        Category::findOrFail($id)->delete();
        if ($multi === null) {
            return response()->json(['message' => 'Category Has Been Deleted Successfully'], 200);
        }
    }

    public function destroyMulti(DeleteMultiCategoryRequest $request)
    {
        foreach ($request->categories as $category) {
            $this->destroy($category['id'], true);
        }
        return response()->json(['message' => 'Categories Has Been Deleted'], 200);
    }
}

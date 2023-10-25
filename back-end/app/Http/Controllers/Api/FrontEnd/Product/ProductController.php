<?php

namespace App\Http\Controllers\Api\FrontEnd\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\FrontEnd\Product\StoreProductRequest;
use App\Http\Requests\api\FrontEnd\Product\UpdateProductRequest;
use App\Http\Resources\Api\FrontEnd\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with('category')->orderBy('id','DESC')->get();
        return response()->json([
            'products' => ProductResource::collection($products)
        ], 200);
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());
        return response()->json(['message' => 'Product Created Successfully'], 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(
            [
                'product' => new ProductResource($product)
            ],
            200
        );
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return response()->json(['message' => 'Product Updated Successfully']);
    }

    public function destroy($id, $multi = null)
    {
        Product::findOrFail($id)->delete();
        if ($multi === null) {
            return response()->json(['message' => 'Product Deleted']);
        }
    }

    public function destroyMulti(Request $request)
    {
        foreach ($request->products as $product) {
            $this->destroy($product['id'], true);
        }
        return response()->json(['message' => 'Categories Has Been Deleted'], 200);
    }
}

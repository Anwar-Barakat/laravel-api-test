<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageSize   = $request->page_size ?? 20;
        $products   = Product::query()->paginate($pageSize);
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ProductRepository $repository)
    {
        $created= $repository->create($request->only(['name','body','price']));
        return new ProductResource($created);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, ProductRepository $repository)
    {
        $product = $repository->update($product, $request->only(['name','body','price']));
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductRepository $repository)
    {
        $deleted = $repository->delete($product);
        return new JsonResponse([
            'message' => 'product was successfully deleted',
        ]);
    }
}

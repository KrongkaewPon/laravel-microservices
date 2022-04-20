<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Jobs\ProductUpdated;
use App\Jobs\ProductDeleted;
use App\Jobs\ProductCreated;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        ProductCreated::dispatch($product->toArray())->onQueue('checkout_topic');

        return response($product, Response::HTTP_CREATED);
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only('title', 'description', 'image', 'price'));

        ProductUpdated::dispatch($product->toArray())->onQueue('checkout_topic');

        return response($product, Response::HTTP_ACCEPTED);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        ProductDeleted::dispatch(['id' => $product->id])->onQueue('checkout_topic');

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $filters = $request->all();
        $response = $this->productService->index($filters);
        return $response;
    }

    public function store(ProductRequest $request)
    {
        $productData = $request->only(['name', 'price']);
        $image = $request->file('image');
        $response = $this->productService->store($productData, $image);
        return $response;
    }

    public function show($id)
    {
        $response = $this->productService->show($id);
        return $response;
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $productData = $request->only(['name', 'price']);
        $image = $request->file('image');
        $response = $this->productService->update($id, $productData, $image);
        return $response;
    }

    public function destroy($id)
    {
        $response = $this->productService->destroy($id);
        return $response;
    }

    public function updateStatus(Request $request, $id)
    {
        $productData = $request->only(['status_id']);
        $user = Auth::guard('api')->user();
        $response = $this->productService->updateStatus($user, $id, $productData);
        return $response;
    }
}

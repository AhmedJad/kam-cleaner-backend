<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\User;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    //Dashboard endpoints
    public function store(StoreProductRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $product = $this->productRepository->store($request->input());
        return $product;
    }
    public function update(UpdateProductRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->productRepository->update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_product"];
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $oldImage = $this->productRepository->delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }
    //This End point for both dashboard and web
    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->productRepository->getPage(request()->page_size, $text);
    }
    //Front End points
    public function show(Product $product)
    {
        return $product;
    }
    public function getAllProducts()
    {
        return $this->productRepository->getAllProducts();
    }
}

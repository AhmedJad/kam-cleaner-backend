<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function store($product)
    {
        return Product::create($product);
    }
    public function update($productInput)
    {
        $product = Product::find($productInput["id"]);
        $oldImage = $product->getImageName();
        $product->name_ar = $productInput["name_ar"];
        $product->name_en = $productInput["name_en"];
        $product->description_ar = $productInput["description_ar"];
        $product->description_en = $productInput["description_en"];
        $product->icon = $productInput["icon"];
        $product->price = $productInput["price"];
        $product->features = $productInput["features"];
        $product->image = isset($productInput["image"]) ? $productInput["image"] : $oldImage;
        $product->save();
        return ["old_image" => $oldImage, "updated_product" => $product];
    }
    public function delete($id)
    {
        $product = Product::find($id);
        $oldImage = null;
        if ($product) {
            $oldImage = $product->getImageName();
            $product->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize, $text)
    {
        return Product::whereRaw('LOWER(`name_ar`) LIKE ? or LOWER(`name_en`) LIKE ?', [
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%',
        ])->paginate($pageSize);
    }
    public function getAllProducts()
    {
        return Product::get();
    }
}

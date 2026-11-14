<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getPaginated()
    {
        return Product::latest()->paginate(10);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
}
<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function createProduct(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->create($data);

            Log::info('Product created', ['product_id' => $product->id]);

            return $product;
        });
    }

    public function updateProduct(Product $product, array $data)
    {
        return DB::transaction(function () use ($product, $data) {
            $updated = $this->productRepository->update($product, $data);

            Log::info('Product updated', ['product_id' => $updated->id]);

            return $updated;
        });
    }

    public function deleteProduct(Product $product)
    {
        return DB::transaction(function () use ($product) {
            $this->productRepository->delete($product);

            Log::info('Product deleted', ['product_id' => $product->id]);
        });
    }
}
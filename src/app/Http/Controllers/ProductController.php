<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private ProductRepository $productRepository
    ) {}

    private function rules(): array
    {
        return [
            'name'        => 'required|max:100',
            'price'       => 'required|integer|min:0',
            'description' => 'nullable',
            'stock'       => 'required|integer|min:0',
            'category'    => 'required|max:50',
        ];
    }

    public function index()
    {
        $products = $this->productRepository->getPaginated();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $product = $this->productService->createProduct($request->validate($this->rules()));

        return redirect()->route('products.show', $product)
            ->with('success', '商品を登録しました');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->productService->updateProduct($product, $request->validate($this->rules()));

        return redirect()->route('products.show', $product)
            ->with('success', '商品を更新しました');
    }

    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('products.index')
            ->with('success', '商品を削除しました');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $product = Product::create($request->validate($this->rules()));

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
        $product->update($request->validate($this->rules()));

        return redirect()->route('products.show', $product)
            ->with('success', '商品を更新しました');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', '商品を削除しました');
    }
}
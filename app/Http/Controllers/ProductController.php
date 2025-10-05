<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string'
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'price' => $request->price,
            'description' => $request->description,
            'image' => $request->image
        ]);

        return redirect()->route('products.show', $product);
    }
}
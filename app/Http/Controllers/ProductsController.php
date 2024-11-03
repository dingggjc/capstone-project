<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Products;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $stockStatus = $request->input('stock_status');

        $products = Products::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('product_name', 'LIKE', "%{$query}%")
                ->orWhere('product_description', 'LIKE', "%{$query}%")
                ->orWhere('product_price', 'LIKE', "%{$query}%")
                ->orWhere('product_quantity', 'LIKE', "%{$query}%")
                ->orWhere('created_at', 'LIKE', "%{$query}%")
                ->orWhere('updated_at', 'LIKE', "%{$query}%");
        })
            ->when($stockStatus, function ($queryBuilder) use ($stockStatus) {
                // Apply stock status filtering
                if ($stockStatus === 'Safe') {
                    $queryBuilder->where('product_quantity', '>=', 26);
                } elseif ($stockStatus === 'Low') {
                    $queryBuilder->whereBetween('product_quantity', [11, 25]);
                } elseif ($stockStatus === 'Critical') {
                    $queryBuilder->where('product_quantity', '<=', 10);
                }
            })
            ->get();

        return Inertia::render('products/productIndex', [
            'products' => $products,
            'searchQuery' => $query,
            'stockStatus' => $stockStatus
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'nullable|string|max:255',
            'product_price' => 'nullable|numeric',
            'product_description' => 'nullable|string',
            'product_quantity' => 'nullable|numeric',
        ]);


        Products::create([
            'product_name' => $validated['product_name'],
            'product_price' => $validated['product_price'],
            'product_description' => $validated['product_description'],
            'product_quantity' => $validated['product_quantity'],
        ]);
        return redirect()->back()->with('success', 'Product added successfully!');
    }


    public function destroy($id)
    {
        $product = Products::find($id);
        if ($product) {
            $product->delete();
            return redirect()->back()->with('success', 'Product deleted successfully.');
        }
        return redirect()->back()->with('error', 'Product not found.');
    }



    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        $product->update($request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'product_price' => 'nullable|numeric',
            'product_quantity' => 'nullable|numeric',
        ]));

        return back()->with('success', 'Product updated successfully');
    }
}
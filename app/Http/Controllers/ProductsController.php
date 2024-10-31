<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Products; // Import the Products model

class ProductsController extends Controller
{
    // Method to show the index page with the product list
    public function index()
    {
        // Fetch all products from the database
        $products = Products::all();

        // Pass the products data to the productIndex Vue component
        return Inertia::render('products/productIndex', [
            'products' => $products
        ]);
    }


    public function dashboard()
    {
        // Fetch all products from the database
        $products = Products::all();

        // Pass the products data to the Dashboard Vue component
        return Inertia::render('Dashboard', [
            'products' => $products
        ]);
    }

    // Method to store a new product
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'product_name' => 'nullable|string|max:255',
            'product_price' => 'nullable|numeric',
            'product_description' => 'nullable|string',
            'product_quantity' => 'nullable|numeric',

        ]);

        // Create a new product using the validated data
        Products::create([
            'product_name' => $validated['product_name'],
            'product_price' => $validated['product_price'],
            'product_description' => $validated['product_description'],
            'product_quantity' => $validated['product_quantity'],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Product added successfully!');
    }

    // Method to delete a product
    public function destroy($id)
    {
        $product = Products::find($id);
        if ($product) {
            $product->delete();
            return redirect()->back()->with('success', 'Product deleted successfully.');
        }
        return redirect()->back()->with('error', 'Product not found.');
    }

    // Method to update a product
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
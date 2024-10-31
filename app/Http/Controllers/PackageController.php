<?php

namespace App\Http\Controllers;

use App\Models\PackageModel;
use App\Models\Products;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends Controller
{
    // Display all packages
    public function index()
    {
        $packages = PackageModel::with('products')->get(); // Eager load products related to packages
        $products = Products::all(); // Fetch all available products

        return Inertia::render('package/index', [
            'package' => $packages,
            'products' => $products // Pass products to the frontend
        ]);
    }

    // Store a new package along with its associated products and quantities
    public function store(Request $request)
    {
        // Validate the package data and the selected products with quantities
        $validated = $request->validate([
            'package_name' => 'nullable|string|max:255',
            'package_description' => 'nullable|string',
            'package_price' => 'nullable|numeric',
            'products' => 'required|array', // Products array is required
            'products.*.product_id' => 'required|exists:product_inventory,product_inventory_id', // Ensure product exists
            'products.*.quantity' => 'required|integer|min:1', // Quantity for each product
        ]);

        // Create the package
        $package = PackageModel::create([
            'package_name' => $validated['package_name'],
            'package_description' => $validated['package_description'],
            'package_price' => $validated['package_price']
        ]);

        // Loop through each product, attach it to the package, and update the product's inventory
        foreach ($validated['products'] as $productData) {
            $product = Products::find($productData['product_id']);

            // Check if there's enough quantity in stock
            if ($product->product_quantity >= $productData['quantity']) {
                // Attach the product to the package along with the quantity
                $package->products()->attach($product->product_inventory_id, ['quantity' => $productData['quantity']]);

                // Reduce the product's quantity from the inventory
                $product->decrement('product_quantity', $productData['quantity']);
            } else {
                // If the inventory is insufficient, throw an error
                return back()->withErrors(['product' => "Not enough inventory for product: {$product->product_name}"]);
            }
        }

        return redirect()->back()->with('success', 'Package created successfully!');
    }

    // Delete a package and its relationships
    public function destroy($id)
    {
        $package = PackageModel::find($id);
        if ($package) {
            $package->products()->detach(); // Detach all related products from the package
            $package->delete(); // Delete the package itself
            return redirect()->back()->with('success', 'Package deleted successfully!');
        }
        return redirect()->back()->with('error', 'Package not found!');
    }

    // Update an existing package
    public function update(Request $request, $id)
    {
        $package = PackageModel::findOrFail($id);

        // Validate the package data
        $validated = $request->validate([
            'package_name' => 'required|string|max:225',
            'package_description' => 'nullable|string',
            'package_price' => 'nullable|numeric',
            'products' => 'required|array', // Products array is required
            'products.*.product_id' => 'required|exists:product_inventory,product_inventory_id', // Ensure product exists
            'products.*.quantity' => 'required|integer|min:1', // Quantity for each product
        ]);

        // Update package information
        $package->update([
            'package_name' => $validated['package_name'],
            'package_description' => $validated['package_description'],
            'package_price' => $validated['package_price']
        ]);

        // Detach all current products
        $package->products()->detach();

        // Re-attach products with new quantities
        foreach ($validated['products'] as $productData) {
            $product = Products::find($productData['product_id']);

            // Check if there's enough quantity in stock
            if ($product->product_quantity >= $productData['quantity']) {
                // Attach the product to the package with the quantity
                $package->products()->attach($product->product_inventory_id, ['quantity' => $productData['quantity']]);

                // Reduce the product's quantity from the inventory
                $product->decrement('product_quantity', $productData['quantity']);
            } else {
                // If the inventory is insufficient, throw an error
                return back()->withErrors(['product' => "Not enough inventory for product: {$product->product_name}"]);
            }
        }

        return back()->with('success', 'Package updated successfully!');
    }
}
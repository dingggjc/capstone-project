<?php

namespace App\Http\Controllers;

use App\Models\PackageModel;
use App\Models\Products;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends Controller
{

    public function index(Request $request)
    {
        $query = $request->input('query');

        $packages = PackageModel::with('products')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('package_name', 'LIKE', "%{$query}%")
                    ->orWhere('package_description', 'LIKE', "%{$query}%")
                    ->orWhere('package_price', 'LIKE', "%{$query}%")
                    ->orWhere('created_at', 'LIKE', "%{$query}%")
                    ->orWhereHas('products', function ($productQuery) use ($query) {
                        $productQuery->where('product_name', 'LIKE', "%{$query}%")
                            ->orWhere('quantity', 'LIKE', "%{$query}%");
                    });
            })
            ->get();

        $products = Products::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('product_name', 'LIKE', "%{$query}%")
                ->orWhere('product_description', 'LIKE', "%{$query}%");
        })
            ->get();

        return Inertia::render('package/index', [
            'package' => $packages,
            'products' => $products,
            'searchQuery' => $query
        ]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_name' => 'nullable|string|max:255',
            'package_description' => 'nullable|string',
            'package_price' => 'nullable|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:product_inventory,product_inventory_id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $package = PackageModel::create([
            'package_name' => $validated['package_name'],
            'package_description' => $validated['package_description'],
            'package_price' => $validated['package_price']
        ]);

        foreach ($validated['products'] as $productData) {
            $product = Products::find($productData['product_id']);
            if ($product->product_quantity >= $productData['quantity']) {
                $package->products()->attach($product->product_inventory_id, ['quantity' => $productData['quantity']]);
            } else {
                return back()->withErrors(['product' => "Not enough inventory for product: {$product->product_name}"]);
            }
        }

        return redirect()->back()->with('success', 'Package created successfully!');
    }




    public function destroy($id)
    {
        $package = PackageModel::find($id);
        if ($package) {
            $package->products()->detach();
            $package->delete();
            return redirect()->back()->with('success', 'Package deleted successfully!');
        }
        return redirect()->back()->with('error', 'Package not found!');
    }

    public function update(Request $request, $id)
    {
        $package = PackageModel::findOrFail($id);

        $validated = $request->validate([
            'package_name' => 'required|string|max:225',
            'package_description' => 'nullable|string',
            'package_price' => 'nullable|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:product_inventory,product_inventory_id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $package->update([
            'package_name' => $validated['package_name'],
            'package_description' => $validated['package_description'],
            'package_price' => $validated['package_price']
        ]);

        $package->products()->detach();

        foreach ($validated['products'] as $productData) {
            $product = Products::find($productData['product_id']);
            if ($product->product_quantity >= $productData['quantity']) {
                $package->products()->attach($product->product_inventory_id, ['quantity' => $productData['quantity']]);
            } else {
                return back()->withErrors(['product' => "Not enough inventory for product: {$product->product_name}"]);
            }
        }

        return back()->with('success', 'Package updated successfully!');
    }
}
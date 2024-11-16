<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Models\specialsModel;
use App\Models\Products;

class specialsController extends Controller
{
    public function index()
    {
        $specials = specialsModel::with(['category', 'products'])->get();
        $products = Products::all();
        $categories = CategoryModel::all();

        return Inertia::render('specials/index', [
            'specials' => $specials,
            'categories' => $categories,
            'products' => $products
        ]);
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:product_inventory,product_inventory_id',
            'products.*.quantity' => 'required|integer|min:1',
            'price' => 'required|string|max:255',
        ]);

        $specials = specialsModel::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
        ]);

        foreach ($validated['products'] as $productData) {
            $product = Products::find($productData['product_id']);
            if ($product->product_quantity >= $productData['quantity']) {
                $specials->products()->attach($product->product_inventory_id, ['quantity' => $productData['quantity']]);
            } else {
                return back()->withErrors(['product' => "Not enough inventory for product: {$product->product_name}"]);
            }
        }

        return redirect()->back()->with('success', 'Special added successfully!');
    }

    public function destroy($id)
    {
        $specials = specialsModel::find($id);
        if ($specials) {
            $specials->delete();
            return redirect()->back()->with('success', 'Special deleted successfully!');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:product_inventory,product_inventory_id',
            'products.*.quantity' => 'required|integer|min:1',
            'price' => 'required|nullable|max:255',
        ]);
        $specials = specialsModel::find($id);
        if ($specials) {
            $specials->products()->detach();

            foreach ($validated['products'] as $productData) {
                $product = Products::find($productData['product_id']);
                if ($product->product_quantity >= $productData['quantity']) {
                    $specials->products()->attach($product->product_inventory_id, ['quantity' => $productData['quantity']]);
                } else {
                    return back()->withErrors(['product' => "Not enough inventory for product: {$product->product_name}"]);
                }
            }

            $specials->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'price' => $validated['price'],
            ]);
            return redirect()->back()->with('success', 'Special updated successfully!');
        }
    }
}

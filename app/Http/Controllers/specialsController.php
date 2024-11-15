<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Models\specialsModel;

class specialsController extends Controller
{
    public function index()
    {
        $specials = specialsModel::with('category')->get();

        $categories = CategoryModel::all();
        return Inertia::render('specials/index', [
            'specials' => $specials,
            'categories' => $categories,
        ]);
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|string|max:255',
        ]);

        specialsModel::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
        ]);

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
            'price' => 'required|nullable|max:255',
        ]);

        $specials = specialsModel::find($id);
        if ($specials) {
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
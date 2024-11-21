<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = CategoryModel::with('examples')->get();

        return Inertia::render('category/index', [
            'category' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'required|string',
            'examples' => 'required|array',
            'examples.*' => 'required|string',
        ]);


        $category = CategoryModel::create([
            'category_name' => $validated['category_name'],
            'category_description' => $validated['category_description'],
        ]);


        foreach ($validated['examples'] as $example) {
            $category->examples()->create([
                'example_name' => $example,
            ]);
        }

        return redirect()->back()->with('success', 'Category added successfully!');
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'required|string',
            'examples' => 'required|array',
            'examples.*' => 'required|string',
        ]);


        $category = CategoryModel::findOrFail($id);
        $category->update([
            'category_name' => $validated['category_name'],
            'category_description' => $validated['category_description'],
        ]);


        $category->examples()->delete();

        foreach ($validated['examples'] as $example) {
            $category->examples()->create([
                'example_name' => $example,
            ]);
        }

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = CategoryModel::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}
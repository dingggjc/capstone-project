<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $category = CategoryModel::all();


        return Inertia::render('category/index', [
            'category' => $category,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'required|string',
            'category_example' => 'required|string',
        ]);
        CategoryModel::create([
            'category_name' => $validated['category_name'],
            'category_description' => $validated['category_description'],
            'category_example' => $validated['category_example'],
        ]);
        return redirect()->back()->with('success', 'Category added successfully!');
    }

    public function destroy($id)
    {
        $category = CategoryModel::find($id);
        if ($category) {
            $category->delete();
            return redirect()->back()->with('success', 'Category deleted successfully!');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'required|string',
            'category_example' => 'required|string',
        ]);

        $category = CategoryModel::find($id);
        if ($category) {
            $category->update([
                'category_name' => $validated['category_name'],
                'category_description' => $validated['category_description'],
                'category_example' => $validated['category_example'],
            ]);
            return redirect()->back()->with('success', 'Category updated successfully!');
        }
    }
}
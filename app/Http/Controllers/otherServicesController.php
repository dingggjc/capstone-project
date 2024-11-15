<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\CategoryModel;
use App\Models\otherServicesModel;
use Illuminate\Http\Request;

class otherServicesController extends Controller
{
    public function index()
    {
        $others =  otherServicesModel::with('category')->get();
        $categories = CategoryModel::all();
        return Inertia::render('others/index', [
            'others' => $others,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'others_name' => 'required|string|max:255',
            'others_description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'others_price' => 'required|string|max:255',
        ]);

        otherServicesModel::create([
            'others_name' => $validated['others_name'],
            'others_description' => $validated['others_description'],
            'category_id' => $validated['category_id'],
            'others_price' => $validated['others_price'],
        ]);

        return redirect()->back()->with('success', 'Other service added successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'others_name' => 'required|string|max:255',
            'others_description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'others_price' => 'required|nullable|max:255',
        ]);

        $others = otherServicesModel::find($id);
        if ($others) {
            $others->update([
                'others_name' => $validated['others_name'],
                'others_description' => $validated['others_description'],
                'category_id' => $validated['category_id'],
                'others_price' => $validated['others_price'],
            ]);
            return redirect()->back()->with('success', 'Other service updated successfully!');
        }
    }

    public function destroy($id)
    {
        $others = otherServicesModel::find($id);
        if ($others) {
            $others->delete();
            return redirect()->back()->with('success', 'Other service deleted successfully!');
        }
    }
}
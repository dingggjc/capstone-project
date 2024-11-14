<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\specialsModel;

class specialsController extends Controller
{
    public function index()
    {
        $specials = specialsModel::all();
        return Inertia::render('specials/index', [
            'specials' => $specials
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|string|max:255',
        ]);

        specialsModel::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
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
            'price' => 'required|nullable|max:255',
        ]);

        $specials = specialsModel::find($id);
        if ($specials) {
            $specials->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
            ]);
            return redirect()->back()->with('success', 'Special updated successfully!');
        }
    }
}

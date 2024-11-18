<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\StaffModel;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = StaffModel::all();
        return Inertia::render('Staff/index', [
            'staff' => $staff
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_name' => 'required|string|max:255',
            'staff_phone' => 'required|string|max:255',
            'staff_status' => 'required|string|max:255|in:Active,Inactive',
        ]);

        StaffModel::create([
            'staff_name' => $validated['staff_name'],
            'staff_phone' => $validated['staff_phone'],
            'staff_status' => $validated['staff_status'],
        ]);

        return redirect()->back()->with('success', 'Staff added successfully!');
    }

    public function destroy($id)
    {
        $staff = StaffModel::find($id);
        if ($staff) {
            $staff->delete();
            return redirect()->back()->with('success', 'Staff deleted successfully!');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'staff_name' => 'nullable|string|max:255',
            'staff_phone' => 'nullable|string|max:255',
            'staff_status' => 'nullable|string|max:255',
        ]);

        $staff = StaffModel::find($id);
        if ($staff) {
            $staff->update([
                'staff_name' => $validated['staff_name'],
                'staff_phone' => $validated['staff_phone'],
                'staff_status' => $validated['staff_status'],
            ]);
            return redirect()->back()->with('success', 'Staff updated successfully!');
        }
    }
}
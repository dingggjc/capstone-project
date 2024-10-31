<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\CustomerModel;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index()
    {

        $customer = CustomerModel::all();

        return Inertia::render('customer/index', [
            'customer' => $customer
        ]);
    }

    public function dashboard()
    {

        $customer = CustomerModel::all();

        return Inertia::render('Dashboard', [
            'customer' => $customer
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cus_name' => 'nullable|string|max:255',
            'vehicle_name' => 'nullable|string|max:255',
            'vehicle_plate' => 'nullable|string|max:255',
            'cus_address' => 'nullable|string',
            'cus_phone' => 'nullable|string',
            'cus_amount' => 'nullable|numeric',
        ]);

        CustomerModel::create([
            'cus_name' => $validated['cus_fname'],
            'vehicle_name' => $validated['vehicle_name'],
            'vehicle_plate' => $validated['vehicle_plate'],
            'cus_address' => $validated['cus_address'],
            'cus_phone' => $validated['cus_phone'],
            'cus_amount' => $validated['cus_amount'],
        ]);

        return redirect()->back()->with('success', 'Customer added successfully!');
    }

    public function destroy($id)
    {
        $customer = CustomerModel::find($id);
        if ($customer) {
            $customer->delete();
            return redirect()->back()->with('success', 'Customer deleted successfully!');
        }
        return redirect()->back()->with('error', 'Customer not found!');
    }

    public function update(Request $request, $id)
    {
        $customer = CustomerModel::find($id);
        $customer->update($request->validate([
            'cus_name' => 'nullable|string|max:255',
            'vehicle_name' => 'nullable|string|max:255',
            'vehicle_plate' => 'nullable|string|max:255',
            'cus_address' => 'nullable|string',
            'cus_phone' => 'nullable|string',
            'cus_amount' => 'nullable|numeric',
        ]));
        return redirect()->back()->with('success', 'Customer updated successfully!');
    }
}
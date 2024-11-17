<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\TemporaryCustomerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        return Inertia::render('payment/index');
    }

    public function saveCustomerDetails(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'vehicle_plate' => 'required|string|max:10',
        ]);


        $customerDetails = TemporaryCustomerDetail::updateOrCreate(
            ['cashier_id' => Auth::id()],
            $validated
        );


        return redirect()->back()->with([
            'success' => 'Customer details saved successfully!',
            'customerDetails' => $customerDetails
        ]);
    }
}
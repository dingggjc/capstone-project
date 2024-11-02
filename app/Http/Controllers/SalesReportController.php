<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {

        $query = $request->input('query');


        $queryDate = null;
        if (strtotime($query)) {
            $queryDate = Carbon::parse($query)->format('Y-m-d');
        }


        $transactions = Transactions::with(['cashier', 'details.product', 'details.package'])
            ->when($query, function ($q) use ($query, $queryDate) {
                $q->where('invoice', 'LIKE', "%{$query}%")
                    ->orWhereHas('cashier', function ($cashierQuery) use ($query) {
                        $cashierQuery->where('name', 'LIKE', "%{$query}%");
                    })
                    ->orWhere('customer_name', 'LIKE', "%{$query}%")
                    ->orWhere('grand_total', 'LIKE', "%{$query}%");


                if ($queryDate) {
                    $q->orWhereDate('created_at', $queryDate);
                }
            })
            ->latest()
            ->get();

        return Inertia::render('SalesReport/index', [
            'transactions' => $transactions,
            'searchQuery' => $query,
        ]);
    }
}
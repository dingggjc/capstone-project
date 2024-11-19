<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Transactions;

class TransactionStatusController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');


        $transactions = Transactions::with(['cashier', 'details.product', 'details.package'])
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('cashier', function ($cashierQuery) use ($query) {
                    $cashierQuery->where('name', 'LIKE', "%{$query}%");
                })
                    ->orWhere('customer_name', 'LIKE', "%{$query}%")
                    ->orWhere('invoice', 'LIKE', "%{$query}%")
                    ->orWhere('created_at', 'LIKE', "%{$query}%")
                    ->orWhereHas('details.product', function ($productQuery) use ($query) {
                        $productQuery->where('product_name', 'LIKE', "%{$query}%");
                    })
                    ->orWhereHas('details.package', function ($packageQuery) use ($query) {
                        $packageQuery->where('package_name', 'LIKE', "%{$query}%");
                    });
            })
            ->get();


        return Inertia::render('TransactionStatus/index', [
            'transactions' => $transactions,
            'searchQuery' => $query,
        ]);
    }
}
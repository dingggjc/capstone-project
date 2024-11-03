<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionReportController extends Controller
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

        return Inertia::render('TransactionReport/index', [
            'transactions' => $transactions,
            'searchQuery' => $query,
        ]);
    }

    public function destroy($id)
    {
        $transaction = Transactions::find($id);
        if ($transaction) {
            $transaction->details()->delete();
            $transaction->delete();
            return redirect()->back()->with('success', 'Transaction deleted successfully!');
        }
        return redirect()->back()->with('error', 'Transaction not found!');
    }
}
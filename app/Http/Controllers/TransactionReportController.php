<?php

namespace App\Http\Controllers;

use App\Models\Transactions;

class TransactionReportController extends Controller
{
    public function index()
    {
        $transactions = Transactions::with('cashier')->get(); // Load the cashier relationship

        return inertia('TransactionReport/index', [
            'transactions' => $transactions,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Transactions;

class TransactionReportController extends Controller
{
    public function index()
    {

        $transactions = Transactions::with([
            'cashier',
            'details.product',
            'details.package'
        ])->get();

        return inertia('TransactionReport/index', [
            'transactions' => $transactions,
        ]);
    }
}
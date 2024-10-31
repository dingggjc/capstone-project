<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Transactions;

class SalesReportController extends Controller
{
    public function index()
    {
        $transactions = Transactions::with(['cashier', 'details.product', 'details.package'])
            ->latest()
            ->get();

        return Inertia::render('SalesReport/index', [
            'transactions' => $transactions,
        ]);
    }
}

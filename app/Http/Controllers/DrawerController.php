<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;

class DrawerController extends Controller
{
    public function getTransactions(Request $request)
    {
        $query = $request->query('search');
        $transactions = Transactions::query()
            ->when($query, function ($q) use ($query) {
                $q->where('vehicle_plate', 'like', "%{$query}%");
            })
            ->with(['details'])
            ->get();

        return response()->json($transactions);
    }
}
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Products;
use App\Models\PackageModel;
use App\Models\Transactions;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $products = Products::all();
        $packages = PackageModel::all();
        $todaySales =  (int) Transactions::whereDate('created_at', Carbon::today())->sum('grand_total');


        $totalProductStock = $products->sum('product_quantity');

        $transactionsCount = Transactions::whereDate('created_at', Carbon::today())->count();

        $topSellingProducts = TransactionDetail::select(
            'product_inventory_id',
            DB::raw('SUM(qty) as total_qty'),
            DB::raw('SUM(qty * product_price) as total_sales')
        )
            ->with('product')
            ->whereNotNull('product_inventory_id')
            ->groupBy('product_inventory_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $topSellingPackages = TransactionDetail::select(
            'package_id',
            DB::raw('SUM(qty) as total_qty'),
            DB::raw('SUM(qty * package_price) as total_sales')
        )
            ->with('package')
            ->whereNotNull('package_id')
            ->groupBy('package_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'products' => $products,
            'packages' => $packages,
            'today_sales' => $todaySales,
            'transactions_count' => $transactionsCount,
            'total_product_stock' => $totalProductStock,
            'top_selling_products' => $topSellingProducts,
            'top_selling_packages' => $topSellingPackages, // Pass total stock to Vue component
        ]);
    }


    public function getTopPackages(Request $request)
    {
        $range = $request->query('range', 'today'); // Default to today
        $query = TransactionDetail::select(
            'package_id',
            DB::raw('SUM(qty) as total_qty'),
            DB::raw('SUM(qty * package_price) as total_sales')
        )
            ->with('package')
            ->whereNotNull('package_id');

        if ($range === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($range === 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($range === 'last7days') {
            $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
        }

        $topSellingPackages = $query->groupBy('package_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return response()->json($topSellingPackages);
    }
}
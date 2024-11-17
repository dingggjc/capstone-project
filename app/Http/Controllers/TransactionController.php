<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Products;
use App\Models\PackageModel;
use App\Models\CategoryModel;
use App\Models\specialsModel;
use App\Models\otherServicesModel;
use App\Models\StaffModel;
use Illuminate\Support\Str;
use App\Models\Transactions;
use App\Models\TemporaryCustomerDetail;


class TransactionController extends Controller
{

    public function index()
    {
        $products = Products::all();
        $packages = PackageModel::all();
        $category = CategoryModel::all();
        $specials = specialsModel::all();
        $others = otherServicesModel::all();
        $staffs = StaffModel::all();

        $carts = Cart::with('products', 'package')->where('cashier_id', Auth::user()->id)->latest()->get();



        if (Auth::user()->role === 'admin') {

            $transactions = Transactions::with('cashier')->latest()->get();
        } else {

            $transactions = Transactions::with(['cashier', 'details.product', 'details.package'])
                ->where('cashier_id', Auth::user()->id)
                ->latest()->get();
        }
        return Inertia::render('Transaction/Index', [
            'carts' => $carts,
            'carts_total' => $carts->sum('price'),
            'products' => $products,
            'packages' => $packages,
            'transactions' => $transactions,
            'category' => $category,
            'specials' => $specials,
            'others' => $others,
            'staffs' => $staffs,


        ]);
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



    public function addToCart(Request $request)
    {

        $product = $request->product_inventory_id ? Products::find($request->product_inventory_id) : null;
        $package = $request->package_id ? PackageModel::find($request->package_id) : null;
        $special = $request->specials_id ? specialsModel::find($request->specials_id) : null;
        $price = 0;


        if ($product) {
            if ($product->product_quantity < $request->qty) {
                return response()->json(['error' => 'Insufficient stock for the selected product.'], 400);
            }
            $price += $product->product_price * $request->qty;
        }

        if ($package) {
            foreach ($package->products as $packageProduct) {
                $productQtyInPackage = $packageProduct->pivot->quantity;
                $requiredQuantity = $productQtyInPackage;

                if ($packageProduct->product_quantity < $requiredQuantity) {
                    return response()->json([
                        'error' => "Insufficient stock for product: {$packageProduct->product_name} in the package."
                    ], 400);
                }
            }
            $price += $package->package_price;
        }

        if ($special) {
            foreach ($special->products as $specialProduct) {
                $requiredQuantity = $specialProduct->pivot->quantity;

                if ($specialProduct->product_quantity < $requiredQuantity) {
                    return response()->json([
                        'error' => "Insufficient stock for product: {$specialProduct->product_name} in the special."
                    ], 400);
                }
            }
            $price += $special->price;
        }


        Cart::create([

            'product_inventory_id' => $request->product_inventory_id,
            'package_id' => $request->package_id,
            'specials_id' => $request->specials_id,
            'cashier_id' => Auth::user()->id,
            'qty' => $request->qty,
            'price' => $price,
        ]);

        return back()->with(['success' => 'Items added to cart']);
    }
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:carts,id',
        ]);

        $cartItem = Cart::find($request->id);

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Item successfully removed from the cart!');
        }

        return redirect()->back()->with('error', 'Item could not be found!');
    }



    public function updateStatus(Request $request, $id)
    {
        $transaction = Transactions::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->back()->with('success', 'Transaction status updated successfully.');
    }
    public function destroyCart(Request $request)
    {
        $cart = Cart::find($request->cart_id);

        if ($cart) {
            $cart->delete();
            return redirect()->back()->with('success', 'Item removed successfully');
        }

        return redirect()->back()->with('error', 'Item not found');
    }

    public function store(Request $request)
    {
        $carts = Cart::where('cashier_id', Auth::user()->id)->get();
        $carts_total = 0;

        foreach ($carts as $cart) {
            if ($cart->product_inventory_id && $cart->products) {
                $carts_total += $cart->products->product_price * $cart->qty;
            }
            if ($cart->package_id && $cart->package) {
                $carts_total += $cart->package->package_price * $cart->qty;
            }
        }

        $status = $request->status ?? ($request->cash >= $carts_total ? 'Paid' : 'Pending');
        $invoice = 'GRNSDE-' . Str::upper(Str::random(10));

        $transaction = Transactions::create([
            'cashier_id' => Auth::user()->id,
            'invoice' => $invoice,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_plate' => $request->vehicle_plate,
            'cash' => $request->cash,
            'change' => $request->cash - $carts_total,
            'grand_total' => $carts_total,
            'status' => $status,
        ]);

        if ($status === 'Paid') {
            foreach ($carts as $cart) {
                if ($cart->product_inventory_id && $cart->products) {
                    $cart->products->decrement('product_quantity', $cart->qty);
                }

                if ($cart->package_id && $cart->package) {
                    foreach ($cart->package->products as $product) {
                        $productQtyInPackage = $product->pivot->quantity; // Quantity of the product in this package
                        $product->decrement('product_quantity', $cart->qty * $productQtyInPackage); // Adjust based on cart qty
                    }
                }

                $transaction->details()->create([
                    'transaction_id' => $transaction->id,
                    'product_inventory_id' => $cart->product_inventory_id,
                    'package_id' => $cart->package_id,
                    'qty' => $cart->qty,
                    'product_price' => $cart->product_inventory_id && $cart->products ? $cart->products->product_price : null,
                    'package_price' => $cart->package_id && $cart->package ? $cart->package->package_price : null,
                ]);

                $product_price = $cart->product_inventory_id && $cart->products ? $cart->products->product_price * $cart->qty : 0;
                $package_price = $cart->package_id && $cart->package ? $cart->package->package_price * $cart->qty : 0;
                $profit = $product_price + $package_price;

                $transaction->profits()->create([
                    'transaction_id' => $transaction->id,
                    'total' => $profit,
                ]);
            }
        }

        Cart::where('cashier_id', Auth::user()->id)->delete();

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }


    public function print(Request $request)
    {

        $transaction = Transactions::with([
            'cashier',
            'details.product',
            'details.package'
        ])->where('invoice', $request->invoice)->firstOrFail();


        return Inertia::render('Transaction/PrintNota', [
            'transaction' => $transaction,
        ]);
    }
}
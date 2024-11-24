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
use Infobip\Configuration;
use Infobip\ApiException;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Api\SmsApi;


class TransactionController extends Controller
{

    public function index()
    {
        $products = Products::all();
        $packages = PackageModel::all();
        $category = CategoryModel::with('examples')->get();
        $specials = specialsModel::all();
        $others = otherServicesModel::all();
        $staff = StaffModel::all();

        $customerDetails = TemporaryCustomerDetail::where('cashier_id', Auth::id())->first();

        $carts = Cart::with('products', 'package')->where('cashier_id', Auth::user()->id)->latest()->get();



        if (Auth::user()->role === 'admin') {

            $transactions = Transactions::with('cashier')->latest()->get();
        } else {

            $transactions = Transactions::with(['cashier', 'details.product', 'details.package'])
                ->where('cashier_id', Auth::user()->id)
                ->latest()->get();
        }
        return Inertia::render('Transaction/Index',  [
            'carts' => $carts,
            'carts_total' => $carts->sum('price'),
            'products' => $products,
            'packages' => $packages,
            'transactions' => $transactions,
            'category' => $category,
            'specials' => $specials,
            'others' => $others,
            'staff' => $staff,
            'customerDetails' => $customerDetails,


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
            'staff_id' => $request->staff_id,
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


    public function paymentIndex()
    {
        $products = Products::all();
        $packages = PackageModel::all();
        $carts = Cart::with('products', 'package')->where('cashier_id', Auth::user()->id)->latest()->get();
        $carts_total = $carts->sum('price');
        $transactions = Transactions::with(['cashier', 'details.product', 'details.package'])
            ->where('cashier_id', Auth::user()->id)
            ->latest()
            ->get();
        $category = CategoryModel::all();
        $specials = specialsModel::all();
        $others = otherServicesModel::all();
        $customerDetails = TemporaryCustomerDetail::where('cashier_id', Auth::id())->first();
        $staff = StaffModel::all();

        return Inertia::render('payment/index', [
            'products' => $products,
            'packages' => $packages,
            'carts_total' => $carts_total,
            'carts' => $carts,
            'transactions' => $transactions,
            'category' => $category,
            'specials' => $specials,
            'others' => $others,
            'customerDetails' => $customerDetails,
            'staff' => $staff,
        ]);
    }



    public function saveCustomerDetails(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'vehicle_plate' => 'required|string|max:10',
        ]);

        $phone = $validated['phone'];
        if (substr($phone, 0, 1) === '0') {
            $phone = '+63' . substr($phone, 1);
        }
        $validated['phone'] = $phone;

        $customerDetails = TemporaryCustomerDetail::updateOrCreate(
            ['cashier_id' => Auth::id()],
            $validated
        );
        return redirect()->back()->with([
            'success' => 'Customer details saved successfully!',
            'customerDetails' => $customerDetails
        ]);
    }


    public function saveStaffDetails(Request $request)
    {
        $validated = $request->validate([
            'staff_name' => 'required|string|max:255',
            'staff_phone' => 'required|string|max:15',
            'staff_status' => 'required|string|max:255',
        ]);
        $staffDetails = StaffModel::updateOrCreate(
            ['cashier_id' => Auth::id()],
            $validated
        );
        return redirect()->back()->with([
            'success' => 'Staff details saved successfully!',
            'staffDetails' => $staffDetails
        ]);
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
        $customerDetails = TemporaryCustomerDetail::where('cashier_id', Auth::id())->first();
        if (!$customerDetails) {
            return response()->json(['error' => 'Customer details are missing!'], 400);
        }

        $carts = Cart::with(['products', 'package.products', 'specials.products'])
            ->where('cashier_id', Auth::id())
            ->get();

        if ($carts->isEmpty()) {
            return response()->json(['error' => 'Cart is empty!'], 400);
        }

        $carts_total = $carts->reduce(function ($total, $cart) {
            if ($cart->product_inventory_id && $cart->products) {
                $total += $cart->products->product_price * $cart->qty;
            }
            if ($cart->package_id && $cart->package) {
                $total += $cart->package->package_price * $cart->qty;
            }
            if ($cart->specials_id && $cart->specials) {
                $total += $cart->specials->price * $cart->qty;
            }
            return $total;
        }, 0);


        $status = 'Ongoing';
        $invoice = 'GRNSDE-' . Str::upper(Str::random(10));

        $transaction = Transactions::create([
            'cashier_id' => Auth::id(),
            'invoice' => $invoice,
            'customer_name' => $customerDetails->name,
            'customer_phone' => $customerDetails->phone,
            'vehicle_plate' => $customerDetails->vehicle_plate,
            'cash' => $request->cash,
            'change' => max($request->cash - $carts_total, 0),
            'grand_total' => $carts_total,
            'status' => $status,
        ]);

        foreach ($carts as $cart) {
            if ($cart->product_inventory_id && $cart->products) {
                $cart->products->decrement('product_quantity', $cart->qty);
            }

            if ($cart->package_id && $cart->package) {
                foreach ($cart->package->products as $product) {
                    $productQtyInPackage = $product->pivot->quantity;
                    $product->decrement('product_quantity', $cart->qty * $productQtyInPackage);
                }
            }

            if ($cart->specials_id && $cart->specials) {
                foreach ($cart->specials->products as $product) {
                    $productQtyInPackage = $product->pivot->quantity;
                    $product->decrement('product_quantity', $cart->qty * $productQtyInPackage);
                }
            }

            $transaction->details()->create([
                'transaction_id' => $transaction->id,
                'product_inventory_id' => $cart->product_inventory_id,
                'specials_id' => $cart->specials_id,
                'package_id' => $cart->package_id,
                'staff_id' => $cart->staff_id,
                'qty' => $cart->qty,
                'product_price' => $cart->product_inventory_id && $cart->products ? $cart->products->product_price : null,
                'package_price' => $cart->package_id && $cart->package ? $cart->package->package_price : null,
                'price' => $cart->specials_id && $cart->specials ? $cart->specials->price : null,
            ]);
        }


        Cart::where('cashier_id', Auth::id())->delete();

        TemporaryCustomerDetail::where('cashier_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction completed successfully!',
            'transaction_id' => $transaction->id,
            'invoice' => $transaction->invoice,
        ]);
    }


    public function sendSMS($id)
    {
        $transaction = Transactions::findOrFail($id);

        if (!$transaction->customer_phone) {
            return response()->json(['message' => 'Phone number not available for this transaction.'], 400);
        }

        // Infobip SMS Setup
        $configuration = new Configuration(
            host: env('INFOBIP_HOST'),
            apiKey: env('INFOBIP_API_KEY')
        );

        $smsApi = new SmsApi(config: $configuration);
        $customerName = $transaction->customer_name;
        $messageText = "Hey $customerName! Your car is ready for pickup. It’s just been washed and looks great! Stop by anytime to get it. See you soon!";
        $message = new SmsTextualMessage(
            destinations: [
                new SmsDestination(to: $transaction->customer_phone)
            ],
            from: 'YourService',
            text: $messageText
        );

        $smsRequest = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $smsApi->sendSmsMessage($smsRequest);
            return response()->json(['message' => 'SMS sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send SMS. ' . $e->getMessage()], 500);
        }
    }


    public function print(Request $request)
    {

        $transaction = Transactions::with([
            'cashier',
            'details.product',
            'details.package',
            'details.specials'
        ])->where('invoice', $request->invoice)->firstOrFail();


        return Inertia::render('Transaction/PrintNota', [
            'transaction' => $transaction,
        ]);
    }
}

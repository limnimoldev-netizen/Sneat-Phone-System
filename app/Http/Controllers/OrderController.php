<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class OrderController extends Controller
{
  function __construct()
  {
      $this->middleware('auth');
      $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index', 'showSale']]);
      $this->middleware('permission:order-create', ['only' => ['createOrder', 'createSale', 'store']]);
      $this->middleware('permission:order-delete', ['only' => ['destroy']]);
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $parameterNames = [];
    $query = Order::query();
    if ($request->search) {
        $filters = $request->only(['customer', 'from_date', 'to_date']);
        

        if (!empty($filters['customer'])) {
            $query->where('customer_id', $filters['customer']);
            $parameterNames['customer'] = $filters['customer'];
        }

        if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
            // Both from_date and to_date are provided
            $query->whereBetween('order_date', [$filters['from_date'], $filters['to_date']]);
            $parameterNames['from_date'] = $filters['from_date'];
            $parameterNames['to_date'] = $filters['to_date'];
        } elseif (!empty($filters['from_date'])) {
            // Only from_date is provided
            $query->where('order_date', '>=', $filters['from_date']);
            $parameterNames['from_date'] = $filters['from_date'];
        } elseif (!empty($filters['to_date'])) {
            // Only to_date is provided
            $query->where('order_date', '<=', $filters['to_date']);
            $parameterNames['to_date'] = $filters['to_date'];
        }
    }

    $orders = $query->with(['customer', 'employee'])->orderBy('order_date', 'desc')->paginate(20);
    session(['printInvoiceId' => null]);

    // code add
    $customers = Customer::pluck('name', 'id');
    
    return view('orders.index', compact(
      'orders',
      'customers',
      'parameterNames'
    ));
  }


   // add function by nimol add sale
    public function createOrder(Request $request)
    {
        $query = Product::available()->whereNotNull('selling_price');

        // If a brand button is clicked, filter products
        if ($request->has('brand') && $request->brand != 'all') {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->brand . '%');
            });
        }
        $products  = $query->get();
        $customers = Customer::all();

        return view('orders.createOrder', compact('products', 'customers'));
    }
    
    //add by  nimol

    public function createSale(Request $request)
    {

    $query = Product::available()->whereNotNull('selling_price');

        // If a brand button is clicked, filter products
        if ($request->has('brand') && $request->brand != 'all') {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->brand . '%');
            });
        }
        $products  = $query->get();
        $customers = Customer::all();

        return view('orders.create', compact('products', 'customers'));
    }

    //add by  nimol
    // Save the order to database
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'integer', Rule::exists('customers', 'id')->whereNull('deleted_at')],
            'order_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'products' => ['required', 'json'],
            'payment_type' => ['nullable', Rule::in([Order::PAYMENT_TYPE_CASH, Order::PAYMENT_TYPE_BANK, Order::PAYMENT_TYPE_OTHER])],
            'payment_status' => ['nullable', Rule::in([Order::PAYMENT_STATUS_PAID, Order::PAYMENT_STATUS_UNPAID])],
            'status' => ['nullable', Rule::in([Order::STATUS_ACTIVE, Order::STATUS_INACTIVE])],
        ]);
        $items = Validator::make(['products' => json_decode($data['products'], true)], [
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'min:1', 'distinct'],
        ])->validate()['products'];

        $employee = $request->user()->employee;
        $customerId = $data['customer_id'] ?? Customer::where('name', 'Walk in Customer')
            ->where('phone', '000000000')->value('id');

        if (!$employee || !$customerId) {
            throw ValidationException::withMessages([
                'customer_id' => !$employee ? 'Your account needs an employee profile to record a sale.' : 'Select a customer.',
            ]);
        }

        DB::transaction(function () use ($data, $items, $employee, $customerId): void {
            $products = Product::available()->whereKey(array_column($items, 'id'))
                ->orderBy('id')->lockForUpdate()->get();

            if ($products->count() !== count($items) || $products->contains(
                fn (Product $product) => !is_numeric($product->selling_price) || $product->selling_price < 0
            )) {
                throw ValidationException::withMessages(['products' => 'Select available products with a valid selling price.']);
            }

            $order = Order::create([
                'customer_id' => $customerId,
                'employee_id' => $employee->id,
                'total_amount' => number_format($products->sum(
                    fn (Product $product) => (int) round($product->selling_price * 100)
                ) / 100, 2, '.', ''),
                'order_date' => Carbon::parse($data['order_date'])->toDateTimeString(),
                'note' => $data['note'] ?? null,
                'payment_status' => $data['payment_status'] ?? Order::PAYMENT_STATUS_PAID,
                'payment_type' => $data['payment_type'] ?? Order::PAYMENT_TYPE_CASH,
                'status' => $data['status'] ?? Order::STATUS_ACTIVE,
            ]);

            foreach ($products as $product) {
                $order->orderDetails()->create([
                    'product_id' => $product->id,
                    'unit_price' => number_format((float) $product->selling_price, 2, '.', ''),
                ]);
                $product->update(['status' => Product::STATUS_ID_SOLD]);
            }
        }, 3);

        return redirect()->route('sales.index', withLang())->with('success', 'Order saved!');
    }

    // add by nimol
    public function showSale($lang, $id)
    {
        $sale = Order::with(['orderDetails.product', 'customer', 'employee'])->findOrFail($id);
        return view('orders.showSale', compact('id', 'sale'));
    }
    

     /**
     * Display the specified resource.
     */
    public function show(string $lang, Order $order)
    {
        $order = $order->with('orderDetails', 'customer', 'employee')->findOrfail($order->id);
        $order_detals = OrderDetail::where('order_id', $order->id)->with('product')->get();
        return view('orders.show', compact('order', 'order_detals'));
    }


    /**
     * * Display the specified resource.
     * */
    public function checkProductOrder(Request $request)
    {
        // Attach order details to the order
        foreach ($request->productIds as $key => $productId) {

            // Check if the product is available
            $product = Product::available()->find($productId);
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }
        }
        return response()->json(['message' => 'Submiting Order'], 201);
    }

    public function destroy(string $lang, string $id)
    {
        DB::transaction(function () use ($id): void {
            $order = Order::lockForUpdate()->findOrFail($id);
            $products = Product::sold()->whereKey($order->orderDetails()->pluck('product_id')->all())
                ->orderBy('id')->lockForUpdate()->get();
            $order->delete();

            foreach ($products as $product) {
                if (!OrderDetail::where('product_id', $product->id)->whereHas('order')->exists()) {
                    $product->update(['status' => Product::STATUS_ID_AVAILABLE]);
                }
            }
        }, 3);

      return redirect()->route('sales.index', withLang())->with('success', 'Sale deleted successfully');
    }

     /**
     * Display the specified resource.
     */
    public function invoice(string $lang, Order $order)
    {
        $order = $order->with('orderDetails', 'customer', 'employee')->findOrfail($order->id);
        $order_detals = OrderDetail::where('order_id', $order->id)->with('product')->get();
        return view('orders.invoice', compact('order', 'order_detals'));
    }

    public function invoicePdf(Request $request, string $lang, Order $order)
    {
      $currentDate = Carbon::now()->format('Y-m-d');
      $order = $order->with('orderDetails', 'customer', 'employee')->findOrfail($order->id);
      $order_detals = OrderDetail::where('order_id', $order->id)->with('product')->get();
      $file_pdf = 'invoice-'.str_pad($order->id, 5, '0', STR_PAD_LEFT).'.pdf';
      $type = $request->type ?? 'download';
      return view('orders.invoice-pdf', compact('order', 'order_detals', 'currentDate' ,'file_pdf', 'type'));
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Series;
use App\Models\Color;
use App\Models\ModelType;
use App\Models\Storage;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
  function __construct()
  {
      $this->middleware('auth');
      $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index','store']]);
      $this->middleware('permission:order-create', ['only' => ['create','store']]);
      $this->middleware('permission:order-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:order-delete', ['only' => ['destroy']]);
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    // code add
    $query = Order::query();  

    $parameterNames = [];
    $query = Order::query();
        $customers = Customer::all();
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

    $orders = $query->orderBy('order_date', 'desc')->paginate(20);
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
    public function createOrder()
    {
        $products  = Product::all();
        $customers = Customer::all();

        return view('orders.createOrder', compact('products', 'customers'));
    }
    
    //add by  nimol

    public function createSale()
    {
        $products  = Product::all();
        $customers = Customer::all();

        return view('orders.create', compact('products', 'customers'));
    }

    //add by  nimol
    // Save the order to database
    public function store(Request $request)
    {
        $customer_id  = $request->input('customer_id');
        $order_date   = $request->input('order_date');
        $note         = $request->input('note');
        $products     = json_decode($request->input('products'), true); // comes from hidden input
        $total        = collect($products)->sum('price');

       
        $order = Order::create([
            'customer_id'    => $request->input('customer_id') ?: 1, 
            'employee_id'    => auth()->id(),
            'total_amount'   => $total,
            'order_date'     => $request->input('order_date'),
            'note'           => $request->input('note'),
            'payment_status' => $request->input('payment_status') ?: 0, 
            'payment_type' => $request->input('payment_type') ?: 1,
            'status'         => $request->input('status') ?: 0,
        ]);

        // Save each product as order item
        foreach ($products as $item) {
            OrderDetail::create([
                'order_id'   => $order->id,
                'product_id' => $item['id'],
                'unit_price' => $item['price'] ?? 0,
                'price'      => $item['price'],
            ]);
        }

        return redirect()->route('sales.index', app()->getLocale())->with('success', 'Order saved!');


    }

    // add by nimol
    public function showSale($lang, $id)
    {
        $sale = Order::findOrFail($id);
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

    public function destroy(string $lang, Order $order)
    {
        $orderDetial = OrderDetail::where('order_id', $order->id)->get();

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

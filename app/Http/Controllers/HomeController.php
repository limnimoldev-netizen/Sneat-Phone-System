<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Loan;
use App\Models\LoanPayment;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:report-list', ['only' => ['index','report']]);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function __invoke(Request $request)
    {
        $locale = $request->cookie('locale', app()->getLocale());

        return redirect()->route('dashboard', in_array($locale, ['kh', 'en'], true) ? $locale : 'en');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
      $validated = $request->validate(['year' => ['nullable', 'integer', 'between:1900,9999']]);
      $currentYear = (int) ($validated['year'] ?? now()->year);
      $yearExpression = DB::getDriverName() === 'sqlite'
          ? "CAST(strftime('%Y', order_date) AS INTEGER)"
          : 'YEAR(order_date)';

      $years = DB::table('orders')
            ->select(DB::raw("$yearExpression as order_year"))
            ->distinct()
            ->orderBy('order_year', 'desc')
            ->get();
      $totalExpense = Expense::whereYear('date', $currentYear)->sum('amount');
      $totalOrderIncome = Order::whereYear('order_date', $currentYear)->whereNull('deleted_at')->sum('total_amount');
      $totalLoanPaymentIncome = LoanPayment::whereYear('date', $currentYear)->whereHas('loan', function ($query) {
        $query->whereNotNull('loan_id')->where('loans.status', 2);
      })->sum('amount');

      $totalIncome = $totalOrderIncome + $totalLoanPaymentIncome;
      $totalProfit = $totalIncome - $totalExpense;
      $getRevenue = $this->setTotalRevenue($currentYear);

      if ($getRevenue['monthlyDifferenceLastYear']['total'] != 0) {
        $percentageChange = (($getRevenue['monthlyDifference']['total'] - $getRevenue['monthlyDifferenceLastYear']['total']) / $getRevenue['monthlyDifferenceLastYear']['total']) * 100;
      } else {$percentageChange = 0;
      }
      $orders = Order::limit(5)->orderBy('order_date', 'desc')->get();
      $loanPayments = collect();
      $loans = collect();
      $lateLoans = Loan::has('customer')->with('customer')->latePayment()->limit(5)->orderBy('next_payment_date', 'desc')->get();

      return view('home', [
        'totalExpense' => $totalExpense,
        'totalIncome' => $totalIncome,
        'totalLoanPaymentIncome' => $totalLoanPaymentIncome,
        'totalProfit' => $totalProfit,
        'monthlyDifferenceLastYear' => $getRevenue['monthlyDifferenceLastYear'],
        'monthlyDifference' => $getRevenue['monthlyDifference'],
        'percentageChange' => $percentageChange,
        'currentYear' => $currentYear,
        'years' => $years,
        'orders' => $orders,
        'loanPayments' => $loanPayments,
        'lateLoans' => $lateLoans,
        'loans' => $loans
        ]);
    }

    public function setTotalRevenue(int $currentYear): array
    {
        return [
            'monthlyDifference' => $this->annualRevenue($currentYear),
            'monthlyDifferenceLastYear' => $this->annualRevenue($currentYear - 1),
        ];
    }

    private function annualRevenue(int $year): array
    {
        $sqlite = DB::getDriverName() === 'sqlite';
        $orderMonth = $sqlite ? "CAST(strftime('%m', order_date) AS INTEGER)" : 'MONTH(order_date)';
        $paymentMonth = $sqlite ? "CAST(strftime('%m', date) AS INTEGER)" : 'MONTH(date)';

        $orders = Order::whereYear('order_date', $year)
            ->selectRaw("$orderMonth as month, SUM(total_amount) as total")
            ->groupByRaw($orderMonth)
            ->pluck('total', 'month');
        $payments = DB::table('loan_payments')->whereYear('date', $year)
            ->selectRaw("$paymentMonth as month, SUM(amount) as total")
            ->groupByRaw($paymentMonth)
            ->pluck('total', 'month');

        $monthlyRevenue = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyRevenue[] = round(($orders[$month] ?? 0) + ($payments[$month] ?? 0), 2);
        }

        return ['name' => $year, 'data' => $monthlyRevenue, 'total' => array_sum($monthlyRevenue)];
    }
}

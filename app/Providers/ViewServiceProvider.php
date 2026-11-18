<?php

namespace App\Providers;

use App\Models\CompanySetting;
use App\Models\Loan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as Template;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $company = null;
        View::composer([
            'components.sidebar', 'loans.create', 'loans.invoice', 'loans.invoice-pdf',
            'loans.payments.pdf', 'loans.payments.invoice', 'loans.payments.invoice-pdf',
            'reports.list-loan', 'reports.*-pdf',
        ], function (Template $view) use (&$company): void {
            $view->with('company', $company ??= CompanySetting::first() ?? new CompanySetting(['interest' => 3]));
        });

        View::composer('components.navbar', function (Template $view): void {
            $query = Loan::approved()->has('customer')->where('remain', '>', 0)
                ->where('next_payment_date', '<', now()->subDays(7)->toDateString());

            $view->with([
                'countOverdueLoans' => $query->count(),
                'overdueLoans' => $query->with('customer')->orderBy('next_payment_date')->limit(5)->get(),
            ]);
        });
    }
}

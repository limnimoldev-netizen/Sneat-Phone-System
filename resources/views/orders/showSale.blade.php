@extends('layouts.app')

@push('styles')
<style>
    .invoice-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .invoice-table th {
        background-color: #f8f9fa !important;
        color: #566a7f !important;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .invoice-table td {
        color: #697a8d;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card invoice-card p-4">

        <div class="text-center mb-4">
            <h4 class="fw-bold text-black mb-1">CMy Phone Shop</h4>
            <span class="text-muted small">លក់ទូរស័ព្ទ iPhone មានគុណភាពល្អ</span>
        </div>
        {{-- Header Section --}}
        <div class="row align-items-center mb-4">
            <div class="col-sm-7">
                <p class="mb-1 text-muted">
                    <i class="bx bx-phone me-1"></i> 011 699 952
                </p>
                <p class="mb-0 text-muted">
                    <i class="bx bx-map me-1"></i>
                    #44 ផ្លូវជាតិលេខ៥ សង្កាត់ទួលសង្កែ ខណ្ឌឬស្សីកែវ
                </p>
            </div>


            <div class="col-sm-5 text-md-end">
                <p class="mb-1">
                    <span class="text-muted">Invoice:</span> 
                    <strong class="text-dark">#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</strong>
                </p>
                <p class="mb-1">
                    <span class="text-muted">Issued Date:</span> 
                    <strong class="text-dark">{{ $sale->created_at->format('d/m/Y') }}</strong>
                </p>
                <p class="mb-0">
                    <span class="text-muted">Order Date:</span> 
                    <strong class="text-dark">{{ $sale->created_at->format('d/m/Y') }}</strong>
                </p>
            </div>
        </div>

        <hr class="my-4">

        {{-- Customer Section --}}
        <div class="row mb-5">
            <div class="col-12">
                <h6 class="text-muted text-uppercase fw-semibold mb-2">Customer Info</h6>
                <h5 class="fw-bold mb-1 text-dark">
                    {{ $sale->customer->name ?? 'Walk in Customer' }}
                </h5>
                <p class="text-muted mb-0">
                    <i class="bx bx-phone-call me-1 small"></i>{{ $sale->customer->phone ?? '0000000000' }}
                </p>
            </div>
        </div>

        {{-- Invoice Title --}}
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark tracking-wider">INVOICE</h3>
        </div>

        {{-- Product Table --}}
        <div class="table-responsive border rounded mb-4">
            <table class="table invoice-table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase py-3">Items</th>
                        <th class="text-uppercase py-3">Description</th>
                        <th class="text-uppercase py-3 text-end">Cost</th>
                        <th class="text-uppercase py-3 text-center">Qty</th>
                        <th class="text-uppercase py-3 text-end">Price</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sale->orderDetails as $detail)
                        <tr>
                            <td class="fw-semibold text-dark py-3">
                                {{ $detail->product->name }}
                            </td>
                            <td>
                                <span class="badge bg-label-secondary font-monospace">
                                    IMEI: {{ $detail->imei ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-end">
                                ${{ number_format($detail->price, 2) }}
                            </td>
                            <td class="text-center fw-semibold">
                                {{ $detail->quantity }}
                            </td>
                            <td class="text-end fw-bold text-dark">
                                ${{ number_format($detail->price * $detail->quantity, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Seller & Total Summary --}}
        <div class="row justify-content-between align-items-center mt-2 py-2">
            <div class="col-sm-6 mb-3 mb-sm-0 text-center text-sm-start">
                <div class="border-top d-inline-block pt-2" style="width: 150px;">
                    <p class="text-muted small mb-0">Authorized Seller</p>
                    <strong class="text-dark">The Seller</strong>
                </div>
            </div>

            <div class="col-sm-5 col-md-4 text-sm-end">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span class="fw-semibold text-dark">${{ number_format($sale->total_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="text-dark fw-bold mb-0">Total:</h5>
                    <h4 class="text-primary fw-bold mb-0">${{ number_format($sale->total_amount, 2) }}</h4>
                </div>
            </div>
        </div>

        <hr class="my-4">

        {{-- Terms Note --}}
        <div class="bg-light p-3 rounded border-start border-3 border-warning">
            <p class="mb-0 small text-muted">
                <strong class="text-dark"><i class="bx bx-info-circle me-1"></i>Note:</strong>
                សូមរក្សាទុកវិក្កយបត្រនេះសម្រាប់ការធានា និងការប្តូរទំនិញ។
            </p>
        </div>

    </div>
</div>
@endsection
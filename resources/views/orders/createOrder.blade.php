@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid container-p-y">

    <div class="row">

        <div class="col-md-2">
            <div class="card">
                <div class="card-body d-grid gap-2">
                    <button class="btn btn-primary">
                        <i class="bx bx-search"></i> Search
                    </button>
                    <button class="btn btn-primary">
                        <i class="bx bx-mobile"></i> All Phones
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="bx bxl-apple fs-3 d-block"></i> APPLE
                    </button>
                    <button class="btn btn-outline-secondary">SAMSUNG</button>
                    <button class="btn btn-outline-secondary">XIAOMI</button>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="row g-3">

                @if(count($products) > 0)
                    @foreach($products as $product)
                        <div class="col-md-4">
                            <div class="card h-100 product-card"
                                style="cursor:pointer;"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->product_name }}"
                                data-price="{{ $product->selling_price }}">

                                <img src="{{ $product->image ? asset($product->image) : asset('assets/img/blank-product.svg') }}"
                                    class="card-img-top"
                                    style="height:180px; object-fit:cover;"
                                    alt="{{ $product->product_name }}">

                                <div class="card-body">
                                    <h6 class="card-title">{{ $product->product_name }}</h6>
                                    <small class="text-muted d-block">IMEI: {{ $product->product_imei }}</small>
                                    <small class="text-muted d-block">
                                        {{ $product->brand->name ?? '' }} {{ $product->series->name ?? '' }}
                                    </small>
                                    <small class="text-muted d-block">
                                        {{ $product->modelType->name ?? '' }} • {{ $product->storage->name ?? '' }}
                                    </small>
                                    <small class="text-muted d-block">
                                        {{ $product->color->name ?? '' }} • {{ $product->condition->name ?? '' }}
                                    </small>
                                    <div class="mt-2 fw-bold text-primary">
                                        ${{ number_format($product->selling_price, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-warning">No products available.</div>
                    </div>
                @endif

            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-header">
                    <strong>Order : #00953</strong>
                </div>

                <div class="card-body">
                    <div id="orderItems">
                        <div class="text-center text-muted py-5" id="emptyCart">
                            <i class="bx bx-cart fs-1"></i>
                            <p class="mt-2 mb-0">No items selected</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong id="orderTotal">$0.00</strong>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-secondary" id="cancelOrder">Cancel</button>
                        <button class="btn btn-primary" id="submitOrder">
                            <i class="bx bx-receipt"></i> Submit Order
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    let cart = {};

    function renderCart() {
        let html = '', total = 0;
        
        $.each(cart, (id, item) => {
            let subtotal = item.price * item.qty;
            total += subtotal;
            html += `<div class="border-bottom pb-2 mb-2 d-flex justify-content-between align-items-center">
                <div><strong>${item.name}</strong><br><small class="text-muted">$${item.price.toFixed(2)} × ${item.qty}</small></div>
                <div class="text-end"><div class="fw-bold">$${subtotal.toFixed(2)}</div><button class="btn btn-sm btn-danger remove-item mt-1" data-id="${id}">Remove</button></div>
            </div>`;
        });

        $('#orderItems').html(html || '<div class="text-center text-muted py-5"><i class="bx bx-cart fs-1"></i><p class="mt-2 mb-0">No items selected</p></div>');
        $('#orderTotal').text('$' + total.toFixed(2));
    }

    // Combine Add & Remove into unified event handling
    $(document).on('click', '.product-card, .remove-item', function (e) {
        e.preventDefault();
        let el = $(this), id = el.data('id');
        if (!id) return;

        if (el.hasClass('product-card')) {
            cart[id] = cart[id] ? { ...cart[id], qty: cart[id].qty + 1 } : { id, name: el.data('name'), price: parseFloat(el.data('price')), qty: 1 };
        } else {
            delete cart[id];
        }
        renderCart();
    });

    // Cancel Order
    $('#cancelOrder').on('click', () => { if (confirm('Clear current order?')) { cart = {}; renderCart(); } });

    // Submit Order
    $('#submitOrder').on('click', function () {
        if ($.isEmptyObject(cart)) return alert('Please select products.');
        
        let btn = $(this).prop('disabled', true).text('Processing...');
    });
});
</script>
@endpush
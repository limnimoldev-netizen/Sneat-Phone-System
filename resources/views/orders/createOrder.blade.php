@extends('layouts.app')

@section('content')

<style>
    /* Hide Sneat layout elements */
    .layout-menu, 
    .layout-navbar, 
    footer.content-footer {
        display: none !important;
    }
    /* Reset screen margins so the POS container fits perfectly */
    .layout-page {
        padding-left: 0 !important;
        padding-top: 0 !important;
    }
    .content-wrapper {
        padding: 20px !important;
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        transition: all 0.2s ease-in-out;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<form id="posOrderForm" action="{{ route('sales.store', app()->getLocale()) }}" method="POST">
    @csrf
    
    <input type="hidden" name="products" id="hiddenProductsInput">
    <input type="hidden" name="order_date" value="{{ date('Y-m-d H:i:s') }}">

    <div class="container-fluid py-2">
        <div class="row g-3">

            <div class="col-md-1">
                <div class="d-flex flex-column gap-2 text-center">
                    <button type="button" class="btn btn-primary w-100 p-2 d-flex flex-column align-items-center justify-content-center" style="min-height: 65px; border-radius: 6px;">
                        <i class="bx bx-search fs-3 mb-1"></i>
                        <span style="font-size: 11px; font-weight: 500;">Search</span>
                    </button>
                    
                    <button type="button" class="btn btn-primary w-100 p-2 d-flex flex-column align-items-center justify-content-center btn-filter" data-brand="all" style="min-height: 65px; border-radius: 6px;">
                        <i class="bx bx-mobile fs-3 mb-1"></i>
                        <span style="font-size: 11px; font-weight: 500;">All Phones</span>
                    </button>
                    
                    <button type="button" class="btn btn-outline-secondary bg-white text-dark w-100 p-2 d-flex flex-column align-items-center justify-content-center btn-filter" data-brand="apple" style="min-height: 65px; border-radius: 6px; border: 1px solid #dee2e6;">
                        <i class="bx bxl-apple fs-2 mb-1 text-dark"></i>
                        <span style="font-size: 11px; font-weight: bold; color: #566a7f;">APPLE</span>
                    </button>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row g-1">
                    @if(count($products) > 0)
                        @foreach($products as $product)
                            <div class="col-md-3"> 
                                <div class="product-card gap-4"
                                    style="cursor:pointer; border-radius: 6px; background: #fff;"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->product_name }}"
                                    data-price="{{ $product->selling_price }}">

                                    <img src="{{ $product->image ? asset('images/product/' . $product->image) : asset('assets/img/blank-product.svg') }}"
                                        class="card-img-top"
                                        style="height:190px; object-fit:contain;"
                                        alt="{{ $product->product_name }}">

                                    <div class="card-body p-2 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="mb-0 text-dark" style="font-size: 13px; font-weight: 600; line-height: 1.3;">
                                                {{ $product->product_name }} <span class="text-muted" style="font-weight: 400;">[ IMEI: {{ $product->product_imei }} ]</span>
                                            </h6>
                                            <small class="text-muted d-block mt-1" style="font-size: 11px; line-height: 1.2;">
                                                {{ $product->condition->name ?? 'Used' }}, 
                                                {{ $product->brand->name ?? '' }} {{ $product->series->name ?? '' }}, 
                                                {{ $product->storage->name ?? '' }}, 
                                                {{ $product->color->name ?? '' }}, Original
                                            </small>
                                        </div>
                                        <div class="mt-2 fw-bold text-dark" style="font-size: 14px;">
                                            ${{ number_format($product->selling_price, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-warning border-0">No phone products match this category selection brand.</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm h-100 d-flex flex-column" style="border-radius: 6px; background: #fff; min-height: 85vh;">
                    
                    <div class="p-2 border-bottom">
                        <small class="text-muted d-block mb-1 fw-semibold" style="font-size: 11px;">Order : #00953</small>
                        <div class="d-flex align-items-center bg-light px-2 py-1" style="border-radius: 6px;">
                            <i class="bx bx-user fs-4 text-secondary me-2"></i>
                            <select class="form-select border-0 bg-transparent text-dark fw-semibold p-0 shadow-none" name="customer_id" id="customer_id" style="font-size: 13px; cursor: pointer;">
                                @if(isset($customers) && count($customers) > 0)
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                @else
                                    <option value="1">General Customer</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="card-body p-2 flex-grow-1" style="overflow-y: auto; max-height: 50vh;">
                        <div id="orderItems">
                            <div class="text-center text-muted py-5" id="emptyCart">
                                <i class="bx bx-cart fs-1"></i>
                                <p class="mt-2 mb-0" style="font-size: 13px;">No items selected</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-2 border-top mt-auto bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                            <span class="text-secondary fw-medium" style="font-size: 14px;">Total</span>
                            <strong class="text-dark fs-4" style="font-weight: 700;">$ <span id="orderTotal">0.00</span></strong>
                        </div>

                        <div class="row g-2">
                            <div class="col-3">
                                <button type="button" class="btn btn-light w-100 p-2 d-flex flex-column align-items-center justify-content-center border-0" id="cancelOrder" style="min-height: 46px; border-radius: 6px; background-color: #e9ecef;">
                                    <i class="bx bx-receipt fs-4 text-secondary"></i>
                                    <span style="font-size: 9px; font-weight: 600; color: #6c757d;">Bill</span>
                                </button>
                            </div>
                            <div class="col-9">
                                <button type="submit" class="btn btn-primary w-100 p-2 d-flex align-items-center justify-content-center gap-2 fw-bold" id="submitOrder" style="min-height: 46px; background-color: #1a68ff; border: none; border-radius: 6px; font-size: 14px;">
                                    <i class="bx bx-calculator fs-4"></i> Submit Order
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</form>
@endsection

<script>

// Wait until the whole HTML page is loaded
document.addEventListener('DOMContentLoaded', function () {

    // Create an empty cart array to store selected products
    let cart = [];

    // FUNCTION: Update Cart UI
    function updateCart() {

        // Get the container where selected products will be displayed
        const container = document.getElementById('orderItems');

        // Check if cart is empty
        if (cart.length === 0) {

            // Show empty cart message
            container.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="bx bx-cart fs-1"></i>
                    <p class="mt-2 mb-0">No items selected</p>
                </div>
            `;

            // Set total price to 0
            document.getElementById('orderTotal').textContent = '0.00';

            // Send empty array to Laravel
            document.getElementById('hiddenProductsInput').value = '[]';

            // Stop function here
            return;
        }

        // Variable to calculate total price
        let total = 0;

        // Loop through all items in cart
        container.innerHTML = cart.map((item, index) => {

            // Add current item price to total
            total += parseFloat(item.price);

            // Return HTML for each cart item
            return `
                <div class="d-flex align-items-center justify-content-between mb-2 p-2 border rounded bg-light">

                    <div style="max-width: 80%;">
                        <h6 class="mb-0 text-dark fw-semibold" style="font-size: 12px;">
                            ${item.name}
                        </h6>

                        <small class="text-primary fw-bold">
                            $${parseFloat(item.price).toFixed(2)}
                        </small>
                    </div>

                    <!-- Remove button -->
                    <button
                        type="button"
                        class="btn text-danger btn-remove p-0 border-0 bg-transparent"
                        data-index="${index}">

                        <i class="bx bx-trash fs-5"></i>
                    </button>

                </div>
            `;
        }).join('');

        // Show total amount
        document.getElementById('orderTotal').textContent = total.toFixed(2);

        // Convert cart array to JSON and send to Laravel
        document.getElementById('hiddenProductsInput').value = JSON.stringify(cart);
    }

    // FUNCTION: Add Product

    // Find all product cards
    document.querySelectorAll('.product-card').forEach(card => {

        // When user clicks a product
        card.onclick = function () {

            // Get product ID from HTML data-id
            const id = this.dataset.id;

            // Check if product already exists in cart
            if (cart.some(item => item.id === id)) {

                // Show warning
                alert('Already added!');

                // Stop code
                return;
            }

            // Add new product into cart
            cart.push({
                id: id,
                name: this.dataset.name,
                price: this.dataset.price
            });

            // Refresh cart UI
            updateCart();
        };
    });


    // FUNCTION: Remove Product
    document.getElementById('orderItems').onclick = function (e) {

        // Check if remove button was clicked
        const btn = e.target.closest('.btn-remove');

        if (btn) {

            // Remove item by index
            cart.splice(btn.dataset.index, 1);

            // Refresh cart
            updateCart();
        }
    };


    // FUNCTION: Clear Order
    document.getElementById('cancelOrder').onclick = function () {

        // Check if cart has products
        if (cart.length > 0 && confirm('Clear order?')) {

            // Empty the cart
            cart = [];

            // Refresh UI
            updateCart();
        }
    };


    // FUNCTION: Filter Brand
    document.querySelectorAll('.btn-filter').forEach(btn => {

        // When user clicks a filter button
        btn.onclick = function () {

            // Get current URL
            const url = new URL(window.location.href);

            // If brand = all
            if (this.dataset.brand === 'all') {

                // Remove brand parameter
                url.searchParams.delete('brand');

            } else {

                // Add brand parameter
                url.searchParams.set('brand', this.dataset.brand);
            }

            // Reload page with new URL
            window.location.href = url;
        };
    });


    // FUNCTION: Prevent Empty Submit
    document.getElementById('posOrderForm').onsubmit = function (e) {

        // Check if cart is empty
        if (cart.length === 0) {

            // Stop form submission
            e.preventDefault();

            // Show error message
            alert('Cart is empty!');
        }
    };

});
</script>
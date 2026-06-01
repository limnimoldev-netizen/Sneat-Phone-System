@extends('layouts.app')

@push('styles')
<style>
    .product-detail-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 0.375rem;
    }
    .info-label {
        color: #a1acb8;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    .info-value {
        color: #566a7f;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title mb-0 text-primary">Product Information</h4>
        </div>
        
        <div class="card-body pt-4">
            <div class="row mb-4">
                <div class="col-12">
                    <img class="product-detail-img img-thumbnail" 
                         src="{{ $product->image_name }}" 
                         alt="Product Image" 
                         onError="this.onerror=null;this.src='{{ asset('/assets/img/blank-product.svg') }}';">
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.name') }} :</span>
                        <span class="info-value">{{ $product->product_name ?? 'XXX' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">Product Code :</span>
                        <span class="info-value">{{ $product->product_code ?? '—' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.brand') }} :</span>
                        <span class="info-value text-uppercase">{{ $product->brand->name ?? 'APPLE' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.color') }} :</span>
                        <span class="info-value">{{ $product->color->name ?? 'Gold' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label ">{{ __('product.storage') }} :</span>
                        <span class="info-value">{{ $product->storage->name ?? '512GB' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.battery_percentage') }} :</span>
                        <span class="info-value">{{ $product->battery_percentage ?? '76' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">Purchase Date :</span>
                        <span class="info-value">{{ $product->purchase_date ?? '2026-05-24' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">Selling Price :</span>
                        <span class="info-value text-danger">{{ isset($product->selling_price) ? setToStringDolla($product->selling_price) : 'XXX' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">Product Note :</span>
                        <span class="info-value">{{ $product->product_note ?? 'Display genuine' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.imei') }} :</span>
                        <span class="info-value">{{ $product->product_imei ?? 'XXX' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.condition.title') }} :</span>
                        <span class="info-value">{!! $product->condition_label_badges_name ?? 'Used' !!}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.series') }} :</span>
                        <span class="info-value">{{ $product->series->name ?? 'iPhone 13 Pro' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.model') }} :</span>
                        <span class="info-value">{{ $product->modelType->name ?? 'LLA' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.machine') }} / Type of Machine :</span>
                        <span class="info-value">{{ $product->network->name ?? 'Original' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.percentage') }} :</span>
                        <span class="info-value">{{ $product->percentage ?? 'B' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">Purchase Price :</span>
                        <span class="info-value">{{ isset($product->purchase_price) ? setToStringDolla($product->purchase_price) : 'XXX' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="info-label">{{ __('product.status') }} :</span>
                        <span class="info-value">{!! $product->status_badges_name ?? 'Instock' !!}</span>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <a href="{{ route('products.index', withLang()) }}" class="btn btn-outline-secondary">
                    Product Lists
                </a>
                @can('product-edit')
                    <a href="{{ route('products.edit', withLang(['product' => $product->id])) }}" class="btn btn-primary">
                        Edit
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
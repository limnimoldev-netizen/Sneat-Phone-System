@extends('layouts.app')
@push('styles')
@endpush

@section('content')

<div class="content-wrapper">
     
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
              <form id="formProductRegister" method="POST" action="{{ route('products.store', withLang()) }}" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4">
                    <h5 class="card-header">Register Product</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        

                                        <img src="{{ $company->image_logo ?? 'img.png' }}" alt="Product" class="rounded" width="100" height="100" style="object-fit: cover;">
                                        
                                    </div>
                                    <div>
                                        <input type="file" id="image" name="image" class="form-control d-none" accept="image/*">
                                        <button type="button" class="btn btn-primary btn-base" onclick="document.getElementById('image').click()">Upload new photo</button>
                                        <button type="button" class="btn btn-outline-secondary btn-base">Reset</button>
                                        <p class="text-muted mb-0 mt-2">Allowed JPG, GIF or PNG.</p>
                                    </div>
                                </div>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="product_name">Product Name</label>
                                <input class="form-control @error('product_name') is-invalid @enderror" type="text" value="{{ old('product_name') }}" id="product_name" name="product_name" >
                                @error('product_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="product_imei">Product imei</label>
                                <input class="form-control @error('product_imei') is-invalid @enderror" type="text" value="{{ old('product_imei') }}" id="product_imei" name="product_imei" >
                                @error('product_imei')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="product_code">Product Code</label>
                                <input class="form-control @error('product_code') is-invalid @enderror" type="text" value="{{ old('product_code') }}" id="product_code" name="product_code" >
                                @error('product_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="condition">Condition</label>
                                <select class="form-select @error('condition') is-invalid @enderror" name="condition" id="condition">
                                    <option value="used" @if(old('condition') == 'used') selected @endif>Used</option>
                                    <option value="new" @if(old('condition') == 'new') selected @endif>New</option>
                                </select>
                                @error('condition')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="brand">Brand</label>
                                <select class="form-select" name="brand_id" id="brand_id">
                                    <option value="" disabled selected>Select Brand</option>

                                    <option value="1" @if(old('brand_id') == 1) selected @endif>Apple</option>
                                    <option value="2" @if(old('brand_id') == 2) selected @endif>Samsung</option>
                                    <option value="3" @if(old('brand_id') == 3) selected @endif>Huawei</option>
                                    <option value="4" @if(old('brand_id') == 4) selected @endif>Xiaomi</option>
                                    <option value="5" @if(old('brand_id') == 5) selected @endif>Oppo</option>
                                    <option value="6" @if(old('brand_id') == 6) selected @endif>Vivo</option>
                                    <option value="7" @if(old('brand_id') == 7) selected @endif>Other</option>
                                </select>
                                @error('brand')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="series">Series</label>
                                <select class="form-select @error('series_id') is-invalid @enderror" name="series_id" id="series_id">
                                    <option value="" disabled selected>Select Series</option>
                                    <option value="1" @if(old('series_id') == 1) selected @endif>iPhone</option>
                                    <option value="2" @if(old('series_id') == 2) selected @endif>Galaxy</option>
                                    <option value="3" @if(old('series_id') == 3) selected @endif>P Series</option>
                                </select>
                                @error('series')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="model_type">Model</label>
                                <input class="form-control @error('model_type') is-invalid @enderror" type="text" value="{{ old('model_type') }}" id="model_type" name="model_type" placeholder="Enter model">
                                @error('model_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="color">Color</label>
                                <select class="form-select @error('color_id') is-invalid @enderror" name="color_id" id="color_id">
                                    <option value="" disabled selected>Select Color</option>
                                    <option value="1" @if(old('color_id') == 1) selected @endif>Black</option>
                                    <option value="2" @if(old('color_id') == 2) selected @endif>White</option>
                                    <option value="3" @if(old('color_id') == 3) selected @endif>Blue</option>
                                    <option value="4" @if(old('color_id') == 4) selected @endif>Red</option>
                                </select>
                                @error('color')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="storage">Storage</label>
                                <select class="form-select @error('storage_id') is-invalid @enderror" name="storage_id" id="storage_id">
                                    <option value="" disabled selected>Select Storage</option>
                                    <option value="1" @if(old('storage_id') == 1) selected @endif>32GB</option>
                                    <option value="2" @if(old('storage_id') == 2) selected @endif>64GB</option>
                                    <option value="3" @if(old('storage_id') == 3) selected @endif>128GB</option>
                                    <option value="4" @if(old('storage_id') == 4) selected @endif>256GB</option>
                                    <option value="5" @if(old('storage_id') == 5) selected @endif>512GB</option>
                                </select>
                                @error('storage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="type_of_machine">Type of Machine</label>
                                <select class="form-select @error('type_of_machine') is-invalid @enderror" name="type_of_machine" id="type_of_machine">
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="smartphone" @if(old('type_of_machine') == 'smartphone') selected @endif>Smartphone</option>
                                    <option value="tablet" @if(old('type_of_machine') == 'tablet') selected @endif>Tablet</option>
                                    <option value="laptop" @if(old('type_of_machine') == 'laptop') selected @endif>Laptop</option>
                                    <option value="watch" @if(old('type_of_machine') == 'watch') selected @endif>Smart Watch</option>
                                </select>
                                @error('type_of_machine')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="network">Lock By</label>
                                <select class="form-select @error('network') is-invalid @enderror" name="network" id="network">
                                    <option value="" disabled selected>Select Lock Status</option>
                                    <option value="unlocked" @if(old('network') == 'unlocked') selected @endif>Unlocked</option>
                                    <option value="carrier" @if(old('network') == 'carrier') selected @endif>Carrier Locked</option>
                                    <option value="icloud" @if(old('network') == 'icloud') selected @endif>iCloud Locked</option>
                                    <option value="google" @if(old('network') == 'google') selected @endif>Google Locked</option>
                                </select>
                                @error('network')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="battery_percentage">Battery Percentage</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control @error('battery_percentage') is-invalid @enderror" id="battery_percentage" name="battery_percentage" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                    @error('battery_percentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="percentage">Product Percentage</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control @error('percentage') is-invalid @enderror"  id="percentage" name="percentage" >
                                    <span class="input-group-text">%</span>
                                    @error('percentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="purchase_price">Purchase Price</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control @error('purchase_price') is-invalid @enderror"  id="purchase_price" name="purchase_price" step="0.01" min="0">
                                    <span class="input-group-text">$</span>
                                    @error('purchase_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="purchase_date">Purchase Date</label>
                                <input class="form-control @error('purchase_date') is-invalid @enderror" type="date" value="{{ old('purchase_date') }}" id="purchase_date" name="purchase_date">
                                @error('purchase_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="selling_price">Selling Price</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" step="0.01" min="0">
                                    <span class="input-group-text">$</span>
                                    @error('selling_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="status">Product Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="1" @if(old('status') == 1) selected @endif>Available</option>
                                    <option value="2" @if(old('status') == 2) selected @endif>Sold</option>
                                    <option value="3" @if(old('status') == 3) selected @endif>Broken</option>
                                    <option value="4" @if(old('status') == 4) selected @endif>Loan</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Save</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>

            </div>
        </div>
    </div>
    <!-- / Content -->
</div>
<!-- Content wrapper -->
@endsection
@push('script')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.js"></script>
<style>
    .select2{
        width: 100% !important;
        padding: .4375rem .875rem;
        font-size: 0.9375rem;
        font-weight: 400;
        line-height: 1.53;
        color: #697a8d;
        appearance: none;
        background-color: #fff;
        background-clip: padding-box;
        border: var(--bs-border-width) solid #d9dee3;
        border-radius: var(--bs-border-radius);
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .select2-container--default .select2-selection--single{
        border: 0px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        top: 8px;
    }
</style>
<script>
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
@endpush
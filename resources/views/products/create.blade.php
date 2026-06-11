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
                            
                            <div class="mb-3 col-md-12">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        <img id="previewImage"
                                            src="{{ asset('assets/img/blank-product.svg') }}"
                                            alt="Product"
                                            class="rounded"
                                            width="100"
                                            height="100"
                                            style="object-fit: cover; border: 1px solid #d9dee3;">
                                    </div>
                                    <div>
                                        <input type="file" id="image" name="image" class="form-control d-none" accept="image/png, image/jpeg, image/gif">
                                        <button type="button" class="btn btn-primary btn-base" onclick="document.getElementById('image').click()">Upload new photo</button>
                                        <button type="button" id="resetImageBtn" class="btn btn-outline-secondary btn-base">Reset</button>
                                        <p class="text-muted mb-0 mt-2">Allowed JPG, GIF or PNG.</p>
                                    </div>
                                </div>
                                @error('image')
                                    <span class="invalid-feedback" role="alert" style="display: block;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row">

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="product_name">Product Name</label>
                                    <input class="form-control @error('product_name') is-invalid @enderror" type="text" value="{{ old('product_name') }}" id="product_name" name="product_name" required>
                                    @error('product_name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="product_imei">Product IMEI</label>
                                    <input class="form-control @error('product_imei') is-invalid @enderror" type="text" value="{{ old('product_imei') }}" id="product_imei" name="product_imei" required>
                                    @error('product_imei')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="product_code">Product Code</label>
                                    <input class="form-control @error('product_code') is-invalid @enderror" type="text" value="{{ old('product_code') }}" id="product_code" name="product_code" required>
                                    @error('product_code')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="condition">Condition</label>
                                    <select class="form-select @error('condition') is-invalid @enderror" name="condition" id="condition" required>
                                        <option value="">Select Condition</option>
                                        @foreach ($conditions as $id => $name)
                                            <option value="{{ $id }}" {{ old('condition') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('condition')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="brand_id">Brand</label>
                                    <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id" id="brand_id" required>
                                        <option value="">Select an option</option>
                                        @foreach($brands as $id => $name)
                                            <option value="{{ $id }}" {{ old('brand_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="series_id">Series</label>
                                    <select class="form-select @error('series_id') is-invalid @enderror" name="series_id" id="series_id" required>
                                        <option value="">Select an option</option>
                                        @foreach($series as $id => $name)
                                            <option value="{{ $id }}" {{ old('series_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('series_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="model_type_id">Model</label>
                                    <select class="form-select @error('model_type_id') is-invalid @enderror" name="model_type_id" id="model_type_id" required>
                                        <option value="">Select an option</option>
                                        @foreach($modelTypes as $id => $name)
                                            <option value="{{ $id }}" {{ old('model_type_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('model_type_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="color_id">Color</label>
                                    <select class="form-select @error('color_id') is-invalid @enderror" name="color_id" id="color_id" required>
                                        <option value="">Select an option</option>
                                        @foreach($colors as $id => $name)
                                            <option value="{{ $id }}" {{ old('color_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('color_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="storage_id">Storage</label>
                                    <select class="form-select @error('storage_id') is-invalid @enderror" name="storage_id" id="storage_id" required>
                                        <option value="">Select an option</option>
                                        @foreach($storage as $id => $name)
                                            <option value="{{ $id }}" {{ old('storage_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('storage_id')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <div class="row">
                                        {{-- Type of Machine --}}
                                        <div class="col-md-6">
                                            <label class="form-label" for="type_of_machine">Type of Machine</label>
                                            <select class="form-select @error('type_of_machine') is-invalid @enderror" name="type_of_machine" id="type_of_machine" required>
                                                <option value="">Select an option</option>
                                                @foreach($type_of_machines as $id => $name)
                                                    <option value="{{ $id }}" {{ old('type_of_machine') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('type_of_machine')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>

                                       
                                        <div class="col-md-6">
                                            <label class="form-label" for="network_id">Lock By</label>
                                            <select class="form-select @error('network_id') is-invalid @enderror" name="network_id" id="network_id" required>
                                                <option value="">Select an option</option>
                                                @foreach($networks as $id => $name)
                                                    <option value="{{ $id }}" {{ old('network_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('network_id')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="battery_percentage">Battery Percentage</label>
                                    <div class="input-group input-group-merge">
                                        <input  class="form-control @error('battery_percentage') is-invalid @enderror" id="battery_percentage" name="battery_percentage" value="{{ old('battery_percentage') }}" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('battery_percentage')
                                        <span class="invalid-feedback" role="alert" style="display: block;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="percentage">Product Percentage</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control @error('percentage') is-invalid @enderror" id="percentage" name="percentage" value="{{ old('percentage') }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('percentage')
                                        <span class="invalid-feedback" role="alert" style="display: block;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="purchase_price">Purchase Price</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required>
                                        <span class="input-group-text">$</span>
                                    </div>
                                    @error('purchase_price')
                                        <span class="invalid-feedback" role="alert" style="display: block;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="selling_price">Selling Price</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" value="{{ old('selling_price') }}">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    @error('selling_price')
                                        <span class="invalid-feedback" role="alert" style="display: block;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="purchase_date">Purchase Date</label>
                                    <input class="form-control @error('purchase_date') is-invalid @enderror" type="date" value="{{ old('purchase_date') }}" id="purchase_date" name="purchase_date" required>
                                    @error('purchase_date')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="status">Product Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status" id="status" required>
                                        <option value="">Select an option</option>
                                        @foreach($status as $id => $name)
                                            <option value="{{ $id }}" {{ old('status') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Save</button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // Live Image Preview Engine
    document.getElementById('image').addEventListener('change', function (event) {
        if(event.target.files.length > 0) {
            let reader = new FileReader();
            reader.onload = function () {
                document.getElementById('previewImage').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    });

    // Reset Image Action Button
    document.getElementById('resetImageBtn').addEventListener('click', function() {
        document.getElementById('image').value = "";
        document.getElementById('previewImage').src = "{{ asset('assets/img/blank-product.svg') }}";
    });
</script>
@endpush
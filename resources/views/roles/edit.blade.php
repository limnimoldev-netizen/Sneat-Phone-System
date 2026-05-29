@extends('layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="pull-right">
                <a class="btn btn-outline-secondary" href="{{ route('roles.index', withLang()) }}">
                    <i class='bx bx-arrow-back'></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Edit Role — <strong>{{ $role->name }}</strong></h5>
        <div class="card-body">
            {!! Form::model($role, [
                'method' => 'PUT',
                'route'  => ['roles.update', withLang(['id' => $role->id])]
            ]) !!}

                {{-- Role Name --}}
                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                    <div class="form-group">
                        <label class="form-label" for="name">Role Name <span class="text-danger">*</span></label>
                        {!! Form::text('name', null, [
                            'class'       => 'form-control ' . ($errors->has('name') ? 'is-invalid' : ''),
                            'id'          => 'name',
                            'placeholder' => 'Enter role name'
                        ]) !!}
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Permissions --}}
                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">Permission</label>
                        <br/>

                        @php
                            $grouped         = $permission->groupBy(function($item) {
                                return explode('-', $item->name)[0];
                            });
                            $rolePermissions = old('permission', $role->permissions->pluck('id')->toArray());
                        @endphp

                        @foreach($grouped as $parent => $children)
                            <div class="mb-2">

                                {{-- Parent Checkbox --}}
                                <input class="form-check-input parent-check"
                                       type="checkbox"
                                       id="parent-{{ $parent }}"
                                       data-group="{{ $parent }}">
                                <label class="form-check-label fw-bold text-capitalize"
                                       for="parent-{{ $parent }}">
                                    {{ $parent }}
                                </label>

                                {{-- Children Checkboxes --}}
                                <div class="ms-4 mt-1">
                                    @foreach($children as $value)
                                        <div class="form-check">
                                            <input class="form-check-input child-check @error('permission') is-invalid @enderror"
                                                   type="checkbox"
                                                   name="permission[]"
                                                   id="permission-{{ $value->id }}"
                                                   value="{{ $value->id }}"
                                                   data-group="{{ $parent }}"
                                                   {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission-{{ $value->id }}">
                                                {{ $value->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach

                        @error('permission')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                    <button type="submit" class="btn btn-primary">
                        Update Role
                    </button>
                    <a href="{{ route('roles.index', withLang()) }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('script')
@include('roles._permission_script')
@endpush
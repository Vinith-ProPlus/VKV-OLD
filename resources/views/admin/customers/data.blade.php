@extends('layouts.admin')

@section('content')
    @php
        $PageTitle="Customers";
        $ActiveMenuName='Customers';
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i
                                    class="f-16 fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">{{$PageTitle}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-sm-12 col-lg-10">
                <div class="card">
                    <div class="card-header text-center">
                        <div class="row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4 my-2"><h5>{{ $customer  ? 'Edit' : 'Create' }} {{$PageTitle}}</h5></div>
                            <div class="col-sm-4 my-2 text-right text-md-right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <form class="row" action="{{ $customer ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST">
                                    @csrf
                                    @if($customer) @method('PUT') @endif
                                    <div class="form-group col-sm-6 col-lg-6">
                                        <label>Full Name</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ $customer ? old('name', $customer->name) : old('name') }}" required>
                                        @error('name')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6">
                                        <label>Email Address</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                               value="{{ $customer ? old('email', $customer->email) : old('email') }}" required>
                                        @error('email')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Date of Birth</label>
                                        <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror"
                                               value="{{ $customer ? old('dob', $customer->dob) : old('dob') }}" max="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                                        @error('dob')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Mobile</label>
                                        <input type="tel" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                               value="{{ $customer ? old('mobile', $customer->mobile) : old('mobile') }}" required>
                                        @error('mobile')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-12 col-lg-12 mt-15">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" required>{{ $customer ? old('address', $customer->address) : old('address') }}</textarea>
                                        @error('address')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>State</label>
                                        <select name="state_id" id="state" class="form-control select2 @error('state_id') is-invalid @enderror"
                                                data-selected='{{ $customer ? old('state_id', $customer->state_id) : old('state_id') }}' required>
                                            <option value="">Select a State</option>
                                        </select>
                                        @error('state_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>District</label>
                                        <select name="district_id" id="district" class="form-control select2 @error('district_id') is-invalid @enderror"
                                                data-selected='{{ $customer ? old('district_id', $customer->district_id) : old('district_id') }}' required>
                                            <option value="">Select a District</option>
                                        </select>
                                        @error('district_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>City</label>
                                        <select name="city_id" id="city" class="form-control select2 @error('city_id') is-invalid @enderror"
                                                data-selected='{{ $customer ? old('city_id', $customer->city_id) : old('city_id') }}' required>
                                            <option value="">Select a City</option>
                                        </select>
                                        @error('city_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Pincode</label>
                                        <select name="pincode_id" id="pincode" class="form-control select2 @error('pincode_id') is-invalid @enderror"
                                                data-selected='{{ $customer ? old('pincode_id', $customer->pincode_id) : old('pincode_id') }}' required>
                                            <option value="">Select a Pincode</option>
                                        </select>
                                        @error('pincode_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ $customer ? '' : 'required' }}>
                                        @error('password')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Active Status</label>
                                        <select name="active_status" class="form-control @error('active_status') is-invalid @enderror" required>
                                            <option value="1" {{ $customer && $customer->active_status ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $customer && !$customer->active_status ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('active_status')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>


                                    <div class="row mt-15 text-end">
                                        <div>
                                            <a href="javascript:void(0)" onclick="window.history.back()" type="button" class="btn btn-warning">Back</a>
                                            @if(!$customer)
                                                @can('Create Customers')
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                @endcan
                                            @else
                                                @can('Edit Customers')
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                @endcan
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function(){

        // --------------------------------- event listners

        $('#state').change(() => getDistricts());

        $('#district').change(() => getCities());

        $('#city').change(() => getPincodes());

        // ------------------------------- get dropdowns

        const getStates = () =>{

            let StateID = $('#state').attr('data-selected');
            $('#state').select2('destroy');
            $('#state option').remove();
            $('#state').append('<option value="">Select a State</option>');

            $.ajax({
                url:"{{route('getStates')}}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    response.forEach(function(item) {
                        if ((item.id == StateID)) {
                            $('#state').append('<option selected value="' + item.id
                                + '">' + item.name + '</option>');
                        } else {
                            $('#state').append('<option value="' + item.id
                                + '">'  + item.name + '</option>');
                        }
                    });
                },
                error: function(e, x, settings, exception) {
                    // ajaxErrors(e, x, settings, exception);
                },
            });
            $('#state').select2();
            getDistricts();
        }

        const getDistricts = () =>{

            let districtID = $('#district').attr('data-selected');
            let stateID = $('#state').val();
            $('#district').select2('destroy');
            $('#district option').remove();
            $('#district').append('<option value="">Select a District</option>');

            $.ajax({
                url:"{{route('getDistricts')}}",
                type: 'GET',
                dataType: 'json',
                data: { 'state_id':stateID },
                success: function(response) {
                    response.forEach(function(item) {
                        if ((item.id == districtID)) {
                            $('#district').append('<option selected value="' + item.id
                                + '">' + item.name + '</option>');
                        } else {
                            $('#district').append('<option value="' + item.id
                                + '">'  + item.name + '</option>');
                        }
                    });
                },
                error: function(e, x, settings, exception) {
                    // ajaxErrors(e, x, settings, exception);
                },
            });
            $('#district').select2();
            getCities();
        }

        const getCities = () =>{

            let cityID = $('#city').attr('data-selected');
            let districtID = $('#district').val();
            $('#city').select2('destroy');
            $('#city option').remove();
            $('#city').append('<option value="">Select a City</option>');

            $.ajax({
                url:"{{route('getCities')}}",
                type: 'GET',
                dataType: 'json',
                data: { 'district_id':districtID },
                success: function(response) {
                    response.forEach(function(item) {
                        if ((item.id == cityID)) {
                            $('#city').append('<option selected value="' + item.id
                                + '">' + item.name + '</option>');
                        } else {
                            $('#city').append('<option value="' + item.id
                                + '">'  + item.name + '</option>');
                        }
                    });
                },
                error: function(e, x, settings, exception) {
                    // ajaxErrors(e, x, settings, exception);
                },
            });
            $('#city').select2();
            getPincodes();
        }

        const getPincodes = () =>{

            let StateID = $('#pincode').attr('data-selected');
            let districtID = $('#district').val();
            $('#pincode').select2('destroy');
            $('#pincode option').remove();
            $('#pincode').append('<option value="">Select a Pincode</option>');

            $.ajax({
                url:"{{route('getPinCodes')}}",
                type: 'GET',
                dataType: 'json',
                data: { 'district_id':districtID },
                success: function(response) {
                    response.forEach(function(item) {
                        if ((item.id == StateID)) {
                            $('#pincode').append('<option selected value="' + item.id
                                + '">' + item.pincode + '</option>');
                        } else {
                            $('#pincode').append('<option value="' + item.id
                                + '">'  + item.pincode + '</option>');
                        }
                    });
                },
                error: function(e, x, settings, exception) {
                    // ajaxErrors(e, x, settings, exception);
                },
            });
            $('#pincode').select2();
        }

        getStates();

    });
</script>
@endsection

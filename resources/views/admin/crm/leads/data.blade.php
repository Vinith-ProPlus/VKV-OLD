@extends('layouts.admin')

@section('content')
    @php
        $PageTitle="Lead";
        $ActiveMenuName='Lead';
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
                            <div class="col-sm-4 my-2"><h5>{{ $leads  ? 'Edit' : 'Create' }} {{$PageTitle}}</h5></div>
                            <div class="col-sm-4 my-2 text-right text-md-right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <form class="row" action="{{ $leads ? route('leads.update', $leads->id) : route('leads.store') }}" method="POST">
                                    @csrf
                                    @if($leads) @method('PUT') @endif
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="text-center">
                                            <label class="d-block">Lead Image <span class="text-danger">*</span></label>
                                            <div id="image-dropzone" class="image-box border rounded d-flex align-items-center justify-content-center flex-column text-center"
                                                 style="width: 200px; height: 200px; cursor: pointer; background: #f8f9fa; border: 2px dashed #ccc;">
                                                <i class="fa fa-upload fa-2x text-secondary"></i>
                                                <p class="text-muted m-0">Drag &amp; drop a file here or click</p>
                                                <img id="image-preview" src="" class="img-fluid d-none" style="max-width: 100%; max-height: 100%;" alt="">
                                            </div>
                                            <input type="file" id="image-input" name="image" class="d-none" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                               value="{{ $leads ? old('first_name', $leads->first_name) : old('first_name') }}" required>
                                        @error('first_name')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                               value="{{ $leads ? old('last_name', $leads->last_name) : old('last_name') }}">
                                        @error('last_name')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-12 col-lg-12 mt-15">
                                        <label>Address <span class="text-danger">*</span></label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" required>{{ $leads ? old('address', $leads->address) : old('address') }}</textarea>
                                        @error('address')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>State <span class="text-danger">*</span></label>
                                        <select name="state_id" id="state" class="form-control select2 @error('state_id') is-invalid @enderror"
                                                data-selected='{{ $leads ? old('state_id', $leads->state_id) : old('state_id') }}' required>
                                            <option value="">Select a State</option>
                                        </select>
                                        @error('state_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>District <span class="text-danger">*</span></label>
                                        <select name="district_id" id="district" class="form-control select2 @error('district_id') is-invalid @enderror"
                                                data-selected='{{ $leads ? old('district_id', $leads->district_id) : old('district_id') }}' required>
                                            <option value="">Select a District</option>
                                        </select>
                                        @error('district_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>City <span class="text-danger">*</span></label>
                                        <select name="city_id" id="city" class="form-control select2 @error('city_id') is-invalid @enderror"
                                                data-selected='{{ $leads ? old('city_id', $leads->city_id) : old('city_id') }}' required>
                                            <option value="">Select a City</option>
                                        </select>
                                        @error('city_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>PinCode <span class="text-danger">*</span></label>
                                        <select name="pincode_id" id="pincode" class="form-control select2 @error('pincode_id') is-invalid @enderror"
                                                data-selected='{{ $leads ? old('pincode_id', $leads->pincode_id) : old('pincode_id') }}' required>
                                            <option value="">Select a Pincode</option>
                                        </select>
                                        @error('pincode_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>GST No</label>
                                        <input type="text" name="gst_number" class="form-control @error('gst_number') is-invalid @enderror"
                                               value="{{ $leads ? old('gst_number', $leads->gst_number) : old('gst_number') }}">
                                        @error('gst_number')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                               value="{{ $leads ? old('email', $leads->email) : old('email') }}">
                                        @error('email')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Mobile Number <span class="text-danger">*</span></label>
                                        <input type="tel" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror"
                                               value="{{ $leads ? old('mobile_number', $leads->mobile_number) : old('mobile_number') }}" required>
                                        @error('mobile_number')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Whatsapp Number <span class="text-danger">*</span></label>
                                        <input type="tel" name="whatsapp_number" class="form-control @error('whatsapp_number') is-invalid @enderror"
                                               value="{{ $leads ? old('whatsapp_number', $leads->whatsapp_number) : old('whatsapp_number') }}" required>
                                        @error('whatsapp_number')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Lead Source <span class="text-danger">*</span></label>
                                        <select name="lead_source_id" id="lead_source_id" class="form-control select2 @error('lead_source_id') is-invalid @enderror"
                                                data-selected='{{ $leads ? old('lead_source_id', $leads->lead_source_id) : old('lead_source_id') }}' required>
                                            <option value="">Select a Lead Source</option>
                                        </select>
                                        @error('lead_source_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Lead Status <span class="text-danger">*</span></label>
                                        <select name="lead_status_id" id="lead_status_id" class="form-control select2 @error('lead_status_id') is-invalid @enderror"
                                                data-selected='{{ $leads ? old('lead_status_id', $leads->lead_status_id) : old('lead_status_id') }}' required>
                                            <option value="">Select a Lead Status</option>
                                        </select>
                                        @error('lead_status_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-6 mt-15">
                                        <label>Lead Owner <span class="text-danger">*</span></label>
                                        <select name="lead_owner_id" id="lead_owner_id" class="form-control select2 @error('lead_owner_id') is-invalid @enderror"
                                                data-selected='{{ $leads ? old('lead_owner_id', $leads->lead_owner_id) : old('lead_owner_id') }}' required>
                                            <option value="">Select a Lead Owner</option>
                                        </select>
                                        @error('lead_owner_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="row mt-15 text-end">
                                        <div>
                                            <a href="javascript:void(0)" onclick="window.history.back()" type="button" class="btn btn-warning">Back</a>
                                            @if(!$leads)
                                                @can('Create Lead')
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                @endcan
                                            @else
                                                @can('Edit Lead')
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

        @if($leads && $leads->image)
        $("#image-preview").removeClass("d-none").attr("src", "{{ Storage::url($leads->image) }}");
        $("#image-dropzone i, #image-dropzone p").hide();
        @endif

        // ------------------------------- get dropdowns

        const getLeadSource = () =>{
            let LeadSourceID = $('#lead_source_id').attr('data-selected');
            $('#lead_source_id').select2('destroy');
            $('#lead_source_id option').remove();
            $('#lead_source_id').append('<option value="">Select a Lead Source</option>');

            $.ajax({
                url:"{{route('getLeadSource')}}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    response.forEach(function(item) {
                        if ((item.id == LeadSourceID)) {
                            $('#lead_source_id').append('<option selected value="' + item.id
                                + '">' + item.name + '</option>');
                        } else {
                            $('#lead_source_id').append('<option value="' + item.id
                                + '">'  + item.name + '</option>');
                        }
                    });
                },
                error: function(e, x, settings, exception) {
                    // ajaxErrors(e, x, settings, exception);
                },
            });
            $('#lead_source_id').select2();
        }
        const getLeadStatus = () =>{
            let LeadStatusID = $('#lead_status_id').attr('data-selected');
            $('#lead_status_id').select2('destroy');
            $('#lead_status_id option').remove();
            $('#lead_status_id').append('<option value="">Select a Lead Status</option>');

            $.ajax({
                url:"{{route('getLeadStatus')}}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    response.forEach(function(item) {
                        if ((item.id == LeadStatusID)) {
                            $('#lead_status_id').append('<option selected value="' + item.id
                                + '">' + item.name + '</option>');
                        } else {
                            $('#lead_status_id').append('<option value="' + item.id
                                + '">'  + item.name + '</option>');
                        }
                    });
                },
                error: function(e, x, settings, exception) {
                    // ajaxErrors(e, x, settings, exception);
                },
            });
            $('#lead_status_id').select2();
        }
        const getLeadOwner = () =>{
            let LeadOwnerID = $('#lead_owner_id').attr('data-selected');
            $('#lead_owner_id').select2('destroy');
            $('#lead_owner_id option').remove();
            $('#lead_owner_id').append('<option value="">Select a Lead Owner</option>');

            $.ajax({
                url:"{{route('getUsers')}}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    response.forEach(function(item) {
                        if ((item.id == LeadOwnerID)) {
                            $('#lead_owner_id').append('<option selected value="' + item.id
                                + '">' + item.name + '</option>');
                        } else {
                            $('#lead_owner_id').append('<option value="' + item.id
                                + '">'  + item.name + '</option>');
                        }
                    });
                },
                error: function(e, x, settings, exception) {
                    // ajaxErrors(e, x, settings, exception);
                },
            });
            $('#lead_owner_id').select2();
        }

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
        getLeadSource();
        getLeadStatus();
        getLeadOwner();

    });
</script>
@endsection

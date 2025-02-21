@extends('layouts.admin')

@section('content')
    @php
        $PageTitle="Pincodes";
        $ActiveMenuName='Pincodes';
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
                            <div class="col-sm-4 my-2"><h5>{{ $pincode  ? 'Edit' : 'Create' }} {{$PageTitle}}</h5></div>
                            <div class="col-sm-4 my-2 text-right text-md-right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <form action="{{ $pincode ? route('pincodes.update', $pincode->id) : route('pincodes.store') }}" method="POST">
                                    @csrf
                                    @if($pincode) @method('PUT') @endif
                                    <div class="form-group">
                                        <label>Pincode</label>
                                        <input type="text" name="pincode" class="form-control @error('pincode') is-invalid @enderror"
                                               value="{{ $pincode ? old('pincode', $pincode->pincode) : old('pincode') }}" required>
                                        @error('pincode')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-15">
                                        <label>District</label>
                                        <select id="lstDistrict" name="district_id" class="form-control select2 @error('district_id') is-invalid @enderror"
                                        data-selected='{{ $pincode ? old('district_id', $pincode->district_id) : old('district_id') }}' required>
                                            <option value="">--Select a District--</option>
                                        </select>
                                        @error('district_id')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-15">
                                        <label>Active Status</label>
                                        <select name="is_active"
                                                class="form-control @error('is_active') is-invalid @enderror">
                                            <option value="1" {{ $pincode && $pincode->is_active ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $pincode && !$pincode->is_active ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('is_active')
                                        <span class="error invalid-feedback">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="row mt-15 text-end">
                                        <div>
                                            <a href="javascript:void(0)" onclick="window.history.back()" type="button" class="btn btn-warning">Back</a>
                                            @if(!$pincode)
                                                @can('Create Pincodes')
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                @endcan
                                            @else
                                                @can('Edit Pincodes')
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

        const getStates = () =>{

            let StateID = $('#lstDistrict').attr('data-selected');
            $('#lstDistrict').select2('destroy');
            $('#lstDistrict option').remove();
            $('#lstDistrict').append('<option value="">--Select a District--</option>');

            $.ajax({
                url:"{{route('getDistricts')}}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    response.forEach(function(item) {
                        if ((item.id == StateID)) {
                            $('#lstDistrict').append('<option selected value="' + item.id
                                + '">' + item.name + '</option>');
                        } else {
                            $('#lstDistrict').append('<option value="' + item.id
                                + '">'  + item.name + '</option>');
                        }
                    });
                    $('#lstDistrict').select2();
                },
                error: function(xhr) {}
            });
        }

        const init = () => {
            getStates();
        }

        init();
    });
</script>
@endsection

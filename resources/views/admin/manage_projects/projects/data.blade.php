@extends('layouts.admin')

@section('content')
    @php
        $PageTitle="Project";
        $ActiveMenuName='Projects';
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
            <div class="col-12 col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header text-center">
                        <div class="row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4 my-2">
                                <h5>{{ $project  ? 'Edit' : 'Create' }} {{$PageTitle}}</h5></div>
                            <div class="col-sm-4 my-2 text-right text-md-right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <form
                                    action="{{ $project ? route('projects.update', $project->id) : route('projects.store') }}"
                                    method="POST">
                                    @csrf
                                    @if($project)
                                        @method('PUT')
                                    @endif
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Project ID</label>
                                                <input type="text" name="project_id"
                                                       class="form-control @error('project_id') is-invalid @enderror"
                                                       value="{{ $project ? old('project_id', $project->project_id) : old('project_id') }}"
                                                       required>
                                                @error('project_id')
                                                <span class="error invalid-feedback">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Project Name</label>
                                                <input type="text" name="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       value="{{ $project ? old('name', $project->name) : old('name') }}"
                                                       required>
                                                @error('name')
                                                <span class="error invalid-feedback">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-10">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Location</label>
                                                <input type="text" name="location"
                                                       class="form-control @error('location') is-invalid @enderror"
                                                       value="{{ $project ? old('location', $project->location) : old('location') }}"
                                                       required>
                                                @error('location')
                                                <span class="error invalid-feedback">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Type</label>
                                                <input type="text" name="type"
                                                       class="form-control @error('type') is-invalid @enderror"
                                                       value="{{ $project ? old('type', $project->type) : old('type') }}"
                                                       required>
                                                @error('type')
                                                <span class="error invalid-feedback">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-10">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Units</label>
                                                <input type="number" name="units"
                                                       class="form-control @error('units') is-invalid @enderror"
                                                       value="{{ $project ? old('units', $project->units) : old('units') }}"
                                                       required>
                                                @error('units')
                                                <span class="error invalid-feedback">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Target Customers</label>
                                                <input type="text" name="target_customers"
                                                       class="form-control @error('target_customers') is-invalid @enderror"
                                                       value="{{ $project ? old('target_customers', $project->target_customers) : old('target_customers') }}"
                                                       required>
                                                @error('target_customers')
                                                <span class="error invalid-feedback">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-10">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Range</label>
                                                <input type="text" name="range"
                                                       class="form-control @error('range') is-invalid @enderror"
                                                       value="{{ $project ? old('range', $project->range) : old('range') }}"
                                                       required>
                                                @error('range')
                                                <span class="error invalid-feedback">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Active Status</label>
                                                <select name="is_active"
                                                        class="form-control @error('is_active') is-invalid @enderror">
                                                    <option
                                                        value="1" {{ $project && $project->is_active ? 'selected' : '' }}>
                                                        Active
                                                    </option>
                                                    <option
                                                        value="0" {{ $project && !$project->is_active ? 'selected' : '' }}>
                                                        Inactive
                                                    </option>
                                                </select>
                                                @error('is_active')
                                                <span class="error invalid-feedback">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-15 text-end">
                                        <div>
                                            <a href="javascript:void(0)" onclick="window.history.back()" type="button"
                                               class="btn btn-warning">Back</a>
                                            @if(!$project)
                                                @can('Create Projects')
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                @endcan
                                            @else
                                                @can('Edit Projects')
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

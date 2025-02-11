@extends('layouts.admin')

@section('content')
    @php
        $PageTitle="Product Category";
        $ActiveMenuName='Product-Category';
    @endphp
    <div class="container">
        <h2>{{ $productCategory  ? 'Edit' : 'Create' }} Category</h2>

        <form action="{{ $productCategory ? route('product_categories.update', $productCategory->id) : route('product_categories.store') }}" method="POST">
            @csrf
            @if($productCategory) @method('PUT') @endif

            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="category_name" class="form-control @error('category_name') is-invalid @enderror" value="{{ $productCategory ? old('category_name', $productCategory->category_name) : old('category_name') }}" required>
                @error('category_name')
                <span class="error invalid-feedback">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Active Status</label>
                <select name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                    <option value="1" {{ $productCategory ? ($productCategory->is_active ? 'selected' : '') : '' }}>Active</option>
                    <option value="0" {{ $productCategory ? (!$productCategory->is_active ? 'selected' : '') : '' }}>Inactive</option>
                </select>
                @error('is_active')
                <span class="error invalid-feedback">{{$message}}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">{{ $productCategory ? 'Update' : 'Save' }}</button>
            <a href="javascript:void(0)" onclick="window.history.back()" type="button" class="btn btn-warning">Back</a>
        </form>
    </div>
@endsection

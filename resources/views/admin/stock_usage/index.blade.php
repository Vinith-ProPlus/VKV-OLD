@extends('layouts.admin')

@section('content')
    @php
        $PageTitle = "Stock Usage Logs";
        $ActiveMenuName = 'Stock-Usage';
    @endphp

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="f-16 fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">Manage Stock</li>
                        <li class="breadcrumb-item">{{ $PageTitle }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Filter Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Project</label>
                                    <select class="form-control select2" id="project_filter">
                                        <option value="">All Projects</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control select2" id="category_filter">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Taken By</label>
                                    <select class="form-control select2" id="user_filter">
                                        <option value="">All Users</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 text-end align-self-end">
                                <a href="{{ route('stock-usages.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Log New Stock Usage
                                </a>
                            </div>
                        </div>
                        <div class="row mt-15">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" class="form-control" id="date_from" min="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" class="form-control" id="date_to" min="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3 align-self-end">
                                <button id="filter_btn" class="btn btn-info">Apply Filter</button>
                                <button id="reset_btn" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Stock Usage Logs</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="stock_usage_table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Project</th>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Taken By</th>
                                <th>Taken At</th>
                                <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let table = $('#stock_usage_table').DataTable({
                "columnDefs": [{"className": "dt-center", "targets": "_all"}],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('stock-usages.index') }}",
                    data: function (d) {
                        d.project_id = $('#project_filter').val();
                        d.category_id = $('#category_filter').val();
                        d.user_id = $('#user_filter').val();
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'project.name', name: 'project.name'},
                    {data: 'category.name', name: 'category.name'},
                    {data: 'product.name', name: 'product.name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'taken_by_name', name: 'taken_by_name'},
                    {data: 'taken_at', name: 'taken_at'},
                    {data: 'remarks', name: 'remarks'}
                ]
            });

            $('#filter_btn').click(function() {
                table.draw();
            });

            $('#reset_btn').click(function() {
                $('#project_filter').val('');
                $('#category_filter').val('');
                $('#user_filter').val('');
                $('#date_from').val('');
                $('#date_to').val('');
                table.draw();
            });
        });
    </script>
@endsection

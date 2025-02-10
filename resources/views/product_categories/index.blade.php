@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Product Categories</h2>
        <a href="{{ route('product_categories.create') }}" class="btn btn-success mb-3">Add New Category</a>
        <div class="container-fluid mt-3">
            <div class="table-responsive">
                <table class="table text-center border rounded" id="list_table">
                    <thead class="thead-light">
                    <tr>
                        <th>S.No</th>
                        <th>Category Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody class="small">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            $('#list_table').DataTable({
                dom: 'Bfrtip',
                buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'],
                "columnDefs": [
                    {"className": "dt-center", "targets": "_all"}
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("product_categories.index") }}',
                    type: 'GET'
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'category_name'},
                    {data: 'is_active'},
                    {data: 'action', orderable: false},
                ]
            });
        });
    </script>
@endsection

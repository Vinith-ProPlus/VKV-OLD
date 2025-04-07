<!-- resources/views/admin/project_stocks/index.blade.php -->
@extends('layouts.admin')

@section('content')
    @php
        $PageTitle = "Project Stock Management";
        $ActiveMenuName = 'Project-Stock-Management';
    @endphp

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="f-16 fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">Transactions</li>
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
{{--                    <div class="card-header">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <h5>{{ $PageTitle }}</h5>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6 text-right">--}}
{{--                                <button type="button" class="btn btn-primary btn-sm" onclick="$('#adjustStockModal').modal('show');">--}}
{{--                                    <i class="fa fa-edit"></i> Adjust Stock--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="row mt-3">--}}
{{--                            <div class="col-md-4">--}}
{{--                                <select id="project_filter" class="form-control">--}}
{{--                                    <option value="">All Projects</option>--}}
{{--                                    @foreach($projects as $project)--}}
{{--                                        <option value="{{ $project->id }}">{{ $project->name }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="card-header text-center">
                        <div class="row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4 my-2"><h5>{{$PageTitle}}</h5></div>
                            <div class="col-sm-4 my-2 text-right text-md-right">
                                @can('Edit Project Stocks')
                                    <button type="button" class="btn btn-primary btn-sm" onclick="$('#adjustStockModal').modal('show');">
                                        <i class="fa fa-edit"></i> Adjust Stock </button>
                                @endcan
                            </div>
                        </div>
                        <div class="row align-items-center justify-content-center">
                            <div class="col-sm-2">
                                <div class="form-group text-center mh-60">
                                    <label style="margin-bottom: 0px;">Projects</label>
                                    <div id="divProject">
                                        <select class="form-control form-control-sm text-center" id="project_filter">
                                            <option value="">Select a Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 d-flex align-items-center justify-content-center">
                                <button class="btn btn-sm btn-danger mt-3" id="clearFilters">Clear Filters</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="stocksTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Last Updated</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="adjustStockModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adjust Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="adjustStockForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Project</label>
                            <select name="project_id" class="form-control" required>
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-15">
                            <label>Product</label>
                            <select name="product_id" class="form-control" required>
                                <option value="">Select Product</option>
                                <!-- Products will be loaded via AJAX -->
                            </select>
                        </div>
                        <div class="form-group mt-15">
                            <label>Adjustment Type</label>
                            <select name="adjustment_type" class="form-control" required>
                                <option value="add">Add Quantity</option>
                                <option value="subtract">Subtract Quantity</option>
                                <option value="set">Set Exact Quantity</option>
                            </select>
                        </div>
                        <div class="form-group mt-15">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="0.01" step="0.01" required>
                        </div>
                        <div class="form-group mt-15">
                            <label>Reason</label>
                            <textarea type="text" name="reason" class="form-control" maxlength="255" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="$('#adjustStockModal').modal('hide');">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#clearFilters').click(clearFilter);

            function initMultiSelect() {
                $('#project_filter').multiselect({
                    buttonClass: 'btn btn-link',
                    enableFiltering: true,
                    maxHeight: 250,
                });
                $('select[name="project_id"]').select2({ dropdownParent: $('#adjustStockModal') });
            }
            function clearFilter() {
                $('#project_filter').val('').multiselect('refresh');
                table.ajax.reload();
            }

            initMultiSelect();

            let table = $('#stocksTable').DataTable({
                "columnDefs": [{"className": "dt-center", "targets": "_all"}],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('project-stocks.index') }}",
                    data: function (d) {
                        d.project_id = $('#project_filter').val();
                    }
                },
                columns: [
                    {data: 'project.name', name: 'project.name'},
                    {data: 'category.name', name: 'category.name'},
                    {data: 'product.name', name: 'product.name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'last_updated', name: 'updated_at'}
                ]
            });

            // Project filter
            $('#project_filter').change(function() {
                table.ajax.reload();
            });

            $('select[name="project_id"]').change(function () {
                var projectId = $(this).val();
                var $productSelect = $('select[name="product_id"]');

                if ($productSelect.hasClass("select2-hidden-accessible")) {
                    $productSelect.select2('destroy');
                }

                if (projectId) {
                    $.ajax({
                        url: '/project-products', // You can change this to `/products?project_id=${projectId}` if needed
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $productSelect.empty().append('<option value="">Select Product</option>');

                            $.each(data, function (key, value) {
                                $productSelect.append(
                                    '<option value="' + value.id + '">' +
                                    value.name + ' (' + value.category.name + ')' +
                                    '</option>'
                                );
                            });

                            // Re-initialize Select2 after updating options
                            $productSelect.select2({ dropdownParent: $('#adjustStockModal') });
                        }
                    });
                } else {
                    $productSelect.empty().append('<option value="">Select Product</option>');
                    $productSelect.select2({ dropdownParent: $('#adjustStockModal') });
                }
            });



            // Handle stock adjustment form
            $('#adjustStockForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('project-stocks.adjust') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.success) {
                            $('#adjustStockModal').modal('hide');
                            $('#adjustStockForm')[0].reset();
                            table.ajax.reload();
                            toastr.success('Stock adjusted successfully');
                        }
                    },
                    error: function(xhr) {
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('Error adjusting stock');
                        }
                    }
                });
            });
        });
    </script>
@endsection

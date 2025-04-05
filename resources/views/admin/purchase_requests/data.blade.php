@extends('layouts.admin')

@section('content')
    @php
        $PageTitle = "Purchase Request";
        $ActiveMenuName = 'Purchase Requests';
        $isEdit = $purchaseRequest && $purchaseRequest->id;
    @endphp

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="f-16 fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">Manage Purchases</li>
                        <li class="breadcrumb-item">{{ $PageTitle }}</li>
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
                        <h5>{{ $isEdit ? 'Edit' : 'Create' }} {{ $PageTitle }}</h5>
                    </div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ $isEdit ? route('purchase-requests.update', $purchaseRequest->id) : route('purchase-requests.store') }}" method="POST">
                            @csrf
                            @if($isEdit) @method('PUT') @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="project_id">Project <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="project_id" id="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ $isEdit && $purchaseRequest->project_id == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($isEdit)
                                    <div class="col-md-6">
                                        <label>Status</label>
                                        <div class="form-control">{{ ucfirst($purchaseRequest->status) }}</div>
                                    </div>
                                @endif
                            </div>

                            <hr>
                            <div class="card">
                                <div class="row" style="background-color: #7167f430;padding: 20px;border-radius: 15px;box-shadow: 1px 10px 40px #e4e2fde3;">
                                    <div class="col-4">
                                        <label for="contract_type_id"><strong>Category</strong></label>
                                        <select class="form-control select2" id="contract_type_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="contractor_id"><strong>Product</strong></label>
                                        <select class="form-control select2" id="contractor_id">
                                            <option value="">Select a Product</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="txtAmount"><strong>Quantity</strong></label>
                                        <input class="form-control" id="txtAmount" type="number" step="0.01" min="0">
                                    </div>
                                    <div class="col-2 align-self-end">
                                        <a class="btn btn-sm" id="addContracts" style="background-color: #7167f4;color: #fff;">Add</a>
                                        <a class="btn btn-sm btn-warning d-none" id="updateContracts">Update</a>
                                        <a class="btn btn-sm mx-2 btn-danger" id="clearContracts">Clear</a>
                                    </div>
                                </div>
                            </div>

                            <table class="table table-hover {{ ($isEdit && count($purchaseRequest->details) > 0) ? '' : 'd-none' }} mt-20 form-group" id="productsTable">
                                <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="tblContract">
                                @if($isEdit)
                                    @foreach($purchaseRequest->details as $index => $detail)
                                        <tr data-new="false">
                                            <td>{{ $index + 1 }}</td>
                                            <td data-contract-type="{{ $detail->category->name }}">
                                                {{ $detail->category->name }}
                                                <input type="hidden" value="{{ $detail->category_id }}" name="products[{{ $detail->id }}][category_id]" data-id="{{ $detail->id }}">
                                            </td>
                                            <td data-contractor="{{ $detail->product->name }}">
                                                {{ $detail->product->name }}
                                                <input type="hidden" value="{{ $detail->product_id }}" name="products[{{ $detail->id }}][product_id]" data-id="{{ $detail->id }}">
                                            </td>
                                            <td data-amount="{{ $detail->quantity }}">
                                                {{ $detail->quantity }}
                                                <input type="hidden" value="{{ $detail->quantity }}" name="products[{{ $detail->id }}][quantity]" data-id="{{ $detail->id }}">
                                            </td>
                                            <td>
                                                <a class="btn btn-outline-primary editContracts"><i class="fa fa-pencil"></i></a>
                                                <a class="btn btn-danger deleteContracts"><i class="fa fa-trash"></i></a>
                                            </td>
                                            <td data-tdata='{{ json_encode(["contract_type_id" => $detail->category_id, "contract_name" => $detail->category->name, "user_id" => $detail->product_id, "user_name" => $detail->product->name, "amount" => $detail->quantity]) }}' class="d-none"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>

                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-success">{{ $isEdit ? 'Update' : 'Submit' }} Request</button>
                                <a href="{{ route('purchase-requests.index') }}" class="btn btn-secondary">Cancel</a>
                                @if($purchaseRequest)
                                    <button type="button" class="btn btn-primary convert-to-po" data-request-id="{{ $purchaseRequest && $purchaseRequest->id }}">
                                        Convert to Purchase Order
                                    </button>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            let tableEditId = 1;
            let deletedDocuments = [];
            let selectedContractRow = null;
            let contractCellId = {{ $isEdit ? ($purchaseRequest->details->count() > 0 ? $purchaseRequest->details->max('id') + 1 : 0) : 0 }};
            let contractUpdateId = 0;
            $('.select2').select2();

            $(document).on('click', '.convert-to-po', function() {
                var requestId = $(this).data('request-id');
                var route = "{{ route('purchase-orders.convertRequestForm') }}";
                window.location.href = route + '?request_id=' + requestId;
            });

            // Function to get product categories
            const getContractTypes = () => {
                let ContractID = $('#contract_type_id');
                let SelectedContractID = ContractID.attr('data-selected');
                ContractID.select2('destroy');
                $('#contract_type_id option').remove();
                ContractID.append('<option value="">--Select a Category--</option>');

                $.ajax({
                    url: "{{route('getCategories')}}",
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        response.forEach(function (item) {
                            if ((item.id == SelectedContractID)) {
                                ContractID.append('<option selected value="' + item.id
                                    + '">' + item.name + '</option>');
                            } else {
                                ContractID.append('<option value="' + item.id
                                    + '">' + item.name + '</option>');
                            }
                        });
                        ContractID.select2();
                    },
                    error: function (xhr) {
                        console.error('Error loading categories:', xhr);
                    }
                });
            }

            // Function to get products by category
            const getContractors = () => {
                let ContractorID = $('#contractor_id');
                let SelectedContractorID = ContractorID.attr('data-selected');
                ContractorID.select2('destroy');
                $('#contractor_id option').remove();
                ContractorID.append('<option value="">--Select a Product--</option>');
                let categoryId = $('#contract_type_id').val();

                if (!categoryId) return;

                $.ajax({
                    url: "{{route('getProductsByCategory')}}",
                    type: 'GET',
                    data: {category_id: categoryId},
                    success: function (response) {
                        response.forEach(function (item) {
                            if ((item.id == SelectedContractorID)) {
                                ContractorID.append('<option selected value="' + item.id
                                    + '">' + item.name + '</option>');
                            } else {
                                ContractorID.append('<option value="' + item.id
                                    + '">' + item.name + '</option>');
                            }
                        });
                        ContractorID.select2();
                    },
                    error: function (xhr) {
                        console.error('Error loading products:', xhr);
                    }
                });
            }

            // Initialize categories dropdown
            getContractTypes();

            // Load products when category changes
            $('#contract_type_id').change(function () {
                getContractors();
            });

            // Add product to table
            $('#addContracts').on('click', function () {
                let contract_type = $('#contract_type_id').find('option:selected');
                let contractor = $('#contractor_id').find('option:selected');
                let amount = parseFloat($('#txtAmount').val()).toFixed(2);

                if (contract_type.val() && contractor.val() && !isNaN(amount)) {
                    let status = true;
                    let table = $('#tblContract');

                    table.find('tr').each(function () {
                        let contractTypeId = $(this).find('td[data-contract-type] input').val();
                        let contractorId = $(this).find('td[data-contractor] input').val();

                        if (contractTypeId === contract_type.val() && contractorId === contractor.val()) {
                            status = false;
                            alert('This product already exists in the list.');
                            return false;
                        }
                    });

                    if (status) {
                        const obj = {
                            'contract_type_id': contract_type.val(),
                            'contract_name': contract_type.text(),
                            'user_id': contractor.val(),
                            'user_name': contractor.text(),
                            'amount': amount
                        };

                        let html = `
                        <tr data-new="true">
                            <td>*</td>
                            <td data-contract-type="${contract_type.text()}">
                                ${contract_type.text()}
                                <input type="hidden" value="${contract_type.val()}" name="products[${contractCellId}][category_id]" data-id="${contractCellId}">
                            </td>
                            <td data-contractor="${contractor.text()}">
                                ${contractor.text()}
                                <input type="hidden" value="${contractor.val()}" name="products[${contractCellId}][product_id]" data-id="${contractCellId}">
                            </td>
                            <td data-amount="${amount}">
                                ${amount}
                                <input type="hidden" value="${amount}" name="products[${contractCellId}][quantity]" data-id="${contractCellId}">
                            </td>
                            <td>
                                <a class="btn btn-outline-primary editContracts"><i class="fa fa-pencil"></i></a>
                                <a class="btn btn-danger deleteContracts"><i class="fa fa-trash"></i></a>
                            </td>
                            <td data-tdata='${JSON.stringify(obj)}' class="d-none">${JSON.stringify(obj)}</td>
                        </tr>`;

                        table.append(html);
                        contractCellId++;
                        serializeTable('#tblContract');

                        $('#productsTable').removeClass('d-none');
                        clearContractFields();
                    }
                } else {
                    alert('Please select a category, product, and enter a valid quantity.');
                }
            });

            // Clear form fields
            $('#clearContracts').on('click', function () {
                $('#updateContracts').addClass('d-none');
                $('#addContracts').removeClass('d-none');
                clearContractFields();
            });

            // Edit row data
            $(document).on('click', '.editContracts', function () {
                selectedContractRow = $(this).closest('tr');
                contractUpdateId = selectedContractRow.find('td:nth-child(2) input').attr('data-id');

                let tdata;
                try {
                    tdata = JSON.parse(selectedContractRow.find('td[data-tdata]').attr('data-tdata'));
                } catch (e) {
                    console.error('Error parsing JSON data:', e);
                    return;
                }

                $('#contract_type_id').val(tdata.contract_type_id).attr('data-selected', tdata.contract_type_id).trigger('change');

                // Need to wait for category dropdown to load products
                setTimeout(function() {
                    $('#contractor_id').attr('data-selected', tdata.user_id);
                    getContractors();

                    // Need to wait for products to load before setting value
                    setTimeout(function() {
                        $('#contractor_id').val(tdata.user_id).trigger('change');
                        $('#txtAmount').val(tdata.amount);
                    }, 500);
                }, 500);

                $('#addContracts').addClass('d-none');
                $('#updateContracts').removeClass('d-none');
            });

            // Delete row
            $(document).on('click', '.deleteContracts', function () {
                $(this).closest('tr').remove();
                serializeTable('#tblContract');

                let isRowEmpty = $('#tblContract').find('tr').length;
                if (!isRowEmpty) {
                    $('#productsTable').addClass('d-none');
                }
            });

            // Update row data
            $('#updateContracts').on('click', function () {
                let contract_type = $('#contract_type_id').find('option:selected');
                let contractor = $('#contractor_id').find('option:selected');
                let amount = parseFloat($('#txtAmount').val()).toFixed(2);

                if (contract_type.val() && contractor.val() && !isNaN(amount)) {
                    let status = true;
                    let table = $('#tblContract');

                    table.find('tr').not(selectedContractRow).each(function () {
                        let contractTypeId = $(this).find('td:nth-child(2) input').val();
                        let contractorId = $(this).find('td:nth-child(3) input').val();

                        if (contractTypeId === contract_type.val() && contractorId === contractor.val()) {
                            status = false;
                            alert('This product already exists in another row.');
                            return false;
                        }
                    });

                    if (status) {
                        const obj = {
                            'contract_type_id': contract_type.val(),
                            'contract_name': contract_type.text(),
                            'user_id': contractor.val(),
                            'user_name': contractor.text(),
                            'amount': amount
                        };

                        selectedContractRow.find('td:nth-child(2)').attr('data-contract-type', contract_type.text())
                            .html(`${contract_type.text()}<input type="hidden" value="${contract_type.val()}" name="products[${contractUpdateId}][category_id]" data-id="${contractUpdateId}">`);

                        selectedContractRow.find('td:nth-child(3)').attr('data-contractor', contractor.text())
                            .html(`${contractor.text()}<input type="hidden" value="${contractor.val()}" name="products[${contractUpdateId}][product_id]" data-id="${contractUpdateId}">`);

                        selectedContractRow.find('td:nth-child(4)').attr('data-amount', amount)
                            .html(`${amount}<input type="hidden" value="${amount}" name="products[${contractUpdateId}][quantity]" data-id="${contractUpdateId}">`);

                        selectedContractRow.find('td[data-tdata]').attr('data-tdata', JSON.stringify(obj)).text(JSON.stringify(obj));

                        $('#updateContracts').addClass('d-none');
                        $('#addContracts').removeClass('d-none');

                        selectedContractRow = null;
                        clearContractFields();
                    }
                } else {
                    alert('Please select a category, product, and enter a valid quantity.');
                }
            });

            // Helper function to clear form fields
            const clearContractFields = () => {
                $('#contract_type_id').attr('disabled', false);
                $('#contractor_id').attr('disabled', false);
                $('#contract_type_id, #contractor_id').val(null).trigger('change');
                $('#txtAmount').val('');
            };

            // Helper function to renumber rows
            const serializeTable = (selector) => {
                let i = 1;
                $(`${selector} tr`).each(function () {
                    $(this).find('td:first').text(i++);
                });
            };

            // Make sure table is visible if it has rows
            if ($('#tblContract tr').length > 0) {
                $('#productsTable').removeClass('d-none');
            }

            // Add debugging before form submission
            $('form').on('submit', function() {
                console.log('Form submitted with data:', $(this).serialize());
                return true;
            });
        });
    </script>
@endsection

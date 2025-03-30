@extends('layouts.admin')

@section('content')
    @php
        $PageTitle="Purchase Orders";
        $ActiveMenuName='Purchase-Orders';
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">Transactions</li>
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
                            <div class="col-sm-4 my-2"><h5>{{ $purchaseOrder  ? 'Edit' : 'Create' }} {{$PageTitle}}</h5></div>
                            <div class="col-sm-4 my-2 text-right text-md-right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3 mt-20">
                                <div class="form-group">
                                    <label class="txtOrderNo">Order Number <span class="required"> * </span></label>
                                    <input type="text" class="form-control" id="txtOrderNo" value="{{$orderNo}}" value="{{ $purchaseOrder->order_no ?? '' }}" disabled>
                                    <div class="errors err-sm" id="txtOrderNo-err"></div>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-20">
                                <div class="form-group">
                                    <label class="dtpOrderDate">Order Date <span class="required"> * </span></label>
                                    <input type="date" class="form-control" id="dtpOrderDate" value="{{ $purchaseOrder->order_date ?? date('Y-m-d') }}">
                                    <div class="errors err-sm" id="dtpOrderDate-err"></div>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-20">
                                <div class="form-group">
                                    <label class="lstProject">Project <span class="required"> * </span></label>
                                    <select id="lstProject" class="form-control select2">
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="errors err-sm" id="lstProject-err"></div>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-20">
                                <div class="form-group">
                                    <label class="lstPurchaseReq">Purchase Request No</label>
                                    <select id="lstPurchaseReq" class="form-control select2">
                                        <option value="">Select Purchase Request No</option>
                                        @foreach ($purchaseRequests as $purchaseRequest)
                                            <option value="{{ $purchaseRequest->id }}">{{ $purchaseRequest->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="errors err-sm" id="lstPurchaseReq-err"></div>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-20">
                                <div class="form-group">
                                    <label class="lstWarehouse">Warehouse <span class="required"> * </span></label>
                                    <select id="lstWarehouse" class="form-control select2">
                                        <option value="">Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="errors err-sm" id="lstWarehouse-err"></div>
                                </div>
                            </div>
                            <div class="col-12 my-2">
                                <hr>
                            </div>
                            <div class="row" id="divItems">
                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Product Category <span class="required"> * </span></label>
                                        <select id="lstProductCategory" class="form-control select2">
                                            <option value="">Select a Product Category</option>
                                            @foreach ($productCategories as $pCategory)
                                                <option value="{{ $pCategory->id }}">{{ $pCategory->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="errors err-sm" id="lstProductCategory-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Product <span class="required"> * </span></label>
                                        <select id="lstProduct" class="form-control select2">
                                            <option value="">Select a Product</option>
                                        </select>
                                        <div class="errors err-sm" id="lstProduct-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Tax <span class="required"> * </span></label>
                                        <select id="lstTax" class="form-control select2">
                                            <option value="">Select Tax</option>
                                            @foreach ($taxes as $tax)
                                                <option value="{{ $tax->id }}" data-per="{{ $tax->percentage }}">{{ $tax->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="errors err-sm" id="lstTax-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Price <span class="required"> * </span></label>
                                        <input type="text" id="txtPrice" class="form-control">
                                        <div class="errors err-sm" id="txtPrice-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Quantity <span class="required"> * </span></label>
                                        <input type="number" id="txtQty" class="form-control">
                                        <div class="errors err-sm" id="txtQty-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Amount <span class="required"> * </span></label>
                                        <input type="text" id="txtAmount" class="form-control" readonly>
                                        <div class="errors err-sm" id="txtAmount-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Tax Type <span class="required"> * </span></label>
                                        <select id="lstTaxType" class="form-control">
                                            <option value="include">Include</option>
                                            <option value="exclude">Exclude</option>
                                        </select>
                                        <div class="errors err-sm" id="lstTaxType-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Taxable <span class="required"> * </span></label>
                                        <input type="text" id="txtTaxable" class="form-control" readonly>
                                        <div class="errors err-sm" id="txtTaxable-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Tax Amount <span class="required"> * </span></label>
                                        <input type="text" id="txtTaxAmount" class="form-control" readonly>
                                        <div class="errors err-sm" id="txtTaxAmount-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-20">
                                    <div class="form-group">
                                        <label>Total Amount</label>
                                        <input type="text" id="txtTotalAmount" class="form-control" readonly>
                                        <div class="errors err-sm" id="txtTotalAmount-err"></div>
                                    </div>
                                </div>

                                <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                    <button type="button" id="btnAddItem" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                            <div class="col-12 my-2">
                            </div>
                            <div class="col-sm-12 mt-3">
                                <table class="table tblItems" id="tblItems">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Product (Category)</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Tax</th>
                                            <th class="text-center">Tax Percentage</th>
                                            <th class="text-center">Taxable</th>
                                            <th class="text-center">Tax Amount</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($purchaseOrder)
                                        @php
                                            $spec_values = json_decode($purchaseOrder->spec_values);
                                        @endphp
                                            @foreach($spec_values as $Key=>$row)
                                                <tr>
                                                    <td>{{$Key + 1}}</td>
                                                    <td>{{$row->value_name}}</td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger btnDeleteItem"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 my-2">
                                <hr>
                            </div>
                            <div class="col-12">
                                <div class="row justify-content-end">
                                    <div class="col-sm-4">
                                        <div class="row justify-content-end mt-20 fw-600 fs-16 mr-10">
                                            <div class="col-6 col-sm-6 col-lg-6">Sub Total <span class="text-end">:</span></div>
                                            <div class="col-6 col-sm-4 col-lg-4 text-right" id="divSubTotal">0.00</div>
                                        </div>
                                        <div class="row justify-content-end mt-20 fw-600 fs-16 mr-10">
                                            <div class="col-6 col-sm-6 col-lg-6">Tax Amount <span class="cright">:</span></div>
                                            <div class="col-6 col-sm-4 col-lg-4 text-right" id="divTaxAmount">0.00</div>
                                        </div>
                                        <div class="row justify-content-end mt-20  fw-700 fs-18 mr-10 text-success">
                                            <div class="col-6 col-sm-6 col-lg-6">Total Amount <span class="cright">:</span></div>
                                            <div class="col-5 col-sm-4 col-lg-4 text-right" id="divTotalAmount"> 0.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row mt-15 justify-content-end">
                            <div class="col-md-4 text-end">
                                <a href="javascript:void(0)" onclick="window.history.back()" type="button" class="btn btn-warning">Back</a>
                                @if(!$purchaseOrder)
                                    @can('Create Project Specifications')
                                        <button type="button" class="btn btn-primary" id="btnSave">Save</button>
                                    @endcan
                                @else
                                    @can('Edit Project Specifications')
                                        <button type="button" class="btn btn-primary" id="btnSave">Update</button>
                                    @endcan
                                @endif
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
    $(document).ready(function () {

        const getProducts=async()=>{
            let category_id=$('#lstProductCategory').val();
            $('#lstProduct').select2('destroy');
            $('#lstProduct option').remove();
            $('#lstProduct').append('<option value="">Select a Products</option>');
            if(category_id){
                $.ajax({
                    type:"get",
                    url:"{{route('getProducts')}}",
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    data:{category_id},
                    dataType:"json",
                    async:true,
                    error:function(e, x, settings, exception){ajaxErrors(e, x, settings, exception);},
                    complete: function(e, x, settings, exception){},
                    success:function(response){
                        for(let item of response){
                            let selected = $('#lstProduct').attr('data-selected') == item.id ? 'selected' : '';
                            $('#lstProduct').append('<option '+selected+' value="'+item.id+'" data-uom="'+item.uom_code+'" data-tax="'+item.tax_id+'">'+item.name+'( '+item.uom_code+' ) </option>');
                        }
                    }
                });
            }
            $('#lstProduct').select2();

        }

        getProducts();

        $("#lstProductCategory").change(function () {
            getProducts();
        });

        $('#lstProduct').change(function () {
            let selected = $(this).find(':selected');
            let uom_id = selected.data('uom');
            let tax_id = selected.data('tax');
            let price = selected.data('price');

            $('#lstUom').val(uom_id).change();
            $('#lstTax').val(tax_id).change();
            $('#txtPrice').val(price);
        });

        // Calculate Amount, Tax, and Total
        $('#txtQty').on('input', function () {
            let qty = parseFloat($(this).val()) || 0;
            let price = parseFloat($('#txtPrice').val()) || 0;
            let taxPercentage = parseFloat($('#lstTax option:selected').text().match(/\d+/)) || 0;
            let taxType = $('#lstTaxType').val();

            let amount = qty * price;
            let taxAmount = taxType === 'include' ? amount * (taxPercentage / (100 + taxPercentage)) : amount * (taxPercentage / 100);
            let totalAmount = amount + (taxType === 'exclude' ? taxAmount : 0);

            $('#txtAmount').val(amount.toFixed(2));
            $('#txtTaxAmount').val(taxAmount.toFixed(2));
            $('#txtTotalAmount').val(totalAmount.toFixed(2));
        });

        $('#btnAddItem').click(function () {
            let status = true;
            let edit_id = $(this).data('edit_id');

            // Clear previous error messages
            $('.errors').text('');

            let itemData = {
                detail_id: $(this).data('detail_id'),
                category: $('#lstProductCategory option:selected').text(),
                category_id: $('#lstProductCategory').val(),
                product: $('#lstProduct option:selected').text(),
                product_id: $('#lstProduct').val(),
                qty: parseFloat($('#txtQty').val().trim()) || 0,
                price: parseFloat($('#txtPrice').val().trim()) || 0,
                amount: parseFloat($('#txtAmount').val().trim()) || 0,
                tax: $('#lstTax option:selected').text(),
                tax_id: $('#lstTax').val(),
                taxType: $('#lstTaxType').val(),
                taxable: parseFloat($('#txtTaxable').val().trim()) || 0,
                taxAmount: parseFloat($('#txtTaxAmount').val().trim()) || 0,
                totalAmount: parseFloat($('#txtTotalAmount').val().trim()) || 0
            };
            console.log(itemData);

            // Validation
            if (!itemData.category_id) {
                $('#lstProductCategory-err').text('Product Category is required'); status = false;
            }
            if (!itemData.product_id) {
                $('#lstProduct-err').text('Product is required'); status = false;
            }
            if (!itemData.qty) {
                $('#txtQty-err').text('Quantity is required'); status = false;
            }
            if (!itemData.price) {
                $('#txtPrice-err').text('Price is required'); status = false;
            }
            if (!itemData.tax_id) {
                $('#lstTax-err').text('Tax is required'); status = false;
            }
            if (!itemData.amount) {
                $('#txtAmount-err').text('Amount is required'); status = false;
            }
            if (!itemData.taxable) {
                $('#txtTaxable-err').text('Taxable is required'); status = false;
            }
            if (!itemData.taxAmount) {
                $('#txtTaxAmount-err').text('Tax Amount is required'); status = false;
            }
            if (!itemData.totalAmount) {
                $('#txtTotalAmount-err').text('Total Amount is required'); status = false;
            }

            let isDuplicate = false;
            $('#tblItems tbody tr').each(function (index) {
                let existingItem = JSON.parse($(this).find('.itemData').val());

                if (edit_id && index + 1 === edit_id) {
                    return true; // Continue to the next iteration
                }

                if (existingItem.product_id == itemData.product_id) {
                    isDuplicate = true;
                    return false;
                }
            });

            if (isDuplicate) {
                $('#lstProduct-err').text('This product is already added to the list');
                status = false;
            }

            if (status) {
                let row = `
                    <tr data-product_id="${itemData.product_id}">
                        <td class="text-center">${edit_id ? edit_id : $('#tblItems tbody tr').length + 1}</td>
                        <td class="text-center">${itemData.product} (${itemData.category})</td>
                        <td class="text-right">${itemData.qty}</td>
                        <td class="text-right">${itemData.price}</td>
                        <td class="text-right">${itemData.amount}</td>
                        <td class="text-center">${itemData.tax}</td>
                        <td class="text-center">${itemData.taxType}</td>
                        <td class="text-right">${itemData.taxable}</td>
                        <td class="text-right">${itemData.taxAmount}</td>
                        <td class="text-right">${itemData.totalAmount}</td>
                        <td class="text-center divItemBtn">
                            <button class="btn btn-warning btn-sm btnEdit">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="btn btn-danger btn-sm btnDelete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                        <td><input type="hidden" class="itemData" value='${JSON.stringify(itemData)}'></td>
                    </tr>
                `;

                if (edit_id) {
                    // Replace the existing row
                    $(`#tblItems tbody tr:eq(${edit_id - 1})`).replaceWith(row);
                } else {
                    // Append a new row
                    $('#tblItems tbody').append(row);
                }
                $('.btnCancel').remove();
                clearItemData();

            }
        });


        $(document).on('click', '.btnEdit', function () {
            let row = $(this).closest('tr');
            let itemData = JSON.parse(row.find('.itemData').val());

            $('#lstProduct').attr('data-selected',itemData.product_id);
            $('#txtQty').val(itemData.qty);
            $('#txtPrice').val(itemData.price);
            $('#txtAmount').val(itemData.amount);
            $('#lstTax').val(itemData.tax_id).trigger('change');
            $('#lstTaxType').val(itemData.taxType);
            $('#txtTaxable').val(itemData.taxable);
            $('#txtTaxAmount').val(itemData.taxAmount);
            $('#txtTotalAmount').val(itemData.totalAmount);
            setTimeout(() => {
                $('#lstProductCategory').val(itemData.category_id).trigger('change');
            }, 500);

            $('#btnAddItem').data('edit_id', row.index() + 1).data('detail_id', itemData.detail_id).text('Update');

            $('.btnEdit, .btnDelete').hide();

            row.find('.divItemBtn').append('<button class="btn btn-secondary btn-sm btnCancel">Cancel</button>');

        });

        // Cancel Button Click - Restore Edit/Delete Buttons
        $(document).on('click', '.btnCancel', function () {
            $(this).remove();
            clearItemData();
        });
        function clearItemData() {
            $('.btnEdit, .btnDelete').show();
            $('#lstProductCategory, #lstTax').val('').trigger('change');
            $('#lstProduct').attr('data-selected','');
            $('#txtQty, #txtPrice, #txtAmount, #txtTaxable, #txtTaxAmount, #txtTotalAmount').val('');
            $('#btnAddItem').removeData('edit_id').removeData('detail_id').text('Add');
        }



        // Edit and Delete functionalities
        $(document).on('click', '.btnDelete', function () {
            $(this).closest('tr').remove();
            $('#tblItems tbody tr').each(function (index) {
                $(this).find('td:first').text(index + 1);
            });
        });

        $(document).on('click change', '.form-control', function () {
            $('.errors').text('');
        });

        // Form Validation Function
        function ItemFormValidation() {
            let valid = true;
            $('#divItems .form-control').each(function () {
                if (!$(this).val()) {
                    valid = false;
                    $(this).css('border', '1px solid red');
                } else {
                    $(this).css('border', '');
                }
            });
            return valid;
        }

        function calculateTotals() {
            let qty = parseFloat($('#txtQty').val()) || 0;
            let price = parseFloat($('#txtPrice').val()) || 0;
            let taxPercentage = parseFloat($('#lstTax option:selected').data('per')) || 0;
            let taxType = $('#lstTaxType').val();

            let amount = qty * price;
            let taxAmount = 0;
            let totalAmount = 0;
            let taxable = 0;

            if (taxType === 'include') {
                taxAmount = amount * (taxPercentage / (100 + taxPercentage));
                taxable = amount - taxAmount;
                totalAmount = amount;
            } else {
                taxAmount = amount * (taxPercentage / 100);
                taxable = amount;
                totalAmount = amount + taxAmount;
            }

            $('#txtAmount').val(amount.toFixed(2));
            $('#txtTaxable').val(taxable.toFixed(2));
            $('#txtTaxAmount').val(taxAmount.toFixed(2));
            $('#txtTotalAmount').val(totalAmount.toFixed(2));
        }

        $('#txtPrice, #txtQty, #lstTaxType, #lstProduct, #lstTax').on('change input', calculateTotals);

        // Call this function whenever items are added, edited, or deleted
        $(document).on('click', '#btnAddItem, .btnEdit, .btnDelete', function () {
            formCalc();
        });

        const formCalc=async()=>{
            let subTotal = 0, taxAmount = 0, totalAmount = 0;

            $('#tblItems tbody tr').each(function () {
                let amount = parseFloat($(this).find('td:nth-child(5)').text()) || 0;
                let tax = parseFloat($(this).find('td:nth-child(9)').text()) || 0;
                let total = parseFloat($(this).find('td:nth-child(10)').text()) || 0;

                subTotal += amount;
                taxAmount += tax;
                totalAmount += total;
            });

            $('#divSubTotal').text(subTotal.toFixed(2));
            $('#divTaxAmount').text(taxAmount.toFixed(2));
            $('#divTotalAmount').text(totalAmount.toFixed(2));
        }




        const ValidateGetData = async () => {
            let status = true;
            $('.errors').text('');

            let formData = {
                order_no: $('#txtOrderNo').val().trim(),
                order_date: $('#dtpOrderDate').val().trim(),
                project_id: $('#lstProject').val(),
                req_id: $('#lstPurchaseReq').val(),
                taxable_amount: parseFloat($('#divSubTotal').text().trim()) || 0,
                tax_amount: parseFloat($('#divTaxAmount').text().trim()) || 0,
                total_amount: parseFloat($('#divTotalAmount').text().trim()) || 0,
                // additional_amount: parseFloat($('#txtAdditionalAmount').val().trim()) || 0,
                // net_amount: parseFloat($('#txtNetAmount').val().trim()) || 0,
                is_secondary: 0,
            };

            let ItemData = [];

            $('#tblItems tbody tr').each(function () {
                let item = JSON.parse($(this).find('.itemData').val());
                ItemData.push(item);
            });

            formData.item_data = JSON.stringify(ItemData);

            // Validation
            if (!formData.order_no) {
                $('#txtOrderNo-err').text('Order Number is required');
                status = false;
            }
            if (!formData.order_date) {
                $('#dtpOrderDate-err').text('Order Date is required');
                status = false;
            }
            if (!formData.project_id) {
                $('#lstProject-err').text('Project is required');
                // status = false;
            }
            if (parseFloat(formData.taxable_amount) <= 0) {
                $('#divSubTotal-err').text('Subtotal must be greater than zero');
                status = false;
            }
            if (parseFloat(formData.tax_amount) < 0) {
                $('#divTaxAmount-err').text('Tax amount cannot be negative');
                status = false;
            }
            if (parseFloat(formData.total_amount) <= 0) {
                $('#divTotalAmount-err').text('Total amount must be greater than zero');
                status = false;
            }
            if (!formData.additional_amount) {
                // $('#txtAdditionalAmount-err').text('Additional amount is required');
                // status = false;
            }
            if (!formData.net_amount) {
                // $('#txtNetAmount-err').text('Net amount is required');
                // status = false;
            }
            if (!formData.is_secondary) {
                // $('#lstSecondaryStatus-err').text('Secondary status is required');
                // status = false;
            }
            if (ItemData.length < 1) {
                toastr.error("Add at least one item", "Failed", { positionClass: "toast-top-right" });
                status = false;
            }

            return { status, formData };
        };


        $(document).on('click', '.btnDeleteItem', function () {
            $(this).closest("tr").remove();
            $('#tblItems tbody tr').each(function(index){
                $(this).find('td:eq(0)').text(index+1);
            });
		});
        $('#btnSave').click(async function(e){
            e.preventDefault();
            let { status, formData }=await ValidateGetData();

            if(status){
                swal({
                    title: "Are you sure?",
                    text: "You want @if(!$purchaseOrder) Save @else Update @endif this Project Specification!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-outline-success",
                    confirmButtonText: "Yes, @if(!$purchaseOrder) Save @else Update @endif it!",
                    closeOnConfirm: false
                }).then(function () {
                    swal.close();
                    btnLoading($('#btnSave'));
                    let postUrl="{{ $purchaseOrder ? route('purchase_orders.update', $purchaseOrder->id) : route('purchase_orders.store') }}";
                    let Type= "{{ $purchaseOrder ? 'PUT' : 'POST' }}";
                    $.ajax({
                        type:Type,
                        url:postUrl,
                        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                        data:formData,
                        success:function(response){
                            document.documentElement.scrollTop = 0;
                            if(response.status==true){

                                @if($purchaseOrder)
                                    window.location.replace("{{route('purchase_orders.index')}}");
                                @else
                                    window.location.reload();
                                @endif

                            }else{
                                toastr.error(response.message, "Failed", { positionClass: "toast-top-right", containerId: "toast-top-right", showMethod: "slideDown", hideMethod: "slideUp", progressBar: !0 })
                                if(response['errors']!=undefined){
                                    $('.errors').html('');
                                    $.each( response['errors'], function( KeyName, KeyValue ) {
                                        var key=KeyName;
                                        if(key=="spec_name"){$('#txtOrderNo-err').html(KeyValue);}
                                    });
                                }
                            }
                        }
                    });
                });
            }
        });

    });
</script>

@endsection

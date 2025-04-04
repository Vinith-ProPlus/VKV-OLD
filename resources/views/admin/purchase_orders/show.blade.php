@extends('layouts.admin')

@section('content')
    @php
        $PageTitle = "Purchase Order";
        $ActiveMenuName = 'Purchase Orders';
        $isEdit = $purchaseOrder && $purchaseOrder->id;
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
                        <h5>Purchase Order Details - {{ $order->order_id }}</h5>
                    </div>

                    <div class="card-body">

        <table class="table table-bordered mt-4">
            <thead>
            <tr>
                <th>Category</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>GST</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->details as $detail)
                <tr id="row-{{ $detail->id }}">
                    <td>{{ $detail->category->name }}</td>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>₹{{ $detail->rate }}</td>
                    <td>
                        @if($detail->gst_applicable)
                            {{ $detail->gst_percentage }}%
                        @else
                            -
                        @endif
                    </td>
                    <td>₹{{ $detail->total_amount_with_gst }}</td>
                    <td><span class="badge bg-{{ $detail->status == 'Delivered' ? 'success' : 'warning' }}">{{ $detail->status }}</span></td>
                    <td>
                        @if($detail->status == 'Pending')
                            <button class="btn btn-sm btn-primary mark-delivered-btn" data-id="{{ $detail->id }}">Mark as Delivered</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deliveryForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="detail_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Mark as Delivered</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Remarks (Optional)</label>
                            <textarea class="form-control" name="remarks"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Upload Image (Optional)</label>
                            <input type="file" class="form-control" name="attachment">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.mark-delivered-btn', function () {
            const id = $(this).data('id');
            $('#detail_id').val(id);
            $('#deliveryModal').modal('show');
        });

        $(document).on('submit', '#deliveryForm', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('purchase-orders.mark-delivered') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res.success) {
                        $('#deliveryModal').modal('hide');
                        const row = $('#row-' + formData.get('id'));
                        row.find('td:nth-child(7)').html('<span class="badge bg-success">Delivered</span>');
                        row.find('td:nth-child(8)').html('');
                    }
                }
            });
        });
    </script>
@endpush

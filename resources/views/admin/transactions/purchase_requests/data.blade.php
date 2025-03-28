@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Create Purchase Request</h2>

    <form action="{{ route('purchase_requests.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="request_number" class="form-label">Request Number</label>
            <input type="text" name="request_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="request_date" class="form-label">Request Date</label>
            <input type="date" name="request_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="requested_by" class="form-label">Requested By</label>
            <input type="text" name="requested_by" class="form-control" required>
        </div>

        <h4>Select Category & Product</h4>
        <div class="mb-3">
            <label for="categorySelect" class="form-label">Product Category</label>
            <select id="categorySelect" class="form-control">
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        
        <h4>Select Product</h4>
        <div class="mb-3">
            <select id="productSelect" class="form-control">
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->unit_price }}">
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
            <button type="button" class="btn btn-success mt-2" id="addProduct">Add Product</button>
        </div>

        <h4>Products</h4>
        <table class="table" id="productsTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#productSelect').select2(); // Apply Select2 for better UX

            $('#addProduct').click(function() {
            
                let selectedProduct = $('#productSelect').find(':selected');
                let productId = selectedProduct.val();
                let productName = selectedProduct.data('name');
                let unitPrice = selectedProduct.data('price');

                if (!productId) {
                    alert("Please select a product!");
                    return;
                }

                // Check if product already exists in table
                if ($(`#productsTable tbody tr[data-id="${productId}"]`).length > 0) {
                    alert("This product is already added!");
                    return;
                }

                let rowIndex = $('#productsTable tbody tr').length;
                let newRow = `
                    <tr data-id="${productId}">
                        <td>${productName}<input type="hidden" name="products[${rowIndex}][id]" value="${productId}"></td>
                        <td><input type="number" name="products[${rowIndex}][quantity]" class="form-control qty" value="1" min="1"></td>
                        <td><input type="number" name="products[${rowIndex}][unit_price]" class="form-control price" value="${unitPrice}" readonly></td>
                        <td><input type="text" name="products[${rowIndex}][total_price]" class="form-control total" value="${unitPrice}" readonly></td>
                        <td><button type="button" class="btn btn-danger remove-row">X</button></td>
                    </tr>
                `;

                $('#productsTable tbody').append(newRow);
                $('#productSelect').val(null).trigger('change'); // Reset dropdown
            });

            // Update total price when quantity changes
            $(document).on('input', '.qty', function() {
                let row = $(this).closest('tr');
                let qty = Math.max(1, parseInt($(this).val())); // Ensure min quantity is 1
                let price = parseFloat(row.find('.price').val());
                row.find('.total').val((qty * price).toFixed(2));
            });

            // Remove row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection

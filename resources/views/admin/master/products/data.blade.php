@extends('layouts.admin')

@section('content')
    @php
        $PageTitle = $product ? 'Edit Product' : 'Create Product';
        $ActiveMenuName = "Product";
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">{{ $PageTitle }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-center">
                <h5>{{ $PageTitle }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $product ? route('products.update', $product->id) : route('products.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if($product) @method('PUT') @endisset

                    <div class="d-flex justify-content-center align-items-center">
                        <div class="text-center">
                            <label class="d-block">Product Image <span class="text-danger">*</span></label>
                            <div id="image-dropzone" class="image-box border rounded d-flex align-items-center justify-content-center flex-column text-center"
                                 style="width: 200px; height: 200px; cursor: pointer; background: #f8f9fa; border: 2px dashed #ccc;">
                                <i class="fa fa-upload fa-2x text-secondary"></i>
                                <p class="text-muted m-0">Drag &amp; drop a file here or click</p>
                                <img id="image-preview" src="" class="img-fluid d-none" style="max-width: 100%; max-height: 100%;" alt="">
                            </div>
                            <input type="file" id="image-input" name="image" class="d-none" accept="image/*">
                        </div>
                    </div>
                    <div class="row mt-15">
                        <div class="col-md-6">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
                            @error('name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="code">Product Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $product->code ?? '') }}" required>
                            @error('code')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-15">
                            <label for="category_id">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control select2" required></select>
                            @error('category_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-15">
                            <label for="tax_id">Tax <span class="text-danger">*</span></label>
                            <select name="tax_id" id="tax_id" class="form-control select2" required></select>
                            @error('tax_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-15">
                            <label for="uom_id">Unit of Measurement <span class="text-danger">*</span></label>
                            <select name="uom_id" id="uom_id" class="form-control select2" required></select>
                            @error('uom_id')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-15">
                            <label for="is_active">Status</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="1" {{ $product && $product->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $product && !$product->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-15 text-end">
                        <div>
                            <a href="javascript:void(0)" onclick="window.history.back()" type="button" class="btn btn-warning">Back</a>
                            @if(!$product)
                                @can('Create Product')
                                    <button type="submit" class="btn btn-primary">Save</button>
                                @endcan
                            @else
                                @can('Edit Product')
                                    <button type="submit" class="btn btn-primary">Update</button>
                                @endcan
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crop Image</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <div class="img-container">
                        <img id="cropper-image" style="max-width: 100%;" alt="" src="">
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" id="rotateLeft"><i class="fa fa-undo"></i></button>
                        <button type="button" class="btn btn-secondary btn-sm" id="rotateRight"><i class="fa fa-redo"></i></button>
                        <button type="button" class="btn btn-secondary btn-sm" id="flipHorizontal"><i class="fa fa-arrows-alt-h"></i></button>
                        <button type="button" class="btn btn-secondary btn-sm" id="flipVertical"><i class="fa fa-arrows-alt-v"></i></button>
{{--                        <button type="button" class="btn btn-secondary btn-sm" id="uploadNewImage"><i class="fa fa-upload"></i></button>--}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" id="cropImage">Crop</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- Include Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <script>
        $(document).ready(function () {
            let cropper;
            let selectedFile;

            function updatePreview(url) {
                $("#image-preview").removeClass("d-none").attr("src", url);
                $("#image-dropzone i, #image-dropzone p").hide(); // Hide text & icon
            }

            @if($product && $product->image)
                updatePreview("{{ Storage::url($product->image) }}");
            @endif

            function resetPreview() {
                $("#image-preview").addClass("d-none").attr("src", "");
                $("#image-dropzone i, #image-dropzone p").show(); // Show text & icon again
            }

            // Click to open file input
            $("#image-dropzone").click(function () {
                $("#image-input").click();
            });

            // Drag & drop functionality
            $("#image-dropzone").on("dragover", function (e) {
                e.preventDefault();
                $(this).css("border-color", "#007bff");
            }).on("dragleave", function () {
                $(this).css("border-color", "#ccc");
            }).on("drop", function (e) {
                e.preventDefault();
                let files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    $("#image-input")[0].files = files;
                    handleFileSelect(files[0]);
                }
            });

            function handleFileSelect(file) {
                if (!file) return;

                selectedFile = file;
                let reader = new FileReader();
                reader.onload = function (e) {
                    $("#cropper-image").attr("src", e.target.result);
                    $("#cropperModal").modal("show");
                };
                reader.readAsDataURL(file);
            }

            // When modal opens, initialize cropper
            $("#cropperModal").on("shown.bs.modal", function () {
                let image = document.getElementById("cropper-image");
                if (cropper) cropper.destroy(); // Ensure cropper resets
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                });
            });

            // Rotate and flip actions
            $("#rotateLeft").click(() => cropper.rotate(-90));
            $("#rotateRight").click(() => cropper.rotate(90));
            $("#flipHorizontal").click(() => cropper.scaleX(-cropper.getData().scaleX || -1));
            $("#flipVertical").click(() => cropper.scaleY(-cropper.getData().scaleY || -1));

            // Upload new image inside modal
            $("#uploadNewImage").click(function () {
                $("#image-input").val(""); // Clear previous selection
                $("#image-input").trigger("click"); // Open file selection
            });

            // Ensure the new file gets handled properly
            $("#image-input").off("change").on("change", function (event) {
                let files = event.target.files;
                if (files && files.length > 0) {
                    handleFileSelect(files[0]);
                }
            });

            // Crop and update preview
            $("#cropImage").click(function () {
                let canvas = cropper.getCroppedCanvas();
                canvas.toBlob((blob) => {
                    let url = URL.createObjectURL(blob);
                    updatePreview(url);
                    $("#cropperModal").modal("hide");
                });
            });

            // Destroy cropper on modal close
            $("#cropperModal").on("hidden.bs.modal", function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });

            // Reset image when clicking outside
            $("#image-dropzone").dblclick(function () {
                resetPreview();
                $("#image-input").val(""); // Reset file input
            });

            $('.select2').select2({ width: '100%' });

            function loadOptions(url, elementId, selectedValue) {
                $.get(url, function (response) {
                    console.log(response);
                    let options = '<option value="">Select</option>';
                    response.forEach(item => {
                        options += `<option value="${item.id}" ${selectedValue == item.id ? 'selected' : ''}>${item.name}</option>`;
                    });
                    $(elementId).html(options);
                });
            }

            loadOptions('{{ route("categories.list") }}', '#category_id', '{{ $product->category_id ?? "" }}');
            loadOptions('{{ route("taxes.list") }}', '#tax_id', '{{ $product->tax_id ?? "" }}');
            loadOptions('{{ route("uoms.list") }}', '#uom_id', '{{ $product->uom_id ?? "" }}');
        });

    </script>
@endsection

@extends('layouts.admin')

@section('content')
    @php
        $PageTitle = "Project";
        $ActiveMenuName = 'Projects';
    @endphp
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    </head>
    <style>
        .wizard-head{
            /* pointer-events: none !important; */
            margin-bottom: 30px !important;
            justify-content: space-evenly;
        }
        .wizard-head a {
            text-decoration: none;
        }
        .wizard-head a .nav-contents {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        .wizard-head .nav-title{
            color: #c0c0c0;
        }
        .wizard-head .nav-link.active .nav-title{
            color: #545454;
        }
        .wizard-head .nav-link i{
            font-size: 32px;
            color: #d8d6ff;
        }
        .wizard-head .nav-link.active i{
            color:  #7167f4 !important;
        }
        .nav-tabs {
            border-bottom: none !important;
        }
        .wizard-head .nav-link {
            border: none !important;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .preview-item {
            position: relative;
            width: 150px;
            background: #f8f9fa;
            border: 2px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin: 15px;

        }
        .preview-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }
        .preview-document {
            width: 100%;
            height: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 4px;
        }
        .preview-document i {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .file-name {
            font-size: 12px;
            text-align: center;
            word-break: break-word;
            margin-top: 5px;
            color: #333;
        }
        .file-size {
            font-size: 11px;
            color: #666;
            text-align: center;
            margin-top: 3px;
        }
        .dropify-wrapper {
            /* margin-bottom: 20px; */
        }
        .remove-file,.remove-modal-file,.btnDeleteDocumentFile, .btnDeleteDocArray{
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
    </style>
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="f-16 fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">{{$PageTitle}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h5>{{ $project ? 'Edit' : 'Create' }} {{$PageTitle}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="nav nav-tabs wizard-head" id="projectTabs">
                            <a class="nav-link active" href="#project-details" data-tab="project-details" data-name="tab-project-details"  data-bs-toggle="tab">
                                <span class="nav-contents">
                                    <i class="bi bi-1-square-fill active"></i>
                                    <span class="nav-title">Project Details</span>
                                </span>
                            </a>
                            <a class="nav-link" href="#project-stages" data-tab="project-stages" data-name="tab-project-stages" data-bs-toggle="tab">
                                <span class="nav-contents">
                                    <i class="bi bi-2-square-fill"></i>
                                    <span class="nav-title">Project Stages</span>
                                </span>
                            </a>
                            <a class="nav-link" href="#project-documents" data-tab="project-documents" data-name="tab-project-documents" data-bs-toggle="tab">
                                <span class="nav-contents">
                                    <i class="bi bi-3-square-fill"></i>
                                    <span class="nav-title">Project Documents</span>
                                </span>
                            </a>
                        </div>

                        <form id="project-form" action="{{ $project ? route('projects.update', $project->id) : route('projects.store') }}" method="POST">
                            @csrf
                            @if($project)
                                @method('PUT')
                            @endif
                        <div class="tab-content mt-3">
                            <!-- Project Details Tab -->
                            <div class="tab-pane fade show active" id="project-details">


                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="site_id">Site Name <span class="text-danger">*</span></label>
                                                <select name="site_id" id="site_id" class="form-control select2" data-selected="{{ old('site_id', $project->site_id ?? '') }}" required></select>
                                                @error('site_id')
                                                <span class="text-danger mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="engineer_id">Engineer Name <span class="text-danger">*</span></label>
                                                <select name="engineer_id" id="engineer_id" class="form-control select2" data-selected="{{ old('engineer_id', $project->engineer_id ?? '') }}" required></select>
                                                @error('engineer_id')
                                                <span class="text-danger mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-10">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Project ID <span class="text-danger">*</span></label>
                                                <input type="text" name="project_id" class="form-control" value="{{ old('project_id', $project->project_id ?? '') }}" required>
                                                @error('project_id')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Project Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $project->name ?? '') }}" required>
                                                @error('name')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-10">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Location <span class="text-danger">*</span></label>
                                                <input type="text" name="location" class="form-control" value="{{ old('location', $project->location ?? '') }}" required>
                                                @error('location')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Type <span class="text-danger">*</span></label>
                                                <input type="text" name="type" class="form-control"
                                                       value="{{ old('type', $project->type ?? '') }}" required>
                                                @error('type')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-10">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Units <span class="text-danger">*</span></label>
                                                <input type="number" name="units" class="form-control"
                                                       value="{{ old('units', $project->units ?? '') }}" required>
                                                @error('units')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Target Customers <span class="text-danger">*</span></label>
                                                <input type="text" name="target_customers" class="form-control"
                                                       value="{{ old('target_customers', $project->target_customers ?? '') }}" required>
                                                @error('target_customers')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-10">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Range <span class="text-danger">*</span></label>
                                                <input type="text" name="range" class="form-control"
                                                       value="{{ old('range', $project->range ?? '') }}" required>
                                                @error('range')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Active Status <span class="text-danger">*</span></label>
                                                <select name="is_active" class="form-control">
                                                    <option value="1" {{ old('is_active', $project->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('is_active', $project->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                @error('is_active')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-40 text-end">
                                        <div class="col-6 text-start">
                                            <a class="btn btn-light">Back</a>
                                        </div>
                                        <div class="col-6 text-end">
                                            <a class="btn btn-primary btn-next">Next</a>
                                        </div>
                                        {{-- <div>
                                            <a href="javascript:void(0)" onclick="window.history.back()" class="btn btn-warning">Back</a>
                                            <button type="submit" class="btn btn-primary">{{ $project ? 'Update' : 'Save' }}</button>
                                        </div> --}}
                                    </div>
                            </div>

                            <div class="tab-pane fade" id="project-stages">
                                <!-- Stages Management -->
                                <div class="row col-12 mt-10 card form-group">
                                    <label>Project Stages</label>
                                    <div class="input-group">
                                        <input type="text" id="stage-name" class="form-control" placeholder="Enter stage name">
                                        <button type="button" class="btn btn-primary" id="add-stage-btn">+</button>
                                    </div>
                                    @error('stages')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                    <ul id="stage-list" class="list-group mt-2 ml-10">
                                        @php
                                            $stages = old('stages', $project ? $project->stages()->withTrashed()->orderBy('order_no')->get()->toArray() : []);
                                        @endphp

                                        @foreach($stages as $index => $stage)
                                            @php
                                                $isDeleted = $stage['deleted'] ?? (!empty($stage['deleted_at']) ? 1 : 0);
                                                $stageId = $stage['id'] ?? '';
                                                $stageName = $stage['name'] ?? '';
                                                $stageNo = $stage['order_no'] ?? '';
                                            @endphp

                                            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $stageId }}">
                                                <span class="stage-name">{{ $stageName }}</span>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-warning edit-stage">Edit</button>
                                                    <button type="button" class="btn btn-sm {{ $isDeleted ? 'btn-success restore-stage' : 'btn-danger delete-stage' }}">
                                                        {{ $isDeleted ? 'Restore' : 'Delete' }}
                                                    </button>
                                                    <input type="hidden" name="stages[{{ $index }}][id]" value="{{ $stageId }}">
                                                    <input type="hidden" name="stages[{{ $index }}][name]" value="{{ $stageName }}">
                                                    <input type="hidden" name="stages[{{ $index }}][order_no]" class="order-no" value="{{ $stageNo }}">
                                                    <input type="hidden" name="stages[{{ $index }}][deleted]" value="{{ $isDeleted }}">
                                                </div>
                                            </li>
                                            <!-- Individual Stage Error Display -->
                                            @error("stages.{$index}.name")
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="row mt-40 text-end">
                                    <div class="col-6 text-start">
                                        <a class="btn btn-light">Back</a>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a class="btn btn-outline-light btn-prev">Previous</a>
                                        <a class="btn btn-primary btn-next">Next</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Project Documents Tab -->
                            <div class="tab-pane fade" id="project-documents">
                                {{-- <form action="{{ route('uploadDocuments') }}" method="POST" class="dropzone" id="document-upload">
                                    @csrf
                                    <input type="hidden" name="module_name" value="{{ 'Project' ?? 'user_project' }}">
                                    <input type="hidden" name="module_id" value="{{ $project->id ?? auth()->user()->id }}">
                                </form> --}}

                                {{-- <div class="mt-3">
                                    <h6>Uploaded Documents</h6>
                                    <ul id="uploaded-documents" class="list-group">
                                        @foreach($project->documents ?? [] as $document)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="{{ asset($document->file_path) }}" target="_blank">{{ $document->file_name }}</a>
                                                <button type="button" class="btn btn-danger btn-sm delete-doc" data-id="{{ $document->id }}">Delete</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div> --}}

                                <table class="table table-responsive table-hover mb-30 d-none">
                                    <thead class="bg-light">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                    <tbody id="tblProjectDocuments"></tbody>
                                    </thead>
                                </table>

                                <div class="text-center">
                                    <a class="btn btn-outline-primary btnAddDocs" type="button" data-bs-toggle="modal" data-bs-target=".mdlDocument">
                                        Add Documents
                                    </a>
                                </div>

                                <div class="modal fade mdlDocument" tabindex="-1" aria-labelledby="myExtraLargeModal" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myExtraLargeModal">Document Details</h4>
                                                <button id="btnCloseDocumentModal" class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body dark-modal">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label for="txtTitle">Title <span class="required">*</span></label>
                                                        <input type="text" id="txtTitle" class="form-control">
                                                        <span class="errors err-sm" id="txtTitle-err"></span>
                                                    </div>
                                                    <div class="col-12 mt-20">
                                                        <label for="txtDescription">Description</label>
                                                        <textarea id="txtDescription" cols="30" class="form-control"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 mt-20 divDocument" id="divDocument">
                                                        <label for="multipleImageDocument">Documents <span class="required">*</span></label>
                                                        <input type="file" class="dropify multipleImageDocument" id="multipleImageDocument"
                                                               data-allowed-file-extensions="png jpg jpeg gif pdf doc docx xls xlsx txt"
                                                               data-height="100" readonly>
                                                        <div class="filterInfos d-none">
                                                            <div class="upload-status mb-2" id="divDocumentCount"></div>
                                                            <div class="row justify-content-between filters">
                                                                <div class="col-6">
                                                                    <button class="filter-btn active" data-filter="all">All Files</button>
                                                                    <button class="filter-btn" data-filter="image">Images</button>
                                                                    <button class="filter-btn" data-filter="document">Documents</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="errors err-sm" id="txtDocumentFile-err"></span>
                                                        <div class="preview-container document-preview-container"></div>
                                                    </div>
                                                </div>

                                                <div class="row justify-content-end mt-20">
                                                    <div class="col-auto text-end">
                                                        <button type="button" class="btn btn-warning d-none" id="btnUpdateDocument">Update</button>
                                                        <button type="button" class="btn btn-primary" id="btnSaveDocument">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-40 text-end">
                                    <div class="col-6 text-start">
                                        <a class="btn btn-light">Back</a>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a class="btn btn-outline-light btn-prev">Previous</a>
                                        <a class="btn btn-primary" onclick="$('#project-form').submit()">Submit</a>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Tab Content -->
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

            //-------------------start of wizard tab toggle function

            $('.btn-prev').on('click',function(){
                changePage(0,$(this).closest('.tab-pane').attr('id'));
            });
            $('.btn-next').on('click',function(){
                changePage(1,$(this).closest('.tab-pane').attr('id'));
            });

            const changePage = (flag,id) =>{
                let tab = $(`a[data-name='tab-${id}']`);

                if(flag){
                    let nextTab = tab.next();

                    if (nextTab.length) {
                        tab.removeClass('active');
                        nextTab.addClass('active');

                        $(`.tab-pane[id='${tab.attr("data-tab")}']`).removeClass('show active');
                        $(`.tab-pane[id='${nextTab.attr("data-tab")}']`).addClass('show active');
                    }
                }else{
                    let prevTab = tab.prev();

                    if (prevTab.length) {
                        tab.removeClass('active');
                        prevTab.addClass('active');

                        $(`.tab-pane[id='${tab.attr("data-tab")}']`).removeClass('show active');
                        $(`.tab-pane[id='${prevTab.attr("data-tab")}']`).addClass('show active');
                    }
                }
            }

            //-------------------------------end of wizard tab toggle function

            //-------------------------------Document image handler

            function clearDropify(inputSelector) {
                let dropifyInstance = $(inputSelector).dropify();
                dropifyInstance = dropifyInstance.data('dropify');

                if (dropifyInstance) {
                    dropifyInstance.clearElement();
                }
            }

            $('.multipleImageDocument').on('input', function (event) {
                let title = $('#txtTitle').val();
                let description = $('#txtDescription').val();
                if(title){

                    let files = event.target.files;

                    if (files.length === 0) {
                        return;
                    }

                    let formData = new FormData();
                    $.each(files, function (i, file) {
                        formData.append('images[]', file);
                    });

                    formData.append('title', title);
                    formData.append('description', description);
                    formData.append('module_name', '{{$PageTitle}}');

                    let csrfToken = $('meta[name="csrf-token"]').attr('content');
                    formData.append('_token', csrfToken);

                    $.ajax({
                        url: "{{ route('projects.handle_documents') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            if (response.success) {
                                updateDocumentPreview(response);
                            } else {
                                handleUploadError(response);
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Upload failed';

                            try {
                                let errorResponse = JSON.parse(xhr.responseText);
                                errorMessage = errorResponse.message || errorMessage;
                            } catch (e) {
                                errorMessage = xhr.statusText || errorMessage;
                            }

                            console.error("Upload error:", errorMessage);
                        }
                    });

                    $('#txtTitle-err').text('');
                    clearDropify('#multipleImageDocument');
                }else{
                    $('#txtTitle-err').text('Title is required !');
                }

                $('#multipleImageDocument').closest('.dropify-wrapper').find('.dropify-preview').addClass('d-none');
            });

            function handleUploadError(response) {
                let errorMessage = 'Upload failed';

                if (response.errors) {
                    // Handle validation errors
                    let errorList = Object.values(response.errors).flat();
                    errorMessage = errorList.join('\n');
                } else if (response.message) {
                    errorMessage = response.message;
                }

                console.log(errorMessage);
            }

            function updateDocumentPreview(response) {

                if (response.files && response.files.length > 0) {
                    let previewContainer = $('.preview-container');
                    let filePath;

                    const allowedImageExtensions = ["jpeg", "jpg", "png", "webp"];

                    response.files.forEach(function(file) {

                        filePath = file.path;
                        let extension = filePath.split('.').pop().toLowerCase();
                        let docImg = filePath;
                        if (!allowedImageExtensions.includes(extension)) {
                            docImg = "/storage/essentials/doc.png";
                        }

                        let previewHtml = `
                            <div class="col-md-3 col-6 img-hover preview-item light-card hover-1" data-url="${file.path}" data-ext="jpeg" data-filename="${file.name}">
                                <button data-id="${file.id}" class="doc-rem remove-file btnDeleteDocumentFile">x</button>
                                <a href="${file.path}" data-lightbox="image-gallery" data-title="${file.name}" class="gallery-item">
                                    <div class="gallery-img-wrap">
                                        <img src="${docImg}" class="preview-image" data-title="${file.name}" alt="${file.name}">
                                    </div>
                                </a>
                                <div class="file-name">${file.name}</div>
                                <div class="text-center">
                                    <a href="${file.path}" download="${file.name}" class="btn btn-sm btn-primary mt-2">Download</a>
                                </div>
                            </div>
                        `;

                        previewContainer.append(previewHtml);
                    });
                }
            }

            $('.preview-container').on('click', '.remove-file', function() {
                $(this).closest('.preview-item').remove();
            });

            $(document).on('click', '.remove-file', function(e) {
                e.preventDefault();

                let id = $(this).attr('data-id');

                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('projects.delete_documents') }}",
                    type: "DELETE",
                    data: JSON.stringify({ id: [id] }),
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                    },
                    error: function(xhr) {
                        let errorMessage = 'Upload failed';

                        try {
                            let errorResponse = JSON.parse(xhr.responseText);
                            errorMessage = errorResponse.message || errorMessage;
                        } catch (e) {
                            errorMessage = xhr.statusText || errorMessage;
                        }

                        console.error("Upload error:", errorMessage);
                    }
                });
            });

            //-----------------------------end of Document image handlers

            //---------------------------Document image table handlers

            $('.btnAddDocs').on('click', function () {
                $('#btnSaveDocument').removeClass('d-none');
                $('#btnUpdateDocument').addClass('d-none');
                clearModal();
            });

            $('#btnSaveDocument').on('click', function () {
                let title = $('#txtTitle').val();
                let description = $('#txtDescription').val();

                let images = [];

                $('.preview-container .preview-item').each(function () {
                    let fileName = $(this).data('filename');
                    let fileUrl = $(this).data('url');
                    let fileId = $(this).find('.doc-rem').data('id');

                    if (fileName && fileUrl) {
                        images.push({
                            fileId:fileId,
                            filename: fileName,
                            url: fileUrl
                        });
                    }
                });

                const obj = {
                    'title':title,
                    'description':description,
                    'images':images
                }

                let html = `
                    <tr data-edit="${tableEditId++}">
                        <td data="serial">*</td>
                        <td data="title">${title}</td>
                        <td data="description">${description ? description : '-' }</td>
                        <td>
                            <a class="btn btn-warning editDocuments"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-outline-danger deleteDocuments"><i class="fa fa-trash"></i></a>
                        </td>
                        <td data="tdata" class="d-none tdata">${JSON.stringify(obj)}</td>
                    </tr>`;

                $('#tblProjectDocuments').append(html).closest('table').removeClass('d-none');
                serialize('#tblProjectDocuments');
                $('.mdlDocument').modal('hide');
                clearModal();
            });

            let updatingRowId;

            $(document).on('click', '.editDocuments', function () {

                clearModal();

                updatingRowId = $(this).closest('tr').attr('data-edit');

                let tData = JSON.parse($(this).closest('tr').find('td').last().text());

                $('#txtTitle').val(tData.title);
                $('#txtDescription').val(tData.description);

                let previewContainer = $('.preview-container');
                let previewHtml = '';
                const allowedImageExtensions = ["jpeg", "jpg", "png", "webp"];
                let filePath;

                if (Array.isArray(tData.images)) {
                    tData.images.forEach(image => {
                        filePath = image.url;
                        let extension = filePath.split('.').pop().toLowerCase();
                        let docImg = filePath;
                        if (!allowedImageExtensions.includes(extension)) {
                            docImg = "/storage/essentials/doc.png";
                        }

                        previewHtml += `
                            <div class="col-md-3 col-6 img-hover preview-item light-card hover-1" data-url="${image.url}" data-ext="jpeg"  data-filename="${image.filename}">
                                <button data-id="${image.fileId}" class="doc-rem btnDeleteDocArray">x</button>
                                <a href="${image.url}" data-lightbox="image-gallery" data-title="${image.filename}" class="gallery-item">
                                        <div class="gallery-img-wrap">
                                            <img src="${docImg}" class="preview-image" data-title="${image.filename}" alt="${image.filename}">
                                        </div>
                                    </a>
                                <div class="file-name">${image.filename}</div>
                                <div class="text-center">
                                    <a href="${image.url}" download="${image.filename}" class="btn btn-sm btn-primary mt-2">Download</a>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    console.error("tData.images is not an array or is undefined:", tData.images);
                }

                previewContainer.append(previewHtml);

                $('#btnUpdateDocument').removeClass('d-none');
                $('#btnSaveDocument').addClass('d-none');

                $('.mdlDocument').modal('show');

                deletedDocuments = [];
            });

            $('#btnUpdateDocument').on('click',function(){

                let title = $('#txtTitle').val();
                let description = $('#txtDescription').val();

                let images = [];

                $('.preview-container .preview-item').each(function () {
                    let fileName = $(this).data('filename');
                    let fileUrl = $(this).data('url');
                    let fileId = $(this).find('.doc-rem').data('id');

                    if (fileName && fileUrl) {
                        images.push({
                            fileId:fileId,
                            filename: fileName,
                            url: fileUrl
                        });
                    }
                });

                const obj = {
                    'title':title,
                    'description':description,
                    'images':images
                }

                if(deletedDocuments.length > 0){
                    deleteByImageIds(deletedDocuments);
                }

                $('#tblProjectDocuments').find(`tr[data-edit="${updatingRowId}"]`).each(function () {
                    let row = $(this);

                    row.find('td[data="title"]').text(title);
                    row.find('td[data="description"]').text(description ? description : '-');
                    row.find('td[data="tdata"]').text(JSON.stringify(obj));
                });

                $('.mdlDocument').modal('hide');
                $('#btnSaveDocument').removeClass('d-none');
                $('#btnUpdateDocument').addClass('d-none');

                clearModal();
            });

            $(document).on('click', '.btnDeleteDocArray', function () {
                $(this).closest('.preview-item').remove();
                deletedDocuments.push($(this).attr('data-id'));
            });

            $(document).on('click', '.deleteDocuments', function () {

                let row = $(this).closest('tr');
                let tData = JSON.parse(row.find('td[data="tdata"]').text());
                let imageID = [];

                if (Array.isArray(tData.images)) {
                    tData.images.forEach(image => {
                        imageID.push(image.fileId);
                    });
                }

                deleteByImageIds(imageID);

                row.remove();

                let flag = $('#tblProjectDocuments tr').length;

                if(!flag) $('#tblProjectDocuments').closest('table').addClass('d-none');

                serialize('#tblProjectDocuments');

                $('#btnSaveDocument').removeClass('d-none');
                $('#btnUpdateDocument').addClass('d-none');

                clearModal();
            });

            const deleteByImageIds = (id) => {

                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('projects.delete_documents') }}",
                    type: "DELETE",
                    data: JSON.stringify({ id: id }),
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                    },
                    error: function(xhr) {
                        let errorMessage = 'Upload failed';

                        try {
                            let errorResponse = JSON.parse(xhr.responseText);
                            errorMessage = errorResponse.message || errorMessage;
                        } catch (e) {
                            errorMessage = xhr.statusText || errorMessage;
                        }

                        console.error("Upload error:", errorMessage);
                    }
                });
            }

            //---------------------------End of Document image table handlers

            //--------------------------Document handling helpers

            const serialize = (selector) => {
                let serialNum = 1;
                $(`${selector} tr`).each(function () {
                    $(this).find('td[data="serial"]').text(serialNum++);
                });
            };

            const clearModal = () => {
                $('#txtTitle').val('');
                $('#txtDescription').val('');
                $('.preview-item').remove();
            }

            //----------------------------End of Document handling helpers

            let stageList = $("#stage-list");
            let isEditMode = {{ $project ? 'true' : 'false' }};

            // Enable sorting and update order_no on change
            stageList.sortable({
                update: function () {
                    updateOrderNumbers();
                }
            });

            // Add Stage Function
            $("#add-stage-btn").on("click", function () {
                let stageName = $("#stage-name").val().trim();
                if (stageName === "") {
                    alert("Stage name cannot be empty!");
                    return;
                }

                if (stageExists(stageName)) {
                    alert("Stage already exists!");
                    return;
                }

                let index = stageList.children().length;
                let newStage = `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="stage-name">${stageName}</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-warning edit-stage">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger delete-stage">Delete</button>
                                <input type="hidden" name="stages[${index}][name]" value="${stageName}">
                                <input type="hidden" name="stages[${index}][deleted]" value="0">
                                <input type="hidden" name="stages[${index}][order_no]" value="${index + 1}">
                            </div>
                        </li>
                    `;
                stageList.append(newStage);
                $("#stage-name").val(""); // Clear input
                updateOrderNumbers();
            });

            // Edit Stage
            stageList.on("click", ".edit-stage", function () {
                let stageItem = $(this).closest("li");
                let name = stageItem.find(".stage-name").text();
                let newName = prompt("Edit Stage Name:", name);
                if (newName && !stageExists(newName)) {
                    stageItem.find(".stage-name").text(newName);
                    stageItem.find("input[name*='[name]']").val(newName);
                } else if (stageExists(newName)) {
                    alert("Stage already exists!");
                }
            });

            // Delete Stage (Soft Delete)
            stageList.on("click", ".delete-stage", function () {
                let stageItem = $(this).closest("li");
                if(isEditMode) {
                    stageItem.find("input[name*='[deleted]']").val(1);
                    $(this).replaceWith('<button type="button" class="btn btn-sm btn-success restore-stage">Restore</button>');
                } else {
                    stageItem.remove();
                }
            });

            // Restore Deleted Stage
            stageList.on("click", ".restore-stage", function () {
                let stageItem = $(this).closest("li");
                stageItem.find("input[name*='[deleted]']").val(0);
                $(this).replaceWith('<button type="button" class="btn btn-sm btn-danger delete-stage">Delete</button>');
            });

            // Function to check if stage already exists
            function stageExists(name) {
                let exists = false;
                $(".stage-name").each(function () {
                    if ($(this).text().toLowerCase() === name.toLowerCase()) {
                        exists = true;
                        return false;
                    }
                });
                return exists;
            }

            // Update Order Numbers after sorting
            function updateOrderNumbers() {
                stageList.children("li").each(function (index) {
                    $(this).find("input[name*='[order_no]']").val(index + 1);
                });
            }
            const getSites = () =>{
                let SiteID = $('#site_id');
                let SelectedSiteID = SiteID.attr('data-selected');
                SiteID.select2('destroy');
                $('#site_id option').remove();
                SiteID.append('<option value="">--Select a Site--</option>');

                $.ajax({
                    url:"{{route('getSites')}}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        response.forEach(function(item) {
                            if ((item.id == SelectedSiteID)) {
                                SiteID.append('<option selected value="' + item.id
                                    + '">' + item.name + '</option>');
                            } else {
                                SiteID.append('<option value="' + item.id
                                    + '">'  + item.name + '</option>');
                            }
                        });
                        SiteID.select2();
                    },
                    error: function(xhr) {}
                });
            }
            const getEngineers = () =>{
                let EngineerID = $('#engineer_id');
                let SelectedEngineerID = EngineerID.attr('data-selected');
                EngineerID.select2('destroy');
                $('#engineer_id option').remove();
                EngineerID.append('<option value="">--Select a Engineer--</option>');

                $.ajax({
                    url:"{{route('getEngineers')}}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        response.forEach(function(item) {
                            if ((item.id == SelectedEngineerID)) {
                                EngineerID.append('<option selected value="' + item.id
                                    + '">' + item.name + '</option>');
                            } else {
                                EngineerID.append('<option value="' + item.id
                                    + '">'  + item.name + '</option>');
                            }
                        });
                        EngineerID.select2();
                    },
                    error: function(xhr) {}
                });
            }

            getSites();
            getEngineers();

            Dropzone.options.documentUpload = {
                paramName: "file",
                maxFilesize: 5,
                acceptedFiles: ".pdf,.doc,.docx,.png,.jpg,.jpeg",
                success: function (file, response) {
                    debugger
                    let fileLink = `<li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="${response.file_path}" target="_blank">${response.file_name}</a>
                        <button type="button" class="btn btn-danger btn-sm delete-doc" data-id="${response.id}">Delete</button>
                    </li>`;
                    $("#uploaded-documents").append(fileLink);
                }
            };

            $("#uploaded-documents").on("click", ".delete-doc", function () {
                let document_id = $(this).data("id");
                let $this = $(this);
                $.ajax({
                    url: "{{ route('deleteDocument') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: document_id
                    },
                    success: function () {
                        $this.closest("li").remove();
                    }
                });
            });

        });
    </script>
@endsection

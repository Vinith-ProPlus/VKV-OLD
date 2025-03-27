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
                        

                        <div class="tab-content mt-3">
                            <!-- Project Details Tab -->
                            <div class="tab-pane fade show active" id="project-details">
                                <form action="{{ $project ? route('projects.update', $project->id) : route('projects.store') }}" method="POST">
                                    @csrf
                                    @if($project)
                                        @method('PUT')
                                    @endif

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
                                </form>
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

                                <div class="text-center">
                                    <a class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target=".mdlDocument">
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
                                                            data-height="100" multiple readonly>
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
                                                        <button class="btn btn-primary" id="btnSaveDocument">Save</button>
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
                                        <a class="btn btn-primary">Submit</a>
                                    </div> 
                                </div>
                            </div>
                        </div> <!-- End Tab Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        
        $(document).ready(function () {

            //start of wizard tab toggle function
            
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

            // end of wizard tab toggle function

            // Document image handler
            $('.multipleImageDocument').on('change', function (event) {
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
                            'Accept': 'application/json' // Explicitly request JSON
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log("Documents uploaded successfully", response);
                                updateDocumentPreview(response);
                            } else {
                                handleUploadError(response);
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Upload failed';
                            
                            // Try to parse error response
                            try {
                                let errorResponse = JSON.parse(xhr.responseText);
                                errorMessage = errorResponse.message || errorMessage;
                            } catch (e) {
                                // If parsing fails, use default or status text
                                errorMessage = xhr.statusText || errorMessage;
                            }

                            console.error("Upload error:", errorMessage);
                            
                            // Show user-friendly error
                            console.log(errorMessage);
                        }
                    });
                }else{
                    console.log('title is required!');
                }
            });

            function handleUploadError(response) {
                let errorMessage = 'Upload failed';
                
                // Check for specific error types
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
                    previewContainer.empty();

                    response.files.forEach(function(file) {
                        let fileType = getFileType(file.extension);
                        let previewHtml = `
                            <div class="preview-item ${fileType}" data-filename="${file.name}">
                                <img src="${file.path}" height="100" width="100"> 
                                <span class="file-name">${file.name}</span>
                                <button data-id="${file.id}" class="remove-file">×</button>
                            </div>
                        `;
                        previewContainer.append(previewHtml);
                    });
                }
            }

            function getFileType(extension) {
                const imageExtensions = ['png', 'jpg', 'jpeg', 'gif'];
                const documentExtensions = ['pdf', 'doc', 'docx', 'txt'];
                const spreadsheetExtensions = ['xls', 'xlsx'];

                if (imageExtensions.includes(extension.toLowerCase())) return 'image';
                if (documentExtensions.includes(extension.toLowerCase())) return 'document';
                if (spreadsheetExtensions.includes(extension.toLowerCase())) return 'spreadsheet';
                return 'unknown';
            }
 
            $('.preview-container').on('click', '.remove-file', function() {
                $(this).closest('.preview-item').remove();
            }); 


            $(document).on('click','.remove-file',function(e){
                e.preventDefault();
                let fileName = $(this).closest('.preview-item').data('filename');
                let id = $(this).attr('data-id');
                
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                let formData = new FormData();

                    formData.append('_token', csrfToken);
                    formData.append('id', id);
                    formData.append('fileName', fileName);
                    
                    $.ajax({
                        url: "{{ route('projects.delete_documents') }}",
                        type: "DELETE",
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr) {
                            let errorMessage = 'Upload failed';
                            
                            // Try to parse error response
                            try {
                                let errorResponse = JSON.parse(xhr.responseText);
                                errorMessage = errorResponse.message || errorMessage;
                            } catch (e) {
                                // If parsing fails, use default or status text
                                errorMessage = xhr.statusText || errorMessage;
                            }

                            console.error("Upload error:", errorMessage);
                            
                            // Show user-friendly error
                            console.log(errorMessage);
                        }
                    });
            });

            // Document image handler
            
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

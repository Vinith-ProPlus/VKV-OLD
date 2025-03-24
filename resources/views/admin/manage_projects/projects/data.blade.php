@extends('layouts.admin')

@section('content')
    @php
        $PageTitle = "Project";
        $ActiveMenuName = 'Projects';
    @endphp

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
                        <form action="{{ $project ? route('projects.update', $project->id) : route('projects.store') }}" method="POST">
                            @csrf
                            @if($project)
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Project ID</label>
                                        <input type="text" name="project_id" class="form-control"
                                               value="{{ old('project_id', $project->project_id ?? '') }}" required>
                                        @error('project_id')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Project Name</label>
                                        <input type="text" name="name" class="form-control"
                                               value="{{ old('name', $project->name ?? '') }}" required>
                                        @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-10">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" name="location" class="form-control"
                                               value="{{ old('location', $project->location ?? '') }}" required>
                                        @error('location')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Type</label>
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
                                        <label>Units</label>
                                        <input type="number" name="units" class="form-control"
                                               value="{{ old('units', $project->units ?? '') }}" required>
                                        @error('units')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Target Customers</label>
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
                                        <label>Range</label>
                                        <input type="text" name="range" class="form-control"
                                               value="{{ old('range', $project->range ?? '') }}" required>
                                        @error('range')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Active Status</label>
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

                            <div class="row mt-15 text-end">
                                <div>
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="btn btn-warning">Back</a>
                                    <button type="submit" class="btn btn-primary">{{ $project ? 'Update' : 'Save' }}</button>
                                </div>
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
        });
    </script>
@endsection

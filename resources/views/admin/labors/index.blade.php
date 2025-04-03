@extends('layouts.admin')

@section('content')
    @php
        $PageTitle="Labor";
        $ActiveMenuName='Labors';
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i
                                    class="f-16 fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">Manage Projects</li>
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
                            <div class="col-sm-4 my-2"><h5>{{$PageTitle}}</h5></div>
                            <div class="col-sm-4 my-2 text-right text-md-right">
                                @can('Create Labors')
                                    <button class="btn btn-sm btnPrimaryCustomizeBlue btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#selectProjectModal">Create</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table text-center border rounded" id="list_table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>S.No</th>
                                            <th>Project Name</th>
                                            <th>Date</th>
                                            <th>Labor Count</th>
                                            <th>Contract Labor Count</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody class="small">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="selectProjectModal" tabindex="-1" aria-labelledby="selectProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectProjectModalLabel">Select Project and Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="selectProjectForm">
                        <div class="mb-3">
                            <label for="project_id" class="form-label">Project</label>
                            <select class="form-control" id="project_id" name="project_id">
                                <option value="">Select a Project</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" max="{{ \Carbon\Carbon::today()->toDateString() }}">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Proceed</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        @can('View Labors')
            $(function () {
                $('.select2').select2({ dropdownParent: $('#selectProjectModal') });
                $('#list_table').DataTable({
                    "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ],
                    serverSide: true,
                    iDisplayLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    ajax: {
                        url: '{{ route("labors.index") }}',
                        type: 'GET'
                    },
                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'project_name' },
                        { data: 'date' },
                        { data: 'labor_count' },
                        { data: 'contract_labor_count' },
                        { data: 'action', orderable: false, searchable: false }
                    ]
                });
            });
        function getProjects() {
            let ProjectID = $('#project_id');
            let SelectedProject = ProjectID.attr('data-selected');

            $.ajax({
                url: "{{ route('getProjects') }}",
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    let options = '<option value="">Select a Project</option>';
                    response.forEach(item => {
                        options += `<option value="${item.id}" ${item.id == SelectedProject ? 'selected' : ''}>${item.name}</option>`;
                    });
                    ProjectID.html(options).select2({ dropdownParent: $('#selectProjectModal') });
                },
                error: function () {
                    console.error("Error fetching projects.");
                }
            });
        }
        getProjects();

        $('#selectProjectForm').submit(function(e) {
            e.preventDefault();
            let projectId = $('#project_id').val();
            let date = $('#date').val();
            if (projectId && date) {
                window.location.href = '/admin/manage-projects/labors/create?project_id=' + projectId + '&date=' + date;
            } else {
                alert('Fill the required fields!.');
            }
        });
        @endcan
    </script>
@endsection

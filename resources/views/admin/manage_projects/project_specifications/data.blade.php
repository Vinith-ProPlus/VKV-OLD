@extends('layouts.admin')

@section('content')
    <style>
        .nested-1 td { padding-left: 20px !important; background: #f8f9fa; }
        .nested-2 td { padding-left: 40px !important; background: #e9ecef; }
        .nested-3 td { padding-left: 60px !important; background: #dee2e6; }
    </style>
    @php
        $PageTitle="Project Specifications";
        $ActiveMenuName='Project-Specifications';
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
                            <div class="col-sm-4 my-2"><h5>{{ $projectSpecification  ? 'Edit' : 'Create' }} {{$PageTitle}}</h5></div>
                            <div class="col-sm-4 my-2 text-right text-md-right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-sm-12 mt-20">
                                <div class="form-group">
                                    <label class="txtSpecName">Project Specification Name <span class="required"> * </span></label>
                                    <input type="text" class="form-control" id="txtSpecName" value="{{ $projectSpecification->spec_name ?? '' }}">
                                    <div class="errors err-sm" id="txtSpecName-err"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-20">
                                <div class="form-group">
                                    <label class="lstActiveStatus">Active Status</label>
                                    <select class="form-control" id="lstActiveStatus">
                                        <option value="1" {{ $projectSpecification && $projectSpecification->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $projectSpecification && !$projectSpecification->is_active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-20">
                                <div class="form-group">
                                    <label class="txtValues">Project Specification Values <span class="required"> * </span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="txtValues">
                                        <button class="input-group-text btn-outline-primary px-4 position-relative" id="btnAddSpecValue"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="errors err-sm" id="txtValues-err"></div>
                                </div>
                            </div>
                            <div class="col-12 my-2">

                            </div>
                            <div class="col-sm-12 mt-3">
                                <table class="table tblSpecValues" id="tblSpecValues">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th> 
                                            <th class="text-center">Specification Value Name</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($projectSpecification)
                                        @php
                                            $spec_values = json_decode($projectSpecification->spec_values);
                                        @endphp
                                            @foreach($spec_values as $Key=>$row)
                                                <tr>
                                                    <td>{{$Key + 1}}</td>
                                                    <td>{{$row->value_name}}</td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger btnDeleteSpecValue"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>                                                                        
                                </table>
                            </div> --}}

                            <div class="row my-3 justify-content-center">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="spec_name" placeholder="Enter Specification Name">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="description" placeholder="Enter Description">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" id="level1">
                                        <option value="">Select Level 1</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" id="level2">
                                        <option value="">Select Level 2</option>
                                    </select>
                                </div>
                                <div class="col-md-3 my-2">
                                    <button class="btn btn-primary" id="addSpec">Add Specification</button>
                                </div>
                            </div>
                        
                            <table class="table table-bordered mt-3" id="specTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Specification Name</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row mt-15 justify-content-end">
                            <div class="col-md-4 text-end">
                                <a href="javascript:void(0)" onclick="window.history.back()" type="button" class="btn btn-warning">Back</a>
                                @if(!$projectSpecification)
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
        

        const ValuesExistingValidation=async(data)=>{
            $('.errors').html('');
            let status=true;
            let lowerCaseData = data.toLowerCase();
            $('#tblSpecValues tbody tr').each(function(){
                let ExistingValue = $(this).find('td:eq(1)').text().toLowerCase().trim();
                if(ExistingValue == lowerCaseData){
                    $("#txtValues-err").html('This Value exists');status=false;
                    return false;
                }
            });
            if(status==false){$("html, body").animate({ scrollTop: 0 }, "slow");}
            return status;
        }
        const ValidateGetData=async()=>{

            let formData={
                spec_name : $('#txtSpecName').val(),
                is_active : $('#lstActiveStatus').val(),
            };
            let Values = [];

            $('#tblSpecValues tbody tr').each(function(){
                let value_name = $(this).find('td:eq(1)').text().trim();
                let reference = $(this).find('td:eq(2)').text().trim();
                let sub_heading = $(this).find('td:eq(3)').text().trim();
                Values.push({value_name});
            });

            formData.spec_values = JSON.stringify(Values);
            let status = true;

            if (!formData.spec_name) {
                $('#txtSpecName-err').html('Project Specification Name is required');status = false;
            }else if(formData.spec_name<3){
                $('#txtSpecName-err').html('Project Specification Name must be greater than 2 characters');status=false;
            }else if(formData.spec_name>100){
                $('#txtSpecName-err').html('Project Specification Name may not be greater than 100 characters');status=false;
            }

            if (formData.spec_name && formData.spec_values.length < 1) {
                toastr.error("Add a Project Specification Value", "Failed", { positionClass: "toast-top-right", containerId: "toast-top-right", showMethod: "slideDown", hideMethod: "slideUp", progressBar: !0 })
            }
            
            return { status, formData };
        }
        $("#btnAddSpecValue").on("click", async function () {

            let SpecName = $('#txtSpecName').val();
            let Value = $('#txtValues').val();
            let status = await ValuesExistingValidation(Value);
            if (SpecName=="") {
                $('#txtSpecName-err').html('Project Specification Name is required');status = false;
            }else if(SpecName.length<3){
                $('#txtSpecName-err').html('Project Specification Name must be greater than 2 characters');status=false;
            }else if(SpecName.length>100){
                $('#txtSpecName-err').html('Project Specification Name may not be greater than 100 characters');status=false;
            }
            if (Value=="") {
                $('#txtValues-err').html('Value is required');status = false;
            }
            if (status) {
                let index = $('#tblSpecValues tbody tr').length;
                let html='<tr>';
					html+='<td>'+ (index + 1) +'</td>';
					html+='<td>'+ Value +'</td>';
					html+='<td><button type="button" class="btn btn-sm btn-outline-danger btnDeleteSpecValue"><i class="fa fa-trash"></i></button></td>';
    				html+='</tr>';
				$('#tblSpecValues tbody').append(html);
				$('#txtValues').val('');
            }
        });
        
        $(document).on('click', '.btnDeleteSpecValue', function () {
            $(this).closest("tr").remove();
            $('#tblSpecValues tbody tr').each(function(index){
                $(this).find('td:eq(0)').text(index+1);
            });
		});
        $("#txtValues").keydown(function (event) {
            if (event.keyCode === 13) {
                $("#btnAddSpecValue").click();
            }
        });
        $('#btnSave').click(async function(e){
            e.preventDefault();
            let { status, formData }=await ValidateGetData();

            if(status){
                swal({
                    title: "Are you sure?",
                    text: "You want @if(!$projectSpecification) Save @else Update @endif this Project Specification!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-outline-success",
                    confirmButtonText: "Yes, @if(!$projectSpecification) Save @else Update @endif it!",
                    closeOnConfirm: false
                }).then(function () {  
                    swal.close();
                    btnLoading($('#btnSave'));
                    let postUrl="{{ $projectSpecification ? route('project_specifications.update', $projectSpecification->id) : route('project_specifications.store') }}";
                    let Type= "{{ $projectSpecification ? 'PUT' : 'POST' }}";
                    $.ajax({
                        type:Type,
                        url:postUrl,
                        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                        data:formData,
                        success:function(response){
                            document.documentElement.scrollTop = 0;
                            if(response.status==true){
                                
                                @if($projectSpecification)
                                    window.location.replace("{{route('project_specifications.index')}}");
                                @else
                                    window.location.reload();
                                @endif
                                
                            }else{
                                toastr.error(response.message, "Failed", { positionClass: "toast-top-right", containerId: "toast-top-right", showMethod: "slideDown", hideMethod: "slideUp", progressBar: !0 })
                                if(response['errors']!=undefined){
                                    $('.errors').html('');
                                    $.each( response['errors'], function( KeyName, KeyValue ) {
                                        var key=KeyName;
                                        if(key=="spec_name"){$('#txtSpecName-err').html(KeyValue);}
                                    });
                                }
                            }
                        }
                    });
                });
            }
        });

        let specData = [];

        $("#addSpec").click(function () {
            let specName = $("#spec_name").val().trim();
            let description = $("#description").val().trim();
            let level1 = $("#level1").val();
            let level2 = $("#level2").val();

            if (!specName) {
                toastr.error("Add a Project Specification Value", "Failed", { positionClass: "toast-top-right", containerId: "toast-top-right", showMethod: "slideDown", hideMethod: "slideUp", progressBar: !0 });
                return;
            }

            let level = 1;
            if (level1) level = 2;
            if (level1 && level2) level = 3;

            let rowClass = level === 1 ? "nested-1" : level === 2 ? "nested-2" : "nested-3";

            // Check for duplicates within the same parent
            let duplicateExists = $(`#specTable tbody tr[data-level='${level}'][data-lvl1='${level1}'][data-lvl2='${level2}'] td:nth-child(2)`)
                .filter(function () {
                    return $(this).text().trim().toLowerCase() === specName.toLowerCase();
                }).length > 0;

            if (duplicateExists) {
                toastr.error("Specification name already exists under the same parent!", "Duplicate Entry", { positionClass: "toast-top-right", containerId: "toast-top-right", showMethod: "slideDown", hideMethod: "slideUp", progressBar: !0 });
                return;
            }

            let newRow = `<tr class="${rowClass}" data-lvl1="${specName}" data-lvl2="${level2}" data-level="${level}">
                <td class="sno"></td>
                <td>${specName}</td>
                <td>${description}</td>
                <td><button class="btn btn-danger btn-sm deleteRow">Delete</button></td>
            </tr>`;

            if (level === 1) {
                $("#specTable tbody").append(newRow);
            } else if (level === 2) {
                let lastLevel1Row = $(`#specTable tbody tr[data-level='1'][data-lvl1='${level1}']`).last();
                if (lastLevel1Row.length) {
                    lastLevel1Row.after(newRow);
                } else {
                    $("#specTable tbody").append(newRow);
                }
            } else if (level === 3) {
                let lastLevel2Row = $(`#specTable tbody tr[data-level='2'][data-lvl1='${level1}'][data-lvl2='${level2}']`).last();
                if (lastLevel2Row.length) {
                    lastLevel2Row.after(newRow);
                } else {
                    let lastLevel1Row = $(`#specTable tbody tr[data-level='1'][data-lvl1='${level1}']`).last();
                    if (lastLevel1Row.length) {
                        lastLevel1Row.after(newRow);
                    } else {
                        $("#specTable tbody").append(newRow);
                    }
                }
            }

            specData.push({ spec: specName, desc: description, level: level, level1: level1, level2: level2 });
            updateDropdowns();
            updateTable();
            $("#spec_name, #description").val("");
        });



        function updateDropdowns() {
            let level1Options = [...new Set(specData.filter(e => e.level === 1).map(e => e.spec))];
            let level1Select = $("#level1");
            level1Select.empty().append('<option value="">Select Level 1</option>');
            level1Options.forEach(spec => {
                level1Select.append(`<option value="${spec}">${spec}</option>`);
            });
            let level2Select = $("#level2");
            level2Select.empty().append('<option value="">Select Level 2</option>');
        }

        $("#level1").change(function () {
            let selectedSpec = $(this).val();
            let level2Select = $("#level2");
            level2Select.empty().append('<option value="">Select Level 2</option>');

            let filteredDesc = specData
                .filter(e => e.level === 2 && e.level1 === selectedSpec)
                .map(e => e.spec);

            filteredDesc.forEach(desc => {
                level2Select.append(`<option value="${desc}">${desc}</option>`);
            });
        });

        $(document).on("click", ".deleteRow", function () {
            let row = $(this).closest("tr");
            let spec = row.data("lvl1");
            let desc = row.data("lvl2");

            specData = specData.filter(e => !(e.spec === spec && e.desc === desc));

            row.remove();
            updateDropdowns();
            updateTable();
        });

        function updateTable() {
            let count = 1; // Level 1 counter
            let level1Count = {}; // Stores numbering for level 1
            let level2Count = {}; // Stores numbering for level 2 per level 1

            $("#specTable tbody tr").each(function () {
                let level = $(this).data("level");
                let level1 = $(this).attr("data-lvl1") || "";
                let level2 = $(this).attr("data-lvl2") || "";

                if (level === 1) {
                    // Assign level 1 numbering
                    $(this).find(".sno").text(count);
                    level1Count[level1] = count; // Store parent count for level 2 reference
                    
                    // Initialize level2Count[level1] if not already initialized
                    if (!level2Count[level1]) level2Count[level1] = {};
                    
                    count++;
                } else if (level === 2) {
                    // Ensure level2Count[level1] is initialized before accessing level2Count[level1][level2]
                    if (!level2Count[level1]) level2Count[level1] = {};

                    // Get parent number and assign level 2 numbering
                    let parentNum = level1Count[level1] || count;
                    if (!level2Count[level1][level2]) level2Count[level1][level2] = 1;
                    let subNum = level2Count[level1][level2]++;
                    $(this).find(".sno").text(`${parentNum}.${subNum}`);
                } else if (level === 3) {
                    // Ensure level2Count[level1] is initialized
                    if (!level2Count[level1]) level2Count[level1] = {};

                    // Ensure level2Count[level1][level2] is initialized
                    if (!level2Count[level1][level2]) level2Count[level1][level2] = 1;

                    // Get parent number and assign level 3 numbering
                    let parentNum = level1Count[level1] || count;
                    let subNum = level2Count[level1][level2];
                    let childNum = $(this).prevAll(`[data-lvl1='${level1}'][data-lvl2='${level2}']`).length + 1;
                    $(this).find(".sno").text(`${parentNum}.${subNum}.${childNum}`);
                }
            });
        }


    });
</script>
    
@endsection

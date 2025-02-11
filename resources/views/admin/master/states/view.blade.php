@extends('layouts.admin')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Master</li>
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
						<div class="col-sm-4">	</div>
						<div class="col-sm-4 my-2"><h5>{{$PageTitle}}</h5></div>
						<div class="col-sm-4 my-2 text-right text-md-right">
						</div>
					</div>
				</div>
				<div class="card-body">
					<div id="order_filter" class="form-row justify-content-center m-20">
						<div class="col-sm-2">
							<div class="form-group text-center mh-60">
								<label style="margin-bottom:0px;">Active Status</label><br>
                                <select id="lstFActiveStatus" class="form-control multiselect">
                                    <option value="">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-sm-12 col-lg-12">
                            <table class="table" id="tblStates">
                                <thead>
                                    <tr>
                                        <th>State ID</th>
                                        <th>State Name</th>
                                        <th>Country Name</th>
                                        <th class="text-center">Active Status</th>
                                        <th class="text-center noExport">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){

        const LoadTable=async()=>{
			
			if(tblStates!=null){
				tblStates.fnDestroy();
			}
            let filterOptions = {
                CountryID:  $('#lstFCountry').val(),
                ActiveStatus: $('#lstFActiveStatus').val(),
            }
			tblStates=$('#tblStates').dataTable( {
				"bProcessing": true,
				"bServerSide": true,
                "ajax": {"url": "{{url('/')}}/admin/master/general/states/data?_token="+$('meta[name=_token]').attr('content'),data:filterOptions,"headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',
				"iDisplayLength": 10,
				"lengthMenu": [[10, 25, 50,100,250,500, -1], [10, 25, 50,100,250,500, "All"]],
				buttons: [
					'pageLength'],
				columnDefs: [
					{"className": "dt-center", "targets":[3,4]},
				]
			});
        }
        LoadTable();
    });
</script>
@endsection
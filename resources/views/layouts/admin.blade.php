<!DOCTYPE html>
<html lang="en">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="expires" content="0">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="ProPlus Logics">
        <meta name="_token" content="{{ csrf_token() }}"/>

        <title>{{$PageTitle}} - {{-- {{$Company['CompanyName']}} --}}</title>
		<link rel="icon" type="image/x-icon" href="{{-- {{url('/')}}/{{$Company['Logo']}} --}}">
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i&amp;display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700&amp;display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/fontawesome.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/icofont.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/themify.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/flag-icon.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/feather-icon.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/bootstrap.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/style.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/js/lightbox/css/lightgallery.css?r={{date('dmyHis')}}">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
		<link rel="stylesheet" type="text/css" href="{{url('/assets/plugins/pplDataTable/pplDataTable.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/assets/plugins/pplDataTable/pplDataTable.min.css')}}">



		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/sweetalert2.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/select2.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/toastr.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/dropify/css/dropify.min.css?r={{date('YmdHis')}}">
    	<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/bootstrap-multiselect/bootstrap-multiselect.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/image-cropper/cropper.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/dynamic-form/v3/dynamicForm.min.css?r={{date('YmdHis')}}">

		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/color-1.css?r={{date('YmdHis')}}" media="screen" id="color">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/loader.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/custom.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/custom-n.css?r={{date('YmdHis')}}">
	</head>
	<body>
		<input type="hidden" style="display:none!important" id="txtRootUrl" value="{{url('/')}}/">
		<input type="hidden" name="txtActiveName" id="txtActiveName" value="{{$ActiveMenuName}}">
		{{-- <div id="divsettings" style="display:none!important">{{json_encode($Settings)}}</div> --}}
		<div class="loader-wrapper">
			<div class="theme-loader"></div>
		</div>
		<div class="page-wrapper compact-wrapper" id="pageWrapper">
			<div class="page-main-header">
				<div class="main-header-right">
					<div class="main-header-left">
						<div class="logo-wrapper">
							<a href="{{url('/')}}/admin">
								<img loading="lazy" src="{{-- {{url('/')}}/{{$Company['Logo']}} --}}" alt="" width="50" height="52">
							</a>
						</div>
					</div>
					<div class="mobile-sidebar">
						<div class="flex-grow-1 text-end switch-sm">
							<label class="switch">
								<input id="sidebar-toggle" type="checkbox" data-bs-toggle=".container" checked="checked">
								<span class="switch-state"></span>
							</label>
						</div>
					</div>
					<div class="nav-right col pull-right right-menu">
						<ul class="nav-menus"><!--
							<li>
								<a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()">
									<i data-feather="maximize"></i>
								</a>
							</li>-->
							<li>
								
							</li>
							<li class="theme-setting">
								<i data-feather="settings"></i>
							</li>
							<li class="onhover-dropdown px-0">
								<span class="d-flex user-header">
									<img loading="lazy" class="me-2 rounded-circle img-35" src="{{-- {{url('/')}}/{{$UInfo->ProfileImage}} --}}" alt="">
									<span class="flex-grow-1">
										<span class="f-12 f-w-600">{{-- {{$UInfo->Name}} --}}</span>
										<span class="d-block">{{-- {{$UInfo->RoleName}} --}}</span>
									</span>
								</span>
								<ul class="profile-dropdown onhover-show-div">
                                    <li><a href="{{url('/')}}/admin/users-and-permissions/change-password/"><i data-feather="user"> </i>Password Change</a></li>
                                    <li class="btnLogout"><i data-feather="log-in"></i>Logout </li>
								</ul>
							</li>
						</ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
					</div>
					<div class="d-lg-none mobile-toggle pull-right">
						<i data-feather="more-horizontal"></i>
					</div>
				</div>
			</div>
			<div class="page-body-wrapper sidebar-icon">
				<nav-menus></nav-menus>
				<header class="main-nav">
					<nav>
						<div class="main-navbar">
							<div class="left-arrow" id="left-arrow">
								<i data-feather="arrow-left"></i>
							</div>
							<div id="mainnav">
								{{-- <ul class="nav-menu custom-scrollbar">
									<li class="back-btn">
										<div class="mobile-back text-end">
											<span>Back</span>
											<i class="fa fa-angle-right ps-2" aria-hidden="true"></i>
										</div>
									</li>
								</ul> --}}
								<ul class="nav-menu custom-scrollbar" style="display: block;">
									<li class="back-btn">
										<div class="mobile-back text-end">
											<span>Back</span>
											<i class="fa fa-angle-right ps-2" aria-hidden="true"></i>
										</div>
									</li>
                                    <li class="dropdown CMenus"><a class="nav-link menu-title link-nav active" data-active-name="Dashboard" href="http://localhost/VKV-OLD/admin/dashboard"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hexagon"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg><span>Dashboard</span><div class="according-menu"><i class="fa fa-angle-double-right"></i></div></a></li><li class="dropdown CMenus"><a class="nav-link menu-title" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg><span>Masters</span><div class="according-menu"><i class="fa fa-angle-double-right"></i></div></a><ul class="nav-submenu menu-content" style="display: none;"><li class=""><a href="http://localhost/VKV-OLD/admin/master/general/states" "="" data-active-name="States" data-original-title="" title="">States</a></li><li class=""><a href="http://localhost/VKV-OLD/admin/master/general/districts" "="" data-active-name="Districts" data-original-title="" title="">Districts</a></li><li class=""><a href="http://localhost/VKV-OLD/admin/master/general/postal-codes" "="" data-active-name="Postal-Codes" data-original-title="" title="">Postal Codes</a></li><li class=""><a href="http://localhost/VKV-OLD/admin/master/general/city" "="" data-active-name="City" data-original-title="" title="">City</a></li><li class=""><a href="http://localhost/VKV-OLD/admin/master/product/tax" "="" data-active-name="Tax" data-original-title="" title="">Tax</a></li><li class=""><a href="http://localhost/VKV-OLD/admin/master/product/unit-of-measurement" "="" data-active-name="Unit-Of-Measurement" data-original-title="" title="">Unit of Measurement</a></li></ul></li><li class="dropdown CMenus"><a class="nav-link menu-title" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><span>Users &amp; Permissions</span><div class="according-menu"><i class="fa fa-angle-double-right"></i></div></a><ul class="nav-submenu menu-content" style="display: none;"><li class=""><a href="http://localhost/VKV-OLD/admin/users-and-permissions/user-roles/" "="" data-active-name="User-and-Roles" data-original-title="" title="">User Roles</a></li><li class=""><a href="http://localhost/VKV-OLD/admin/users-and-permissions/users/" "="" data-active-name="Users" data-original-title="" title="">Users</a></li><li class=""><a href="http://localhost/VKV-OLD/admin/users-and-permissions/change-password/" "="" data-active-name="Change-Password" data-original-title="" title="">Change Password</a></li></ul></li><li class="dropdown CMenus"><a class="nav-link menu-title" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg><span>Settings</span><div class="according-menu"><i class="fa fa-angle-double-right"></i></div></a><ul class="nav-submenu menu-content" style="display: none;"><li class=""><a href="http://localhost/VKV-OLD/admin/settings/general/" "="" data-active-name="General-Settings" data-original-title="" title="">General</a></li><li class=""><a href="http://localhost/VKV-OLD/admin/settings/company/" "="" data-active-name="Company-Settings" data-original-title="" title="">Company</a></li></ul></li><li class="dropdown CMenus" id="btnLogout"><a class="nav-link menu-title link-nav" data-active-name="logout" href="http://localhost/VKV-OLD/"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg><span>Logout</span><div class="according-menu"><i class="fa fa-angle-double-right"></i></div></a></li>								</ul>
							</div>
							<div class="right-arrow" id="right-arrow">
								<i data-feather="arrow-right"></i>
							</div>
						</div>
					</nav>
				</header>
				<div class="page-body">
                    @yield('content')
					<div class="modal  medium" tabindex="-1" role="dialog" id="ImgCrop">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header pt-10 pb-10">
									<h5 class="modal-title">Image Crop</h5>
									<button type="button" class="close display-none" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-sm-12">
											<img loading="lazy" style="width:100%" src="" id="ImageCrop" data-id="">
										</div>
									</div>
									<div class="row mt-10 d-flex justify-content-center">
										<div class="col-sm-12 docs-buttons d-flex justify-content-center">
											<div class="btn-group">
												<button class="btn btn-outline-primary" type="button" data-method="rotate" data-option="-45" title="Rotate Left"><span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="$().cropper(&quot;rotate&quot;, -45)"><span class="fa fa-rotate-left"></span></span></button>
												<button class="btn btn-outline-primary" type="button" data-method="rotate" data-option="45" title="Rotate Right"><span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="$().cropper(&quot;rotate&quot;, 45)"><span class="fa fa-rotate-right"></span></span></button>
												<button class="btn btn-outline-primary" type="button" data-method="scaleX" data-option="-1" title="Flip Horizontal"><span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="$().cropper(&quot;scaleX&quot;, -1)"><span class="fa fa-arrows-h"></span></span></button>
												<button class="btn btn-outline-primary" type="button" data-method="scaleY" data-option="-1" title="Flip Vertical"><span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="$().cropper(&quot;scaleY&quot;, -1)"><span class="fa fa-arrows-v"></span></span></button>
												<button class="btn btn-outline-primary" type="button" data-method="reset" title="Reset"><span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="$().cropper(&quot;reset&quot;)"><span class="fa fa-refresh"></span></span></button>
												<button class="btn btn-outline-primary btn-upload" id="btnUploadImage" title="Upload image file"><span class="docs-tooltip" data-bs-toggle="tooltip" data-animation="false" title="Import image with Blob URLs"><span class="fa fa-upload"></span></span></button>
												<?php
													$Images=array("jpg","jpeg","png","gif","bmp","tiff");
													if(isset($FileTypes)){
														if(array_key_exists("category",$FileTypes)){
															if(array_key_exists("Images",$FileTypes['category'])){
																$Images=$FileTypes['category']['Images'];
															}
														}
													}
													$Images=".".implode(",.",$Images);
												?>
												<input class="sr-only display-none" id="inputImage" type="file" name="file" accept="{{$Images}}">
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-outline-dark"  id="btnCropModelClose">Close</button>
									<button type="button" class="btn btn-outline-info" id="btnCropApply">Apply</button>
								</div>
							</div>
						</div>
					</div>
				</div>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 footer-copyright">
                                <p class="mb-0">Copyright  &copy; @if(date("Y")=="2023") {{date("Y")}} @else 2023-{{date("Y")}} @endif <a class="text-bold-800 grey darken-2" href="https://propluslogics.com/" target="_blank">Web Development Company </a>, All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </footer>
			</div>
		</div>
		<?php
			$color="color-1";
			$mixLayout="light-only";
			$layout="ltr";
			$zoomLevel='100%';
			$FontSize="14px";
			$buttonSize="";
			$tableSize="";
			$switchSize="";
			$inputSize="";
		?>
		<div class="customizer-contain">
			<div class="customizer-links">
				<div class="nav nac-pills" id="c-pills-tab" role="tablist" aria-orientation="vertical">
					<a class="nav-link active show" id="c-pills-home-tab" data-bs-toggle="pill" href="#c-pills-home" role="tab"
						aria-controls="c-pills-home" aria-selected="true">
						<div class="settings"> <i class="icofont icofont-ui-settings"></i> General setting </div>
					</a>
					<a class="nav-link" id="c-pills-profile-tab" data-bs-toggle="pill" href="#c-pills-profile" role="tab"
						aria-controls="c-pills-profile" aria-selected="false">
						<div class="settings color-settings"> <i class="icofont icofont-color-bucket"></i> Colors </div>
					</a>
				</div>
			</div>
			<div class="tab-content" id="c-pills-tabContent">
				<div class="customizer-body custom-scrollbar">
					<div class="tab-pane fade show active" id="c-pills-home" role="tabpanel" aria-labelledby="c-pills-home-tab"><!--
						<h6>Layout Type</h6>
						<ul class="main-layout layout-grid">
							<li data-attr="ltr" class=" @if($layout=='ltr') active  @endif">
								<div class="body">
									<ul>
										<li class="body"> <span class="badge badge-light">LTR</span> </li>
									</ul>
								</div>
							</li>
							<li data-attr="rtl" class=" @if($layout=='rtl') active  @endif">
								<div class="body">
									<ul>
										<li class="body"> <span class="badge badge-light">RTL</span></li>
									</ul>
								</div>
							</li>
							<li data-attr="box-layout" class="box-layout  @if($layout=='box-layout') active  @endif">
								<div class="body">
									<ul>
										<li class="body"> <span class="badge badge-light">Box</span> </li>
									</ul>
								</div>
							</li>
						</ul>-->
						<h6 class="mb-1">Sidebar Type</h6>
						<ul class="sidebar-type layout-grid">
							<li data-attr="normal-sidebar">
								<div class="header bg-light">
									<ul>
										<li></li>
										<li></li>
										<li></li>
									</ul>
								</div>
								<div class="body">
									<ul>
										<li class="bg-dark sidebar"></li>
										<li class="bg-light body w-100"> </li>
									</ul>
								</div>
							</li>
							<li data-attr="compact-sidebar">
								<div class="header bg-light">
									<ul>
										<li></li>
										<li></li>
										<li></li>
									</ul>
								</div>
								<div class="body">
									<ul>
										<li class="bg-dark sidebar compact"></li>
										<li class="bg-light body"> </li>
									</ul>
								</div>
							</li>
						</ul>
						<h6 class="mb-1">Sidebar background setting</h6>
						<ul class="nav nac-pills nav-primary" id="pills-tab" role="tablist">
							<li class="nav-item"><a class="nav-link active show" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true" data-original-title="" title="">Color</a></li>
							<li class="nav-item"><a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false" data-original-title="" title="">Pattern</a></li>
							<li class="nav-item"><a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false" data-original-title="" title="">image</a></li>
						</ul>
						<div class="tab-content sidebar-main-bg-setting" id="pills-tabContent">
							<div class="tab-pane fade active show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
								<ul class="sidebar-bg-settings">
									<li class="bg-dark active" data-attr="dark-sidebar"> </li>
									<li class="bg-white" data-attr="light-sidebar"> </li>
									<li class="bg-color1" data-attr="color1-sidebar"> </li>
									<li class="bg-color2" data-attr="color2-sidebar"> </li>
									<li class="bg-color3" data-attr="color3-sidebar"> </li>
									<li class="bg-color4" data-attr="color4-sidebar"> </li>
									<li class="bg-color5" data-attr="color5-sidebar"> </li>
								</ul>
							</div>
							<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
								<ul class="sidebar-bg-settings">
									<li class=" bg-pattern1" data-attr="sidebar-pattern1"> </li>
									<li class=" bg-pattern2" data-attr="sidebar-pattern2"> </li>
									<li class=" bg-pattern3" data-attr="sidebar-pattern3"> </li>
									<li class=" bg-pattern4" data-attr="sidebar-pattern4"> </li>
									<li class=" bg-pattern5" data-attr="sidebar-pattern5"> </li>
									<li class=" bg-pattern6" data-attr="sidebar-pattern6"> </li>
								</ul>
							</div>
							<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
								<ul class="sidebar-bg-settings">
									<li class="bg-img1" data-attr="sidebar-img1"> </li>
									<li class="bg-img2" data-attr="sidebar-img2"> </li>
									<li class="bg-img3" data-attr="sidebar-img3"> </li>
									<li class="bg-img4" data-attr="sidebar-img4"> </li>
									<li class="bg-img5" data-attr="sidebar-img5"> </li>
									<li class="bg-img6" data-attr="sidebar-img6"> </li>
								</ul>
							</div>
						</div>
						<div class="row "  style="display:none">
							<div class="col-12 col-sm-6 mt-30 d-none" >
								<h6 class="mb-1">Zoom</h6>
								<select  class="form-control" id="lstZoom">
									<option value="25%" @if($zoomLevel=="25%") Selected  @endif>25 %</option>
									<option value="50%" @if($zoomLevel=="50%") Selected  @endif>50 %</option>
									<option value="75%" @if($zoomLevel=="75%") Selected  @endif>75 %</option>
									<option value="100%" @if($zoomLevel=="100%") Selected  @endif>100 %</option>
									<option value="125%" @if($zoomLevel=="125%") Selected  @endif>125 %</option>
									<option value="150%" @if($zoomLevel=="150%") Selected  @endif>150 %</option>
									<option value="175%" @if($zoomLevel=="175%") Selected  @endif>175 %</option>
									<option value="200%" @if($zoomLevel=="200%") Selected  @endif>200 %</option>
								</select>
							</div>
							<div class="col-12 col-sm-6 mt-30" style="display:none">
								<h6 class="mb-1">Font Size</h6>
								<select  class="form-control" id="lstFontSize" >
									@for($i=12;$i<=20;$i++)
										<option value="{{$i}}px" @if($FontSize==$i."px") Selected  @endif >{{$i}} px</option>
									@endfor
								</select>
							</div>
							<div class="col-12  col-sm-6 mt-30" style="display:none">
								<h6 class="mb-1">Input Size</h6>
								<select  class="form-control" id="lstInputSize">
									<option value="form-control-lg" @if($inputSize=="form-control-lg") Selected  @endif>Large</option>
									<option value="" @if($inputSize=="") Selected  @endif>Normal</option>
									<option value="form-control-sm" @if($inputSize=="form-control-sm") Selected  @endif>Small</option>
								</select>
							</div>
							<div class="col-12 col-sm-6 mt-30" style="display:none">
								<h6 class="mb-1">Button Size</h6>
								<select  class="form-control" id="lstButtonSize">
									<option value="btn-lg" @if($buttonSize=="btn-lg") Selected  @endif>Large</option>
									<option value="" @if($buttonSize=="") Selected  @endif>Normal</option>
									<option value="btn-sm" @if($buttonSize=="btn-sm") Selected  @endif>Small</option>
									<option value="btn-xs" @if($buttonSize=="btn-xs") Selected  @endif>Extra Small</option>
								</select>
							</div>
							<div class="col-12 col-sm-6 mt-30" style="display:none">
								<h6 class="mb-1">Table Size</h6>
								<select  class="form-control" id="lstTableSize">
									<option value="" @if($tableSize=="") Selected  @endif>Normal</option>
									<option value="table-sm" @if($tableSize=="table-sm") Selected  @endif>Small</option>
								</select>
							</div>
							<div class="col-12  col-sm-6 mt-30" style="display:none">
								<h6 class="mb-1">switch Size</h6>
								<select  class="form-control" id="lstSwitchSize">
									<option value="switch-lg" @if($switchSize=="switch-lg") Selected  @endif>Large</option>
									<option value="" @if($switchSize=="") Selected  @endif>Normal</option>
									<option value="switch-sm" @if($switchSize=="switch-sm") Selected  @endif>Small</option>
								</select>
							</div>
						</div>
					</div>
					<div class="tab-pane fade " id="c-pills-profile" role="tabpanel" aria-labelledby="c-pills-profile-tab">
						<h6 class="mb-1">Light layout</h6>
						<ul class="layout-grid customizer-color">
							<li class="color-layout @if($color=='color-1') active @endif" data-attr="color-1" data-primary="#158df7" data-secondary="#fb2e63"><div></div></li>
							<li class="color-layout @if($color=='color-2') active @endif" data-attr="color-2" data-primary="#0288d1" data-secondary="#26c6da"><div></div></li>
							<li class="color-layout @if($color=='color-3') active @endif" data-attr="color-3" data-primary="#d64dcf" data-secondary="#8e24aa"><div></div></li>
							<li class="color-layout @if($color=='color-4') active @endif" data-attr="color-4" data-primary="#4c2fbf" data-secondary="#2e9de4"><div></div></li>
							<li class="color-layout @if($color=='color-5') active @endif" data-attr="color-5" data-primary="#7c4dff" data-secondary="#7b1fa2"><div></div></li>
							<li class="color-layout @if($color=='color-6') active @endif" data-attr="color-6" data-primary="#3949ab" data-secondary="#4fc3f7"><div></div></li>
						</ul>
						<h6 class="mb-1">Dark Layout</h6>
						<ul class="layout-grid customizer-color dark">
							<li class="color-layout @if($color=='color-1') active @endif" data-attr="color-1" data-primary="#4466f2" data-secondary="#1ea6ec"><div></div></li>
							<li class="color-layout @if($color=='color-2') active @endif" data-attr="color-2" data-primary="#0288d1" data-secondary="#26c6da"><div></div></li>
							<li class="color-layout @if($color=='color-3') active @endif" data-attr="color-3" data-primary="#d64dcf" data-secondary="#8e24aa"><div></div></li>
							<li class="color-layout @if($color=='color-4') active @endif" data-attr="color-4" data-primary="#4c2fbf" data-secondary="#2e9de4"><div></div></li>
							<li class="color-layout @if($color=='color-5') active @endif" data-attr="color-5" data-primary="#7c4dff" data-secondary="#7b1fa2"><div></div></li>
							<li class="color-layout @if($color=='color-6') active @endif" data-attr="color-6" data-primary="#3949ab" data-secondary="#4fc3f7"><div></div></li>
						</ul>
						<h6 class="mb-1">Mix Layout</h6>
						<ul class="layout-grid customizer-mix">
							<li class="color-layout @if($mixLayout=='light-only') active @endif" data-attr="light-only">
								<div class="header bg-light">
									<ul>
										<li></li>
										<li></li>
										<li></li>
									</ul>
								</div>
								<div class="body">
									<ul>
										<li class="bg-light sidebar"></li>
										<li class="bg-light body"> </li>
									</ul>
								</div>
							</li>
							<li class="color-layout @if($mixLayout=='dark-sidebar') active @endif" data-attr="dark-sidebar">
								<div class="header bg-light">
									<ul>
										<li></li>
										<li></li>
										<li></li>
									</ul>
								</div>
								<div class="body">
									<ul>
										<li class="bg-dark sidebar"></li>
										<li class="bg-light body"> </li>
									</ul>
								</div>
							</li>
							<li class="color-layout @if($mixLayout=='dark-body-only') active @endif" data-attr="dark-body-only">
								<div class="header bg-light">
									<ul>
										<li></li>
										<li></li>
										<li></li>
									</ul>
								</div>
								<div class="body">
									<ul>
										<li class="bg-light sidebar"></li>
										<li class="bg-dark body"> </li>
									</ul>
								</div>
							</li>
							<li class="color-layout @if($mixLayout=='dark-sidebar-body-mix') active @endif" data-attr="dark-sidebar-body-mix">
								<div class="header bg-light">
									<ul>
										<li></li>
										<li></li>
										<li></li>
									</ul>
								</div>
								<div class="body">
									<ul>
										<li class="bg-dark sidebar"></li>
										<li class="bg-dark body"> </li>
									</ul>
								</div>
							</li>
							<li class="color-layout @if($mixLayout=='dark-header-sidebar-mix') active @endif" data-attr="dark-header-sidebar-mix">
								<div class="header bg-dark">
									<ul>
										<li></li>
										<li></li>
										<li></li>
									</ul>
								</div>
								<div class="body">
									<ul>
										<li class="bg-dark sidebar"></li>
										<li class="bg-light body"> </li>
									</ul>
								</div>
							</li>
							<li class="color-layout @if($mixLayout=='dark-only') active @endif" data-attr="dark-only">
								<div class="header bg-dark">
									<ul>
										<li></li>
										<li></li>
										<li></li>
									</ul>
								</div>
								<div class="body">
									<ul>
										<li class="bg-dark sidebar"></li>
										<li class="bg-dark body"> </li>
									</ul>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<script src="{{url('/')}}/assets/js/jquery-3.7.1.min.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/bootstrap/bootstrap.bundle.min.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/icons/feather-icon/feather.min.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/icons/feather-icon/feather-icon.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/sidebar-menu.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/config.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/script.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/theme-customizer/customizer.js?r={{date('YmdHis')}}"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js?r={{date('YmdHis')}}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js?r={{date('YmdHis')}}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.min.js?r={{date('YmdHis')}}"></script>

		<script src="{{url('/assets/plugins/pplDataTable/pplDataTable.js')}}"></script>
		<script src="{{url('/assets/plugins/pplDataTable/dataTable.min.js')}}"></script>


		<script src="{{url('/')}}/assets/plugins/bootstrap-multiselect/bootstrap-multiselect.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/select2/select2.full.min.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/sweet-alert/sweetalert.min.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/toastr.min.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/plugins/dropify/js/dropify.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/plugins/bootbox-js/bootbox.min.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/plugins/image-cropper/cropper.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/plugins/dynamic-form/v3/dynamicForm.min.js?r={{date('YmdHis')}}"></script>
    	<script src="{{url('/')}}/assets/js/prototypes.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/support.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/custom.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/address.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/app-init.js?r={{date('YmdHis')}}"></script>
		<script src="{{url('/')}}/assets/js/form-wizard/form-wizard-two.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
		<script src="{{url('/')}}/assets/js/lightbox/js/lightgallery.js?r={{date('dmyHis')}}"></script>
		<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
		<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>
		<script src="{{url('/assets/firebase.full.js')}}"></script>


		<script src="{{url('/')}}/assets/plugins/ckeditor/ckeditor.js"></script>
    	<script src="{{url('/')}}/assets/plugins/ckeditor/custom.js"></script>
        @yield('scripts')
	</body>
</html>

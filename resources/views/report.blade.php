<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Project Report</title>

        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/flag-icon.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/fontawesome.css?r={{date('YmdHis')}}">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/support/margin.css">
		<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/bootstrap.css"> 

        {{-- bootstrap cdn--}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <link href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	   
        <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    </head>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
        }
        .hero-title {
            background-color: #cac9df;
            text-align: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px; 
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            font-weight: bolder;
            color: #5a54a2;
            font-size: x-large;
            box-shadow: 0px 2px 25px 10px #bababa8a;
        }

        .wizard-head {
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

        .wizard-head .nav-title {
            color: #c0c0c0;
        }

        .wizard-head .nav-title {
            color: #fbf9f9;
        }
        .wizard-head .nav-link.active .nav-title {
            color: #7167f4;
        }

        .wizard-head .nav-link i {
            font-size: 32px;
            color: #d8d6ff;
        }

        .wizard-head .nav-link.active i {
            color: #7167f4 !important;
        }

        .nav-tabs {
            border-bottom: none !important;
        }
        .nav-tabs .nav-link{
            border-radius: 0px;
        }
        .wizard-head .nav-link {
            color: #fff; 
            padding: 10px 65px;
            background-color: #7167f4;
            box-shadow: 10px 10px #784d954d;
        }

        .wizard-head .nav-link:hover{
            transform: translate(6px, 6px) !important;
            box-shadow: none;
        }

        hr{
            /* background-color: #7167f4;  */
            background-color: #000000; 
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: #e5e7eb;
            overflow: hidden;
        }
        .progress-value {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(to right, #4f46e5, #818cf8);
        }
        .stage-box{
            cursor: pointer;
            padding: 20px 30px;
            background-color: #cac9df73;
            color: #7167f4 !important;
            font-weight: bolder;
            font-size: medium;
        }
    </style>
    <body class="">
        <div class="container-fluid">
            <div class="hero-title w-25">
                <span>
                    {{$project->name}}
                 </span>
             </div>
            <div class="row justify-content-center" style="padding: 15px;">
               <div class="col-12 mt-10">
                <a href="{{ url()->previous() }}" class="btn btn-back btn-light">Back</a>
               </div>  
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-lg-12"> 
                    <div class="card mt-15" style="border: none;">
                        <div class="card-body">
                            <div class="nav nav-tabs wizard-head" id="projectTabs">
                                <a class="nav-link active" href="#project-details" data-tab="project-details"
                                data-name="tab-project-details" data-bs-toggle="tab">
                                    <span class="nav-contents">
                                        <span class="nav-title">Details</span>
                                    </span>
                                </a>
                                <a class="nav-link" href="#project-stages" data-tab="project-stages"
                                data-name="tab-project-stages" data-bs-toggle="tab">
                                <span class="nav-contents">
                                    <span class="nav-title">Stages</span>
                                </span>
                                </a>
                                <a class="nav-link" href="#project-contractors" data-tab="project-contractors"
                                data-name="tab-project-contractors" data-bs-toggle="tab">
                                    <span class="nav-contents">
                                        <span class="nav-title">Contractors</span>
                                    </span>
                                </a>
                                <a class="nav-link" href="#project-labors" data-tab="project-labors"
                                data-name="tab-project-labors" data-bs-toggle="tab">
                                    <span class="nav-contents">
                                        <span class="nav-title">Labors</span>
                                    </span>
                                </a>
                                <a class="nav-link" href="#project-purchases" data-tab="project-purchases"
                                data-name="tab-project-purchases" data-bs-toggle="tab">
                                    <span class="nav-contents">
                                        <span class="nav-title">Purchases</span>
                                    </span>
                                </a>
                            </div>
                            <div class="position-relative" style="margin: 0 -2rem; background-color: #b1b1b1;"> 
                                <hr class="border-top">
                            </div>
                            
                            <div class="tab-content mt-3">
                                <div class="tab-pane fade show active" id="project-details">
                                    <div class="container mx-auto px-4 py-8">
                                        <!-- Header -->
                                        <div class="flex justify-between items-center mb-8">
                                          <div>
                                            <h1 class="text-3xl font-bold text-gray-800"><i class="fas fa-map-marker-alt mr-2"></i>{{ $project->location }}</h1>
                                          </div>
                                          {{-- <div class="flex space-x-3">
                                            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-medium">{{ $project->status }}</span>
                                            <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                              <i class="fas fa-edit mr-2"></i>Edit
                                            </button>
                                            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                              <i class="fas fa-print mr-2"></i>Print
                                            </button>
                                          </div> --}}
                                        </div>
                                        
                                        <!-- Main Grid -->
                                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                          <!-- Project Details Card -->
                                          <div class="bg-white rounded-xl shadow-md p-6 transition duration-300 card-hover">
                                            <div class="flex items-center mb-4">
                                                <div class="p-3 bg-indigo-100 rounded-lg">
                                                    <i class="fas fa-building text-indigo-600 text-xl"></i>
                                                </div>
                                                <h2 class="text-xl font-bold text-gray-800 ml-3">Project Details</h2>

                                                <div class="ml-20">
                                                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-medium">{{ $project->status }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-4">
                                              <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                  <p class="text-sm text-gray-500">Type</p>
                                                  <p class="font-medium">{{ $project->type }}</p>
                                                </div>
                                                <div>
                                                  <p class="text-sm text-gray-500">Units</p>
                                                  <p class="font-medium">{{ $project->units }}</p>
                                                </div>
                                              </div>
                                              
                                              <div>
                                                <p class="text-sm text-gray-500">Target Customers</p>
                                                <p class="font-medium">{{ $project->target_customers }}</p>
                                              </div>
                                              
                                              <div>
                                                <p class="text-sm text-gray-500">Range</p>
                                                <p class="font-medium">{{ $project->range }}</p>
                                              </div>
                                              
                                              <div>
                                                <p class="text-sm text-gray-500">Engineer</p>
                                                <p class="font-medium">{{ $project->engineer->name }}</p>
                                              </div>
                                              
                                              <div>
                                                <p class="text-sm text-gray-500">Area</p>
                                                <p class="font-medium">{{ number_format($project->area_sqft) }} sqft</p>
                                              </div>

                                            </div>
                                          </div>
                                          
                                          <!-- Financial Overview Card -->
                                          <div class="bg-white rounded-xl shadow-md p-6 transition duration-300 card-hover">
                                            <div class="flex items-center mb-4">
                                              <div class="p-3 bg-green-100 rounded-lg">
                                                <i class="fas fa-chart-pie text-green-600 text-xl"></i>
                                              </div>
                                              <h2 class="text-xl font-bold text-gray-800 ml-3">Financial Overview</h2>
                                            </div>
                                            
                                            <div class="space-y-6">
                                              <div>
                                                <div class="flex justify-between mb-1">
                                                  <p class="text-sm text-gray-500">Investment Amount</p>
                                                  <p class="text-sm font-medium">${{ number_format($project->investment_amount) }}</p>
                                                </div>
                                                <div class="progress-bar">
                                                  <div class="progress-value" style="width: 100%"></div>
                                                </div>
                                              </div>
                                              
                                              <div>
                                                <div class="flex justify-between mb-1">
                                                  <p class="text-sm text-gray-500">Sold Amount</p>
                                                  <p class="text-sm font-medium">${{ number_format($project->sold_amount) }}</p>
                                                </div>
                                                <div class="progress-bar">
                                                  <div class="progress-value" style="width: {{ ($project->sold_amount / ($project->investment_amount ?? 1)) * 100 }}%"></div>
                                                </div>
                                              </div>
                                              
                                              <div class="pt-4 border-t">
                                                <div class="flex justify-between">
                                                  <p class="font-semibold">Total Profit</p>
                                                  <p class="font-bold text-green-600">${{ number_format($project->investment_amount - $project->sold_amount) }}</p>
                                                </div>
                                              </div>
                                              
                                              <div class="pt-4">
                                                <canvas id="financialChart" width="100%" height="150"></canvas>
                                              </div>
                                            </div>
                                          </div>
                                          
                                          <!-- Site Details Card -->
                                          <div class="bg-white rounded-xl shadow-md p-6 transition duration-300 card-hover">
                                            <div class="flex items-center mb-4">
                                              <div class="p-3 bg-yellow-100 rounded-lg">
                                                <i class="fas fa-map text-yellow-600 text-xl"></i>
                                              </div>
                                              <h2 class="text-xl font-bold text-gray-800 ml-3">Site Information</h2>
                                            </div>
                                            
                                            <div class="space-y-4">
                                              <div>
                                                <p class="text-sm text-gray-500">Site Name</p>
                                                <p class="font-medium">{{ $project->site->name }}</p>
                                              </div>
                                              
                                              <div>
                                                <p class="text-sm text-gray-500">Location</p>
                                                <p class="font-medium">{{ $project->site->location }}</p>
                                              </div>
                                              
                                              <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                  <p class="text-sm text-gray-500">Latitude</p>
                                                  <p class="font-medium">{{ $project->site->latitude }}</p>
                                                </div>
                                                <div>
                                                  <p class="text-sm text-gray-500">Longitude</p>
                                                  <p class="font-medium">{{ $project->site->longitude }}</p>
                                                </div>
                                              </div>
                                              
                                              <div>
                                                <p class="text-sm text-gray-500">Status</p>
                                                <span class="inline-flex px-3 py-1 text-sm {{ $project->site->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                                                  {{ $project->site->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                              </div>
                                              
                                              <div class="pt-4">
                                                <div id="maps" class="w-full h-48 bg-gray-200 rounded-lg">
                                                  <!-- Map placeholder -->
                                                  <div id="map" style="height: 100%; border-radius: 8px;"></div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        
                                        <!-- Amenities Section -->
                                        <div class="mt-8">
                                          <div class="bg-white rounded-xl shadow-md p-6 transition duration-300 card-hover">
                                            <div class="flex items-center mb-6">
                                              <div class="p-3 bg-purple-100 rounded-lg">
                                                <i class="fas fa-star text-purple-600 text-xl"></i>
                                              </div>
                                              <h2 class="text-xl font-bold text-gray-800 ml-3">Project Amenities</h2>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                                @foreach($project->amenities as $item)
                                                <div class="items-center p-3 bg-gray-50 rounded-lg">
                                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                                    <span>{{ $item->amenity->name }}</span>
                                                    <br>
                                                    <span>{{ $item->description }}</span>
                                                </div>
                                            @endforeach
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="project-stages">
                                    {{-- <div class="row">
                                        <div class="col-3">
                                            @foreach($stages as $item)                                                
                                                <a href="" data-id="{{$item->project_id}}">{{$item}}</a>
                                            @endforeach
                                        </div>
                                        <div class="col-3">
                                        </div>
                                    </div> --}}
                                    <div class="row g-3">    
                                        <div class="col-xxl-3 col-xl-3 col-12">
                                          <div class="nav flex-column header-vertical-wizard" id="wizard-tab" role="tablist" aria-orientation="vertical">
                                              @foreach($stages as $item)   
                                                  <a class="stage-box nav-link active" id="{{$item->id}}" data-bs-toggle="pill"  role="tab" aria-controls="wizard-contact" aria-selected="true"> 
                                                      <div class="vertical-wizard">
                                                        <div class="vertical-wizard-content"> 
                                                            <h6>{{$item->name}}</h6>
                                                        </div>
                                                      </div>
                                                  </a>
                                              @endforeach
                                          </div>
                                        </div>
                                        <div class="col-xxl-9 col-xl-9 col-12">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Name</th>
                                                        <th>Date</th>
                                                        <th>Description</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tblTasks"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="project-contractors">3</div>
                                <div class="tab-pane fade" id="project-labors">4</div>
                                <div class="tab-pane fade" id="project-purchases">5</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.7/gsap.min.js" integrity="sha512-f6bQMg6nkSRw/xfHw5BCbISe/dJjXrVGfz9BSDwhZtiErHwk7ifbmBEtF9vFW8UNIQPhV2uEFVyI/UHob9r7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
     
    <script src="{{url('/')}}/assets/js/bootstrap/bootstrap.bundle.min.js?r={{date('YmdHis')}}"></script>  
 
 
</body>

    <script>
        $(document).ready(function(){
            // ----------------animations
            gsap.from(".hero-title", {
                y: -100, 
                opacity: 0,
                duration: 1,
                ease: "power3.out"
            });

            gsap.from(".btn-back", {
                x: -100, 
                opacity: 0,
                duration: 1,
                ease: "power3.out"
            });

            gsap.from(".nav-link", {
                x: -100,
                opacity: 0,
                duration: 1,
                stagger: 0.13,
                ease: "power2.out"
            });

            gsap.from(".tab-content", { 
                y: 200, 
                opacity: 0,
                duration: 1, 
                ease: "power2.out"
            });

            $('.stage-box').on('click',function(){

                let csrfToken = $('meta[name="_token"]').attr('content');

                let stage_id = $(this).attr('id');

                $.ajax({
                    url: "{{ route('getProjectTasks') }}",
                    type: "GET",
                    data: {'stage_id': stage_id},
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        console.log(response);
                    },
                });
            });
 
        })
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const project = "{{ $project->site->name ?? 'Site Location' }}";
            const latitude = {{ $project->site->latitude ?? '0' }};
            const longitude = {{ $project->site->longitude ?? '0' }};
    
            const map = L.map('map').setView([latitude, longitude], 15); // Zoom level 15 is decent
    
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
    
            // Add marker
            L.marker([latitude, longitude])
                .addTo(map)
                .bindPopup(`${project}`)
                .openPopup();
        });
    </script>
</html>

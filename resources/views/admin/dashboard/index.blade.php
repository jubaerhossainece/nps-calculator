@extends('admin.layouts.app')
@push('styles')
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@endpush

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center summery">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex">
                        <div class="user">
                            <div class="user-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stats user-stat">
                            <h2>{{ $users }}</h2>
                            <span>Total user</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex">
                        <div class="nps">
                            <div class="nps-icon">
                            <i class="fas fa-layer-group"></i>
                            </div>
                        </div>
                        <div class="stats nps-stat">
                            <h2>{{ $nps }}</h2>
                            <span>Total NPS Collect</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex">
                        <div class="avg-nps">
                            <div class="avg-nps-icon">
                                <i class="fas fa-tasks-alt"></i>
                            </div>
                        </div>
                        <div class="stats avg-nps-stat">
                            <h2>{{ $total_project }}</h2>
                            <span>Total Projects</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h4>User Summery</h4>
                        </div>
                        <div class="user-filter-box">

                                <select name="user_filter" class="form-select form-select-lg mb-3 select_with_search" id="user-filter" onchange="getNpsData()">
                                    <option value="">This Year</option>
                                    <option value="">Last week</option>
                                    <option value="">Last month</option>
                                    <option value="">Last year</option>
                                    <option value="">Date range</option>
                                </select>




                            <!-- <ul class="nav nav-tabs tabs-bordered" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#" role="tab"
                                        aria-selected="true" onclick="getUserData('year')">
                                        <span class="d-none d-sm-block">Yearly</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#" role="tab"
                                        aria-selected="false" onclick="getUserData('month')">
                                        <span class="d-none d-sm-block">Monthly</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#" role="tab"
                                        aria-selected="false" onclick="getUserData('week')">
                                        <span class="d-none d-sm-block">Weekly</span>
                                    </a>
                                </li>
                            </ul> -->
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="cardCollpase1" class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="user-summery"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h4>Project NPS statistics</h4>
                            </div>
                            <div class="col-md-8">
                                <div class="row mt-2">
                                    <div class="col-md-6 form-group">
                                        <select name="select_box" class="form-select form-select-lg mb-3 select_with_search"
                                            id="projectId" data-live-search="true" onchange="getProjectFeedback()">
                                            <option value="">Select Project</option>
                                            @foreach ($projects as $key => $project)
                                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" class="form-control" id="date-range"
                                            placeholder="Select Date Ranges">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="cardCollpase2" class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="project-feedback"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> --}}

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h4>NPS score</h4>
                            </div>
                            <div class="col-md-8">
                                <div class="row mt-2">
                                    <div class="col-md-6 form-group">
                                        {{-- <label> Projects </label> --}}
                                        <select name="select_box" class="form-select form-select-lg mb-3 select_with_search"
                                            id="projectIdNps" data-live-search="true" onchange="getNpsData()">
                                            <option value="">Select Project</option>
                                            @foreach ($projects as $key => $project)
                                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        {{-- <label> Date range </label> --}}
                                        <input type="text" class="form-control" id="date-range-ano" placeholder="Select Date Range">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="cardCollpase2" class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="nps-score"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/datepicker.js') }}"></script>
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select_with_search').select2();
            $('.select2-selection--single').css('height','38px');
            $('#user-filter').select2({
                minimumResultsForSearch: Infinity
            })
        });
    </script>
@endpush

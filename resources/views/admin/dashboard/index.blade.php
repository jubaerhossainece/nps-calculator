@extends('admin.layouts.app')
@push('styles')
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
                            <h4>User Summary</h4>
                        </div>

                        <div class="filter-box">
                            <span>filter</span>
                            <div id="user-report-range" class="date-range-picker">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                            <!-- <input id="user-report-range" name="user_date_range" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" onchange="getUserData()"> -->
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="cardCollapse1" class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="user-summary-canvas"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3">
                                <h4>Collected NPS Summary</h4>
                            </div>
                            <div class="col-md-9">
                                <div class="row mt-2">
                                    <div class="col-md-4 form-group">
                                        <span>User</span>
                                        <select name="user_nps_filter" class="select2 form-select form-select-lg mb-3 select_with_search"
                                            id="user-nps-filter" data-live-search="true" onchange="getProjectFeedback()">
                                            <option value="">All Users</option>
                                            @foreach ($users as $key => $user)
                                                <option value="{{ $project->id }}">{{ $project->email }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 form-group">
                                        <span>Project</span>
                                        <select name="project_nps_filter" class="select2 form-select form-select-lg mb-3 select_with_search"
                                            id="project-nps-filter" data-live-search="true" onchange="getProjectFeedback()">
                                            <option value="">All Projects</option>
                                            @foreach ($projects as $key => $project)
                                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <span>filter</span>
                                        <div id="nps-report-range" class="date-range-picker">
                                            <i class="fa fa-calendar"></i>&nbsp;
                                            <span></span> <i class="fa fa-caret-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="cardCollapse2" class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="nps-summary-canvas"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <!-- <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h4>Project Summary</h4>
                            </div>
                            <div class="col-md-4 offset-md-4">
                                <span>filter</span>
                                <div id="project-report-range" class="date-range-picker">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="cardCollpase2" class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="project-summary-canvas"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> -->

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <!-- Select2 JS -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- date range JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/datepicker.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('.select2-selection--single').css('height','38px');
            $('#user-filter').select2({
                minimumResultsForSearch: Infinity
            })
        });
    </script>

@endpush

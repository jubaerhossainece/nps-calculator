@extends('admin.layouts.app')
@push('styles')
    <style>

        /* yajra datatables customization */
        /* .table-responsive {
            overflow-x: none;
        } */

        .card-header:first-child {
            border-radius: 10px;
            background: white;
        }

        .card{
            border-radius: 10px;
        }

        /* css for summery section */
        .summery .card-body{
            padding: 0px;
        }

        .card-body .stats{
            font-weight: 600;
        }

        .card-body .stats h2, .card-body .stats span{
            color: white;
            margin-bottom: 0px;
        }

        .stats h2{
            font-size: 33px;
        }

        .audience {
            padding: 17px;
            color: white;
            background-color: #1877E2;
            border-radius: 10px 0px 0px 10px;
        }

        .audience-icon {
            background-color: #519DEB;
            height: 86px;
            width: 86px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .audience-icon i{
            font-size: 20px;
        }

        .audience-stat{
            background-image: linear-gradient(to right, #1979E3, #98e2fd);
            width: 100%;
        }


        .nps {
            padding: 17px;
            color: white;
            background-color: #F7931D;
            border-radius: 10px 0px 0px 10px;
        }

        .nps-icon {
            background-color: #FAB25E;
            height: 86px;
            width: 86px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nps-icon i{
            font-size: 20px;
        }

        .nps-stat{
            background-image: linear-gradient(to right, #F7931D, #FCC07A);
            width: 100%;
        }


        .avg-nps {
            padding: 17px;
            color: white;
            background-color: #DC396A;
            border-radius: 10px 0px 0px 10px;
        }

        .avg-nps-icon {
            background-color: #E76E92;
            height: 86px;
            width: 86px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avg-nps-icon i{
            font-size: 30px;
        }

        .avg-nps-stat{
            background-image: linear-gradient(to right, #DC396A, #FD5F8E);
            width: 100%;
        }

        .stats{
            padding: 20px;
            border-radius: 0px 10px 10px 0px;
        }

    </style>
@endpush

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center summery">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex">
                        <div class="audience">
                            <div class="audience-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stats audience-stat">
                            <h2>{{$audiences}}</h2>
                            <span>Total audience</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex">
                        <div class="nps">
                            <div class="nps-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stats nps-stat">
                            <h2>{{$nps}}</h2>
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
                            <h2>{{$projects}}</h2>
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
                        <div><h4>Audience summery</h4></div>
                        <div>
                            <ul class="nav nav-tabs tabs-bordered" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#" role="tab" aria-selected="true" onclick="getAudienceData('year')">
                                        <span class="d-none d-sm-block">Yearly</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#" role="tab" aria-selected="false" onclick="getAudienceData('month')">
                                        <span class="d-none d-sm-block">Monthly</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#" role="tab" aria-selected="false" onclick="getAudienceData('week')">
                                        <span class="d-none d-sm-block">Weekly</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="cardCollpase1" class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="audience-summery"></canvas>
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
                        <h4>Recent Audience</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="audience-table">
                                <thead>

                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{asset('js/dashboard.js')}}"></script>
@endpush


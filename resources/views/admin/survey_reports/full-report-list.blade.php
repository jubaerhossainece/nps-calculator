@extends('admin.layouts.app')
@section('styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
         <div class="card">
            <div class="row">
                <div class="col col-sm-6">
                    <h4 class="page-title">Report</h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <td id="project_name">
                                    {{ $projectLink->name }}
                                </td>
                            </tr>
    
                            <tr>
                                <th>Code</th>
                                <td id="code">
                                    {{ $projectLink->code }}
                                </td>
                            </tr>
    
                        </thead>
                    </table>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Report Type</th>
                        <th scope="col">Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($link_reports as $key=>$report)
                        <tr>
                            <td>{{ ((request('page') ?: 1) - 1) * 10 + $key + 1 }}</td>
                            <td>{{ $reportModel->getReportTypeUsingId($report->report_type_id) }}</td>
                            <td>{{ $report->comment }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
         </div>
        <!-- end page title -->

    </div>
@endsection

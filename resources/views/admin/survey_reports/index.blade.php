@extends('admin.layouts.app')
@section('styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row mt-4 mb-3">
            <div class="col-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between bg-white">
                        <div>
                            <h4>Survey Report Abuse List</h4>
                        </div>

                        <div>
                            <ul class="nav nav-tabs tabs-bordered" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="all" data-toggle="tab" href="#"
                                        role="tab" aria-selected="true" onclick="getData('all')">
                                        <span class="d-none d-sm-block">All</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="active" data-toggle="tab" href="#" role="tab"
                                        aria-controls="profile-b1" aria-selected="false" onclick="getData('active')">
                                        <span class="d-none d-sm-block">Active</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="inactive" data-toggle="tab" href="#" role="tab"
                                        aria-controls="message-b1" aria-selected="false" onclick="getData('inactive')">
                                        <span class="d-none d-sm-block">Inactive</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="project-report-table">
                                <thead>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

    </div>
@endsection

@section('modal')
    @include('admin.survey_reports.modal.modal')
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        function getData(type) {

            let table = $('#project-report-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                destroy: true,
                stateSave: true,

                ajax: {
                    url: "/abuse-reports/list/" + type
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: 'DT_RowIndex',
                        title: "Serial",
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                        title: 'User',
                        orderable: false
                    },
                    {
                        data: 'user_email',
                        name: 'user_email',
                        title: 'User Email',
                        orderable: false
                    },
                    {
                        data: 'status',
                        title: 'User Status',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'project_name',
                        name: 'project_name',
                        title: 'Project Name',
                        orderable: false
                    },
                    {
                        data: 'code',
                        title: 'Code',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'report_abuse_count',
                        title: 'Report Count',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'project_link_status',
                        title: 'Survey link status',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'action',
                        title: 'Action',
                        searchable: false,
                        orderable: false
                    },
                ]
            });

            // check previous type and draw datatable accordingly
            let prev_type = localStorage.getItem("prev_type");
            if(prev_type){
                if(prev_type != type){
                    // Clear the saveState
                    table.state.clear();

                    // Redraw the table
                    table.draw();
                }
            }
            localStorage.setItem('prev_type', type);

            // redraw the datatable if last page is greater than current page or changes route		
            table.on('draw.dt', function () {
                if(table.page.info().page+1 > table.page.info().pages){
                    // Clear the saveState
                    table.state.clear();

                    // Redraw the table
                    table.page(table.page.info().pages - 1).draw(false)
                }
            });
        }

        $(document).ready(function() {

            getData("all");

        });


        function changeProjectLinkStatus(id) {
            $.ajax({
                type: "POST",
                url: "/project-link/" + id + "/status-change",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    let type = $(".nav-link.active").attr('id');

                    getData(type);
                }
            });
        }


        function changeUserStatus(id) {
            $.ajax({
                type: "POST",
                url: "/users/" + id + "/status-change",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    let type = $(".nav-link.active").attr('id');

                    getData(type);
                }
            });
        }


        function reportLogs(project_link_id) {
            $.ajax({
                type: "GET",
                url: "/abuse-reports-records/top-five/" + project_link_id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('.modal-body').html(data);
                    $('#abuseReportModal').modal('show');
                }
            });

        }
    </script>
@endpush

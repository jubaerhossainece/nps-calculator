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
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>User name</th>
                                        <td id="uname">
                                            {{ $projectLink->user_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td id="uemail">
                                            {{ $projectLink->user_email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Project Name</th>
                                        <td id="project_name">
                                            {{ $projectLink->project_name }}
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="full-report-table">
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

@push('scripts')
    <script>
        function getData(id) {

            $('#full-report-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                destroy: true,
                // order: [4, "desc"],

                ajax: {
                    url: "/abuse-reports-records/all/" + id
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: 'DT_RowIndex',
                        title: "Serial",
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'report_type_id',
                        title: 'Report Type',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'comment',
                        title: 'Comment',
                        searchable: true,
                        orderable: false
                    },

                ]
            });
        }

        $(document).ready(function() {
            const url = new URL(window.location.href);
            const id = url.pathname.split("/").pop();
            getData(id);

        });

        function changeStatus(id) {

            $.ajax({
                type: "POST",
                url: "/audiences/" + id + "/status-change",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    let type = $(".nav-link.active").attr('id');

                    getData(type);
                }
            });

        }


        function changeProjectLinkStatus(id) {
            console.log(id);

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

@extends('admin.layouts.app')
@section('styles')

@endsection

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Project List</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb p-0 m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Index</li>
                        </ol>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="project-table">
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

	function getData(){
		
		$('#project-table').DataTable({
		processing: true,
		serverSide: true,
		autoWidth: true,
		destroy: true,
		searching: false,
		// order: [4, "desc"],
		
		ajax: {
			url : "{{ route('user.projects.list', $user) }}"
		},
		columns: [
				{data: "DT_RowIndex",name:'DT_RowIndex', title: "Serial", searchable: false, orderable: false},
				{data: 'name', title:'Project Name',orderable: false},
				{data: 'feedbacks_count', title:'Total nps collect',orderable: false},
                {data: 'status', title:'Status',orderable: false},
                {data: 'action', title:'Action',orderable: false},

			]
		});
	}

	$(document).ready(function(){        
		getData();
	});

	$(".changeable_field").change(function(){
		$("#project-table").empty()
		getData();
	})
</script>
@endpush
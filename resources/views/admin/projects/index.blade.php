@extends('admin.layouts.app')
@section('styles')
<style>
    .h3{
        margin-bottom: 0px;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row mt-4 mb-3">
            <div class="col-12">
                
                <div class="card">
                    <div class="card-header bg-white">
                        <div>
                            <p class="h3">{{$user->name}}</p>
                            <span>{{$user->email}}</span>
                        </div>
                    </div>
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
			url : "{{ route('user.projects.list', $user->id) }}"
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
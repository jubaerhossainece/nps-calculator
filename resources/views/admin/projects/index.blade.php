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

		let from_date = $('#from_date').val();
		let to_date = $('#to_date').val();
		
		$('#project-table').DataTable({
		processing: true,
		serverSide: true,
		autoWidth: true,
		destroy: true,
		order: [4, "desc"],
		
		ajax: {
			url : "{{ route('projects.list') }}",
			data: function(d) { d.from_date = from_date, d.to_date = to_date }
		},
		columns: [
				{data: "DT_RowIndex",name:'DT_RowIndex', title: "Serial", searchable: false, orderable: false},
				{data: 'name', title:'Name',orderable: false},
				{data: 'logo', title:'Name',orderable: false},
				{data: 'wt_visibility', title:'Phone', orderable: false},
				{data: 'name_field_visibility', title:'Email', orderable: false},
				{data: 'email_field_visibility', title:'Total coin spent', searchable: false},
				{data: 'comment_field_visibility', title:'Total coin spent', searchable: false},
				{data: 'welcome_text', title:'Total coin spent', searchable: false},
			]
		});
	}

	$(document).ready(function(){
		$('.datePicker').datepicker({
			format: "yyyy-mm-dd",
			autoclose: true,
			todayHighlight: true
		});
        
		getData();
	});

	$(".changeable_field").change(function(){
		$("#project-table").empty()
		getData();
	})
</script>
@endpush
@extends('admin.layouts.app')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row mt-4 mb-3">
            <div class="col-12">
                
                <div class="card">
					<div class="card-header d-flex justify-content-between bg-white">
						<div>
							<h4>Audience List</h4>
						</div>

						<div>
							<ul class="nav nav-tabs tabs-bordered" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="all" data-toggle="tab" href="#" role="tab" aria-selected="true" onclick="getData('all')">
										<span class="d-none d-sm-block">All</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="active" data-toggle="tab" href="#" role="tab" aria-controls="profile-b1" aria-selected="false" onclick="getData('active')">
										<span class="d-none d-sm-block">Active</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="inactive" data-toggle="tab" href="#" role="tab" aria-controls="message-b1" aria-selected="false" onclick="getData('inactive')">
										<span class="d-none d-sm-block">Inactive</span>
									</a>
								</li>
							</ul>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>

	function getData(type){
		
		$('#project-table').DataTable({
		processing: true,
		serverSide: true,
		autoWidth: true,
		destroy: true,
		// order: [4, "desc"],
		
		ajax: {
			url : "/audiences/list/"+type
		},
		columns: [
				{data: "DT_RowIndex",name:'DT_RowIndex', title: "Serial", searchable: false, orderable: false},
				{data: 'name', title:'Name',orderable: false},
				{data: 'email', title:'Email',orderable: false},
				{data: 'projects', title:'Total Project',orderable: false},
				{data: 'feedbacks', title:'Total NPS Collect',orderable: false},
				
				{data: 'status', title:'Status',orderable: false},
				{data: 'action', title:'Action',orderable: false},
			]
		});
	}

	$(document).ready(function(){
        
		getData("all");

	});

	function changeStatus(id){

		$.ajax({
			type: "POST",
			url: "/audiences/"+id+"/status-change",
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			success: function(data){
				let type = $(".nav-link.active").attr('id');
				
				getData(type);
			}
		});
  
	}

</script>
@endpush
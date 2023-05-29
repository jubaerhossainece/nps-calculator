<div class="btn-group dropleft" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class=" fas fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
      <a class="dropdown-item" href="" onclick="changeStatus({{$report->user_id}}); return false;">{{$report->status ? 'Deactivate User' : 'Activate User'}}</a>
      <a class="dropdown-item" href="" onclick="changeCodeStatus({{$report->code}}); return false;">{{$report->status ? 'Deactivate User' : 'Activate User'}}</a>
    </div>
</div>
<div class="btn-group dropleft" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class=" fas fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
      <!-- <a class="dropdown-item" href="" onclick="changeStatus({{$report->project->user->id}}); return false;">{{$report->project->user->status ? 'Deactivate user' : 'Activate user'}}</a> -->
      <a class="dropdown-item" href="" onclick="changeProjectLinkStatus({{$report->id}}); return false;">{{$report->status ? 'Deactivate project link' : 'Activate project link'}}</a>
      <span class="dropdown-item"  onclick="reportLogs({{$report->id}}); return false;" style="cursor: pointer;">Reports</span>
    </div>
</div>
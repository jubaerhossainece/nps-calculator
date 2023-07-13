<div class="btn-group dropleft" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class=" fas fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
      <a class="dropdown-item" href="{{route('user.projects', $user->id)}}" >View project list</a>
      <a class="dropdown-item" href="" onclick="changeStatus({{$user->id}}); return false;">{{$user->status ? 'Deactivate' : 'Activate'}}</a>
    </div>
  </div>
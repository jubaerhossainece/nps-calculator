<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Report type</th>
            <th>Comment</th>
        </tr>
       @foreach ($link_reports as $report )
       <tr>
          <td>{{ $report->getReportTypeUsingId($report->id) }}</td>
          <td>{{ $report->comment }}</td>
       </tr>           
       @endforeach
    </thead>
</table>
<div class="text-center">
    <a href="{{ route('abuse-reports-records',['project_link_id'=>$project_link_id]) }}" class="btn btn-primary">Show full list</a>
</div>
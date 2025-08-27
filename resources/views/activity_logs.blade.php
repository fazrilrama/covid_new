@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Activity Log</h1>
@stop

@section('content')
			
    <div style="float: right;" class="btn-group" role="group">
      <a href="JavaScript:poptastic('{{ url('/activity_logs/print') }}')" type="button" class="btn btn-primary" title="Copy"">
        <i class="fa fa-download"></i> Cetak
      </a>
    </div>
    <div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>User:</th>
	                <th>Activity:</th>
	                <th width="50%">Properties:</th>
	                <th>Model:</th>
	                <th>Created At:</th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $item)
	            <tr>
	                <td>{{$item->id}}</td>
	                <td>{{ !empty($item->causer->user_id) ? $item->causer->user_id : '' }}</td>
	                <td>{{$item->description}}</td>
	                <td>{{$item->properties}}</td>
	                <td>{{$item->subject_type}}</td>
	                <td>{{$item->created_at}}</td>
	            </tr>
	        @endforeach
	      </tbody>
	    </table>
	</div>

<script type="text/javascript">
    var newwindow;
    function poptastic(url)
    {
        newwindow=window.open(url,'name','height=800,width=1600');
        if (window.focus) {newwindow.focus()}
    }
</script>  
@stop
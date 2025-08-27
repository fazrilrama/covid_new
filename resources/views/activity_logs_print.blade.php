
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
    <script>
        window.print();
    </script>
<div class="table-responsive">
  <table class="data-table table table-bordered table-hover no-margin" width="100%">

      <thead>
          <tr>
            <th>Item SKU:</th>
            <th>Item Name:</th>
            <th>Group Ref:</th>
            <th>Qty:</th>
            <th>UOM:</th>
            <th>Weight:</th>
            <th>Volume:</th>
            <th></th>
          </tr>
      </thead>
      
      <tbody>
      @foreach($advanceNotice->details->where('status', '<>', 'canceled') as $detail)
          <tr>
              <td>{{$detail->item->sku}}</td>
              <td>{{$detail->item->name}}</td>
              <td>{{$detail->ref_code}}</td>
              <td>{{$detail->qty}}</td>
              <td>{{$detail->uom->name}}</td>
              <td>{{$detail->weight}}</td>
              <td>{{$detail->volume}}</td>
              <td>
                @if($advanceNotice->status != 'Completed' && $advanceNotice->status != 'Canceled')
                <div class="btn-toolbar">
                    <div class="btn-group" role="group">
                        <a href="{{url('advance_notice_details/'.$detail->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                    <div class="btn-group" role="group">
                        <form action="{{url('advance_notice_details', [$detail->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                        </form>
                    </div>
                </div>
                @endif
              </td>
          </tr>
      @endforeach
      </tbody>
    </table>
</div>
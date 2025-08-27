@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>
    Daftar Storage
</h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
                <tr>
                    <th>Storage ID:</th>
                    <th>Warehouse ID:</th>
                    <th>Warehouse:</th>
                    <th>Status </th>
                    <th>Ubah Status</th>
                </tr>
            </thead>
	        
	        <tbody>
                @foreach($storages as $item)
                    <tr>
                        <td>{{$item->code}}</td>
                        <td>{{empty($item->warehouse) ?'': $item->warehouse->code}}</td>
                        <td>{{empty($item->warehouse) ?'': $item->warehouse->name}}</td>
                        <td>
                            @if($item->status == 1)
                                Buka
                            @elseif($item->status == 0)
                                Tutup
                            @endif
                        </td>
                        <td>
                            @if($item->status == 1 && $type == 'inbound')
                                <div class="btn-toolbar">
                                    <div class="btn-group" role="group">
                                        <form action="{{route('change_storage_status')}}" method="POST" onclick="return confirm('Anda yakin ingin menutup storage ini?');">
                                            <input type="hidden" name="_method" value="PUT">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="storage_id" value="{{ $item->id }}">
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="type" value="inbound">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-undo"></i></a></button>
                                        </form>
                                    </div>  
                                </div>
                            @elseif($item->status == 0 && $type == 'outbound')
                                <div class="btn-toolbar">
                                    <div class="btn-group" role="group">
                                        <form action="{{route('change_storage_status')}}" method="POST" onclick="return confirm('Anda yakin ingin membuka storage ini?');">
                                            <input type="hidden" name="_method" value="PUT">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="storage_id" value="{{ $item->id }}">
                                            <input type="hidden" name="status" value="1">
                                            <input type="hidden" name="type" value="outbound">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-undo"></i></a></button>
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

@endsection
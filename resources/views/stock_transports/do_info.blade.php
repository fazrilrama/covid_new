@if($type == 'inbound' && \Request::route()->getName() != 'stock_transports.create')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">DO Info</h3>
        <br><br>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
        <!-- /.box-tools -->
        <form id="changeAttributeGoodReceiving" action="{{ $action }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            @if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
                {{ method_field($method) }}
            @endif
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="do_number" class="col-sm-3 control-label">DO Number</label>
                            <div class="col-sm-9">
                                <input type="text" name="do_number" id="do_number" class="form-control" placeholder="Do Number" value="{{old('do_number', $stockTransport->do_number)}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="do_attachment" class="col-sm-3 control-label">DO Attachment</label>
                            <div class="col-sm-9">
                                <input type="file" name="do_attachment" class="form-control" placeholder="SPTB" value="{{old('do_attachment', $stockTransport->do_attachment)}}">
                                @if(!empty($stockTransport->do_attachment))
                                <a href="{{ \Storage::disk('public')->url($stockTransport->do_attachment) }}" target="_blank">See Attachment</a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="driver_name" class="col-sm-3 control-label">Driver Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="driver_name" id="driver_name" class="form-control" placeholder="Driver Name" value="{{old('driver_name', $stockTransport->driver_name)}}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="driver_phone" class="col-sm-3 control-label">Driver Phone</label>
                            <div class="col-sm-9">
                                <input type="number" name="driver_phone" id="driver_phone" class="form-control" placeholder="Driver Phone" value="{{old('driver_phone', $stockTransport->driver_phone)}}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="driver_phone" class="col-sm-3 control-label">Sim Number</label>
                            <div class="col-sm-9">
                                <input type="number" name="sim_number" id="sim_number" class="form-control" placeholder="Sim Number" value="{{old('sim_number', $stockTransport->sim_number)}}" >
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="police_number" class="col-sm-3 control-label">Police Number</label>
                            <div class="col-sm-9">
                                <input type="text" name="police_number" id="police_number" class="form-control" placeholder="Police Number" value="{{old('police_number', $stockTransport->police_number)}}" >
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label for="wp_number" class="col-sm-3 control-label">Weight of Proof Number</label>
                            <div class="col-sm-9">
                                <input type="text" name="wp_number" id="wp_number" class="form-control" placeholder="Weight of Proof Number" value="{{old('wp_number', $stockTransport->wp_number)}}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fleet_arrived" class="col-sm-3 control-label">Fleet of Arrival</label>
                            <div class="col-sm-9">
                                <input type="text" name="fleet_arrived" id="fleet_arrived" class="form-control" placeholder="Fleet of Arrival" value="{{old('fleet_arrived', $stockTransport->fleet_arrived)}}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="unloading_start" class="col-sm-3 control-label">Unloading Start</label>
                            <div class="col-sm-9">
                                <input type="text" name="unloading_start" id="unloading_start" class="form-control" placeholder="Unloading Start" value="{{old('unloading_start', $stockTransport->unloading_start)}}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="unloading_end" class="col-sm-3 control-label">Unloading Finish</label>
                            <div class="col-sm-9">
                                <input type="text" name="unloading_end" id="unloading_end" class="form-control" placeholder="Unloading Finish" value="{{old('unloading_end', $stockTransport->unloading_end)}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-info btn-completed" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endif
<div class="modal fade" id="modal-assignto">
    <div class="modal-dialog modal-sm">
        <form action="{{ $url }}" method="POST" id="assignto" role="form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">ASSIGN TO</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <p align="center">
                        Harap memilih WH Supervisor untuk di assign
                    </p>
                    @if(isset($advanceNotice) && $advanceNotice->type == 'outbound')
                    <div class="form-group required">
                        <select class="form-control" name="warehouse_id" id="warehouse_id" required>
                            <option value="" selected disabled>-- Pilih Warehouse --</option>
                            @foreach($warehouses as $warehouse)
                            @if($warehouse->id == $advanceNotice->warehouse_id)
                            <option value="{{$warehouse->id}}" selected>{{$warehouse->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="form-group required">
                        <select class="form-control" name="warehouse_id" id="warehouse_id" required>
                            <option value="" selected disabled>-- Pilih Warehouse --</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                            @endforeach
                        </select>
                    </div>  
                    @endif
                    <div id="select_warehouse_officer"></div>                  
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-flat">Lanjutkan</button>
                </div>
            </div>
        </form>
    </div>
</div>
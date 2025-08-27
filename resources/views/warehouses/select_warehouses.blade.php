<div class="form-group required">
    <label for="warehouse_id" class="col-sm-3 control-label">
        Warehouse
    </label>
    <div class="col-sm-9">
        <select class="form-control" name="warehouse_id" id="warehouse_id" required="required">
            <option value="" disabled selected>-- Pilih Warehouse</option>
            @foreach($warehouses as $wh)
            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
            @endforeach
        </select>
    </div>
</div>
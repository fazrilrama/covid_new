<div class="form-group required">
    <select class="form-control" name="employee_name" id="employee_name" required>
        <option value="" selected disabled>-- Pilih Warehouse Supervisor --</option>
        @foreach($warehouse_officer as $wo)
        <option data-spv="{{ $wo->user->id }}" value="{{$wo->user->first_name}} {{$wo->user->last_name}}">{{$wo->user->user_id}}</option>
        @endforeach
    </select>
    <input type="hidden" name="employee_id" id="select-wh-id">
</div>
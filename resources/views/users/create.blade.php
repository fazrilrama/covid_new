@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>Create User</h1>
@stop

@section('content')
<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
  @if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
  {{ method_field($method) }}
  @endif
  @csrf

  <div class="row">
   <div class="col-md-12">
    <div class="box box-default">
     <div class="box-header with-border">
      <h3 class="box-title">Informasi Data</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div>
      <!-- /.box-tools -->
    </div>
    @include('users.form')
  </div>
</div>
<div class="col-md-12">
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Role User</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div>
      <!-- /.box-tools -->
    </div>
    <div class="box-body">
      <div class="form-group">
        <div class="col-sm-12">
          <select class="form-control" name="roles" id="roles" required>
            <option value="" disabled selected>-- Pilih Roles --</option>
            @foreach($roles as $value)
            <option value="{{$value->id}}">{{$value->name}}</option>
            @endforeach
          </select>
        </div>
      </div>
      @foreach($permissions as $value)
      <div class="col-md-3 checkbox-roles">
        <input type="checkbox" id="{{$value->id}}" name="permissions[]" value="{{$value->id}}" @if($user->roles->contains($value->id)){{'checked'}}@endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$value->name}}
      </div>
      @endforeach
    </div>
  </div>
</div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-footer">
        <button type="submit" class="btn btn-info pull-right">Simpan</button>
      </div>
      <!-- /.box-footer -->
    </div>
  </div>
</div>
</form>
@endsection


@section('js')
<script>
  $('#roles').on('change', function(){
    $('input:checkbox').removeAttr('checked');
    $.ajax({
      method: 'GET',
      url: '/permission/' + $(this).val(),
      dataType: "json",
      success: function(data){
        if (data.length >= 1) {
          for (var i = 0; i <= data.length; i++) {
            $("#"+data[i].permission_id).prop('checked',true);
          }
        } else {
          alert("Data Roles tidak ditemukan");
        }
      },
      error:function(){
        console.log('error '+ data);
      }
    });
  });

  function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
</script>
@endsection
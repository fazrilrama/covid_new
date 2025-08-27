@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Tambah Barang</h1>
@stop

@section('content')
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
			@include('stock_entry_details.form')
		</div>
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
  $(document).ready(function() {
        $('select[name="warehouse_id"]').on('change', function() {
            // $('select[name="warehouse_id"]').attr('disabled', 'true');
            // $('select[name="storage_id"]').attr('disabled', 'true');
            $('.btn-info').attr('disabled', 'true');
            var warehouseId = $(this).val();
            if(warehouseId) {
                $.ajax({
                    url: '/select-warehouse/'+warehouseId,
                    type: "GET",
                    data: {
                        'item_id': $('select[name="item_id"]').val(),
                        'doc_type': $('input[name="doc_type"]').val(),
                    },
                    dataType: "json",
                    success:function(data) {
                        $('select[name="storage_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="storage_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });

                        $('select[name="warehouse_id"]').removeAttr('disabled');
                        $('select[name="storage_id"]').removeAttr('disabled');
                        $('.btn-info').removeAttr('disabled');
                    },
                    error:function(error){
                        $('select[name="warehouse_id"]').removeAttr('disabled');
                        $('select[name="storage_id"]').removeAttr('disabled');
                        $('.btn-info').removeAttr('disabled');
                    }
                });
            }else{
                $('select[name="storage_id"]').empty();
            }
        });

        $('.btn-info').attr('disabled', 'true');
        var warehouseId = $(this).val();
        if(warehouseId) {
            $.ajax({
                url: '/select-warehouse/'+warehouseId,
                type: "GET",
                data: {
                    'item_id': $('select[name="item_id"]').val(),
                    'doc_type': $('input[name="doc_type"]').val(),
                },
                dataType: "json",
                success:function(data) {
                    $('select[name="storage_id"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="storage_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });

                    $('select[name="warehouse_id"]').removeAttr('disabled');
                    $('select[name="storage_id"]').removeAttr('disabled');
                    $('.btn-info').removeAttr('disabled');
                },
                error:function(error){
                    $('select[name="warehouse_id"]').removeAttr('disabled');
                    $('select[name="storage_id"]').removeAttr('disabled');
                    $('.btn-info').removeAttr('disabled');
                }
            });
        }else{
            $('select[name="storage_id"]').empty();
        }
    });
</script>
@endsection
@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')
<style>
    .files input {
    outline: 2px dashed #92b0b3;
    outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear;
    padding: 120px 0px 85px 35%;
    text-align: center !important;
    margin: 0;
    width: 100% !important;
}
.files input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
 }
.files{ position:relative}
.files:after {  pointer-events: none;
    position: absolute;
    top: 60px;
    left: 0;
    width: 50px;
    right: 0;
    height: 56px;
    content: "";
    background-image: url(https://image.flaticon.com/icons/png/128/109/109612.png);
    display: block;
    margin: 0 auto;
    background-size: 100%;
    background-repeat: no-repeat;
}
.color input{ background-color:#f1f1f1;}
.files:before {
    position: absolute;
    bottom: 10px;
    left: 0;  pointer-events: none;
    width: 100%;
    right: 0;
    height: 57px;
    display: block;
    margin: 0 auto;
    font-weight: 600;
    text-transform: capitalize;
    text-align: center;
}
</style>
@section('content_header')
<h1>@if($type=='inbound') AIN @else AON @endif List
    
    <form action="{{route('advance_notices.index',$type)}}" method="GET">
        @if(Auth::user()->hasRole('CargoOwner'))
            <a href="{{url('advance_notices/create/'.$type)}}" type="button" class="btn btn-sm btn-success" title="Create">
                <i class="fa fa-plus"></i> Tambah
            </a>
        @elseif(Auth::user()->hasRole('WarehouseSupervisor') && $type=='inbound')
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalBulkData">
            <i class="fa fa-plus"></i> Tambah dari Format Data
            </button>
        @endif
        @if(!Auth::user()->hasRole('CargoOwner'))
        <button class="btn btn-sm btn-warning" name="submit" value="1">
            <i class="fa fa-download"></i> Export ke Excel
        </button>
        @endif
    </form>
</h1>
@stop
@section('adminlte_css')
    <link rel='stylesheet' href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/buttons.dataTables.min.css') }}">
@endsection


@section('content')
	<div class="table-responsive">
	    {!! $dataTables->table(['id'=> 'table-stockonlocation', 'class' => 'table table-bordered table-hover no-margin','style' => 'width:100%', 'cellspacing' => '0']) !!}
	</div>
    <div class="modal fade" id="modalBulkData" tabindex="-1" role="dialog" aria-labelledby="modalBulkData" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBulkData">Import Format Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('advance_notices.uploadAin') }}" id="#" enctype="multipart/form-data">
            @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group files">
                                <label>Upload Your File </label>
                                <input name="file" type="file" class="form-control" multiple="" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                            </div>    
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Import</button>
                </div>
            </form>

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
	@yield('js')
	<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('vendor/datatables/js/input.js') }}"></script>
	<script src="{{ asset('vendor/datatables/js/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
	{!! $dataTables->scripts() !!}
	<script>
         function($) {
            'use strict';

            // UPLOAD CLASS DEFINITION
            // ======================

            var dropZone = document.getElementById('drop-zone');
            var uploadForm = document.getElementById('js-upload-form');

            var startUpload = function(files) {
                console.log(files)
            }

            uploadForm.addEventListener('submit', function(e) {
                var uploadFiles = document.getElementById('js-upload-files').files;
                e.preventDefault()

                startUpload(uploadFiles)
            })

            dropZone.ondrop = function(e) {
                e.preventDefault();
                this.className = 'upload-drop-zone';

                startUpload(e.dataTransfer.files)
            }

            dropZone.ondragover = function() {
                this.className = 'upload-drop-zone drop';
                return false;
            }

            dropZone.ondragleave = function() {
                this.className = 'upload-drop-zone';
                return false;
            }

        }(jQuery);
	</script>
@endsection

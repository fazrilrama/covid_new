<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>
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
<?php $__env->startSection('content_header'); ?>
<h1><?php if($type=='inbound'): ?> AIN <?php else: ?> AON <?php endif; ?> List
    
    <form action="<?php echo e(route('advance_notices.index',$type)); ?>" method="GET">
        <?php if(Auth::user()->hasRole('CargoOwner')): ?>
            <a href="<?php echo e(url('advance_notices/create/'.$type)); ?>" type="button" class="btn btn-sm btn-success" title="Create">
                <i class="fa fa-plus"></i> Tambah
            </a>
        <?php elseif(Auth::user()->hasRole('WarehouseSupervisor') && $type=='inbound'): ?>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalBulkData">
            <i class="fa fa-plus"></i> Tambah dari Format Data
            </button>
        <?php endif; ?>
        <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
        <button class="btn btn-sm btn-warning" name="submit" value="1">
            <i class="fa fa-download"></i> Export ke Excel
        </button>
        <?php endif; ?>
    </form>
</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('adminlte_css'); ?>
    <link rel='stylesheet' href="<?php echo e(asset('vendor/datatables/css/jquery.dataTables.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('vendor/datatables/css/buttons.dataTables.min.css')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
	<div class="table-responsive">
	    <?php echo $dataTables->table(['id'=> 'table-stockonlocation', 'class' => 'table table-bordered table-hover no-margin','style' => 'width:100%', 'cellspacing' => '0']); ?>

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
            <form method="post" action="<?php echo e(route('advance_notices.uploadAin')); ?>" id="#" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
	<?php echo $__env->yieldContent('js'); ?>
	<script src="<?php echo e(asset('vendor/datatables/js/jquery.dataTables.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/datatables/js/input.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/datatables/js/dataTables.buttons.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/select2/js/select2.min.js')); ?>"></script>
	<?php echo $dataTables->scripts(); ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>Create User</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
  <?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
  <?php echo e(method_field($method)); ?>

  <?php endif; ?>
  <?php echo csrf_field(); ?>

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
    <?php echo $__env->make('users.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($value->id); ?>"><?php echo e($value->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
      </div>
      <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col-md-3 checkbox-roles">
        <input type="checkbox" id="<?php echo e($value->id); ?>" name="permissions[]" value="<?php echo e($value->id); ?>" <?php if($user->roles->contains($value->id)): ?><?php echo e('checked'); ?><?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($value->name); ?>

      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
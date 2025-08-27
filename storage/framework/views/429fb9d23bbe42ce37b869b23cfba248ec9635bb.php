<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Activity Log</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
			
    <div style="float: right;" class="btn-group" role="group">
      <a href="JavaScript:poptastic('<?php echo e(url('/activity_logs/print')); ?>')" type="button" class="btn btn-primary" title="Copy"">
        <i class="fa fa-download"></i> Cetak
      </a>
    </div>
    <div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>User:</th>
	                <th>Activity:</th>
	                <th width="50%">Properties:</th>
	                <th>Model:</th>
	                <th>Created At:</th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e($item->id); ?></td>
	                <td><?php echo e(!empty($item->causer->user_id) ? $item->causer->user_id : ''); ?></td>
	                <td><?php echo e($item->description); ?></td>
	                <td><?php echo e($item->properties); ?></td>
	                <td><?php echo e($item->subject_type); ?></td>
	                <td><?php echo e($item->created_at); ?></td>
	            </tr>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<script type="text/javascript">
    var newwindow;
    function poptastic(url)
    {
        newwindow=window.open(url,'name','height=800,width=1600');
        if (window.focus) {newwindow.focus()}
    }
</script>  
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
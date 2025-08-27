
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
    <script>
        window.print();
    </script>
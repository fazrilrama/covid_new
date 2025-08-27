<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Laporan POD per No Good Isue</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startSection('additional_field'); ?>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="branch" class="col-sm-4 col-form-label">Project</label>
                <div class="col-sm-8">
                    <select name="project" id="project" class="form-control select2">
                        <option value="all" selected>--Semua Project--</option>
                    </select>
                    <input type="hidden" id="selected_project" value="<?php echo e(!empty($data['project']) ? $data['project'] : ''); ?>">
                    
                </div>
            </div>
        
        </div>
        <input type="hidden" id="report_branch" value="<?php echo e(route('project_list')); ?>">
    <?php $__env->stopSection(); ?>

    <?php echo $__env->make('report.searchDateForm', ['print_this' => true], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    

    <?php if($search): ?>
    <div class="table-responsive">
        <table class="data-table table table-bordered table-hover no-margin" width="100%">
            <thead>
                <tr>
                    <th>Tanggal:</th>
                    <th>Sisa Pengiriman Awal:</th>
                    <th>Pengiriman Dibuat:</th>
                    <th>Sampai Tujuan:</th>
                    <th>Outstanding</th>
                    <th>Closing</th>
                    <th>Detail:</th>
                </tr>
            </thead>
        
            <tbody> 
                <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                <td><?php echo e($collection['date']); ?></td>
                <td><?php echo e($collection['begining']); ?></td>
                <td><?php echo e($collection['receiving']); ?></td>
                <td><?php echo e($collection['delivery']); ?></td>
                <td><?php echo e($collection['begining'] + $collection['receiving'] - $collection['delivery']); ?></td>
                <td><?php echo e($collection['closing']); ?></td>
                    <td><a href="<?php echo e(route('report.detail_good_issue_mutation', ['date' => $collection['date'], 'project'=>$collection['project']])); ?>" type="button" class="btn btn-primary" title="View" target="_blank">
                        <i class="fa fa-eye"></i>
                    </a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
    <script>
        $.ajax({
            method: 'GET',
            url: $('#report_branch').val(),
            dataType: "json",
            success: function(data){
                // $("#warehouses").empty();
                $.each(data,function(i, value){
                    if($('#selected_project').val() == value.project_id) {
                        $("#project").append("<option value='"+value.project_id+"' selected>"+value.name+"</option>");
                    } else {
                        $("#project").append("<option value='"+value.project_id+"'>"+value.name+"</option>");
                    }
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
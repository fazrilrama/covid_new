<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1><?php if($type=='inbound'): ?> Advance Inbound Notice <?php else: ?> Advance Outbound Notice <?php endif; ?> List
    
    <form action="<?php echo e(route('advance_notices.index',$type)); ?>" method="GET">
        <?php if(Auth::user()->hasRole('CargoOwner') || (Auth::user()->hasRole('WarehouseSupervisor'))): ?>
            <a href="<?php echo e(url('advance_notices/create/'.$type)); ?>" type="button" class="btn btn-sm btn-success" title="Create">
                <i class="fa fa-plus"></i> <?php echo e(__('lang.create')); ?>

            </a>
        <?php endif; ?>
    </form>
</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Processed</a></li>
  <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
  <li><a data-toggle="tab" href="#menu2">Closed</a></li>
  <?php endif; ?>
</ul>

<div class="tab-content">
    <div id="home" class="tab-pane fade in active">
        <div class="table-responsive">
            <table class="data-table table table-bordered table-hover no-margin" width="100%">
            
                <thead>
                    <tr>
                        <?php if(Auth::user()->hasRole('Reporting')): ?>
                        <th>NO#:</th>
                        <?php else: ?>
                        <th>ID#:</th>
                        <?php endif; ?>
                        <th><?php echo e(__('lang.no_refference')); ?>:</th>
                        <th><?php if($type=='inbound'): ?> AIN <?php else: ?> AON <?php endif; ?>:</th>
                        <th><?php if($type=='inbound'): ?> <?php echo e(__('lang.shipper')); ?>: <?php else: ?> <?php echo e(__('lang.consignee')); ?>: <?php endif; ?></th>
                        <th><?php echo e(__('lang.storage_area')); ?>:</th>
                        <th><?php echo e(__('lang.origin')); ?>:</th>
                        <th><?php echo e(__('lang.destination')); ?>:</th>

                        <!-- <th>ETD:</th>
                        <th>ETA:</th> -->
                        <th>Outstanding:</th>
                        <!-- <th>Arrived</th> -->
                        <?php if(Auth::user()->hasRole('CargoOwner')): ?>
                        <th>Status:</th>
                        <?php endif; ?>
                        <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
                        <th><?php echo e(__('lang.receiving_status')); ?>:</th>
                        <?php endif; ?>
                        <?php if(Auth::user()->hasRole('WarehouseManager')): ?>
                        <th><?php echo e(__('lang.assign_to')); ?>:</th>
                        <?php endif; ?>
                        <th><?php echo e(__('lang.created_at')); ?>:</th>
                        <th></th>
                    </tr>
                </thead>
                
                <tbody>
                <?php if(isset($collections)): ?>
                <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php if(Auth::user()->hasRole('Reporting')): ?>
                        <td><?php echo e($loop->iteration); ?></td>
                        <?php else: ?>
                        <td><?php echo e($item->id); ?></td>
                        <?php endif; ?>
                        <td><?php echo e($item->item_ref_code); ?></td>
                        <td><?php echo e($item->item_code); ?></td>
                        <td width="20%"><?php if($type=='inbound'): ?> <?php echo e($item->consingee_name); ?> <?php else: ?> <?php echo e($item->consingee_name); ?> <?php endif; ?></td>
                        <td>
                            <?php echo e((isset($item->item_storage_area)) ? $item->item_storage_area : '-'); ?>

                        </td>
                        <td><?php echo e(!empty($item->item_origin) ? $item->item_origin : ''); ?></td>
                        <td><?php echo e(!empty($item->item_destination) ? $item->item_destination : ''); ?></td>
                        <!-- <td><?php echo e($item->item_etd); ?></td>
                        <td><?php echo e($item->item_eta); ?></td> -->
                        <td align="center">
                            <?php echo e(number_format($item->item_outstanding, 2, ',', '.')); ?>

                            <p>
                                <select>
                                    <?php $__currentLoopData = $detail_advance_notices[$item->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option>
                                            <?php echo e($detail->item->name); ?> | <?php echo e($detail->ref_code); ?> | ots: <?php echo e($detail->detail_outstanding); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </p>
                        </td>
                        <!-- <td align="center">
                            <?php if($item->is_arrived == 0): ?>
                                No 
                            <?php else: ?>
                                Yes
                            <?php endif; ?>
                        </td> -->
                        <?php if(Auth::user()->hasRole('CargoOwner')): ?>
                        <td>
                                <?php echo e(($item->item_status == 'Pending' ? 'Planning' : ($item->item_status == 'Completed' ? 'Submitted' : $item->item_status))); ?>

                           
                        </td>
                        <?php endif; ?>
                        <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
                            <td width="15%">
                                <?php echo e(($item->item_outstanding == 0  && ($item->item_status == 'Completed' || $item->item_status == 'Closed') ? 'Full' : 'Partial')); ?>

                            </td>
                        <?php endif; ?>
                        <?php if(Auth::user()->hasRole('WarehouseManager')): ?>
                            <td><?php echo e(($item->item_employee_name)); ?></td>
                        <?php endif; ?>
                        <td> <?php echo e($item->item_created_at); ?></td>
                        
                        <td>
                            <div class="btn-toolbar">
                                <?php if(Auth::user()->id == $item->user_id || (Auth::user()->hasRole('WarehouseSupervisor') && session()->get('current_project')->id == '337')): ?>
                                    <?php if($item->editable): ?>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(url('advance_notices/' . $item->id .'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <form action="<?php echo e(url('advance_notices', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                                <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                            </form>
                                        </div>
                                        <?php else: ?>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(url('advance_notices/' . $item->id. '/show')); ?>" type="button" class="btn btn-primary" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(url('advance_notices/' . $item->id . '/show')); ?>" type="button" class="btn btn-primary" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
            </table>
        </div> 
    </div>
    <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
    <div id="menu2" class="tab-pane">
        <div class="table-responsive">
            <table class="data-table table table-bordered table-hover no-margin" width="100%">
            
                <thead>
                    <tr>
                        <?php if(Auth::user()->hasRole('Reporting')): ?>
                        <th>NO#:</th>
                        <?php else: ?>
                        <th>ID#:</th>
                        <?php endif; ?>
                        <th><?php echo e(__('lang.message')); ?>:</th>
                        <th><?php if($type=='inbound'): ?> AIN <?php else: ?> AON <?php endif; ?>:</th>
                        <th><?php if($type=='inbound'): ?> <?php echo e(__('lang.shipper')); ?>: <?php else: ?> <?php echo e(__('lang.consignee')); ?>: <?php endif; ?></th>
                        <th><?php echo e(__('lang.storage_area')); ?>:</th>
                        <th><?php echo e(__('lang.origin')); ?>:</th>
                        <th><?php echo e(__('lang.destination')); ?>:</th>

                        <!-- <th>ETD:</th>
                        <th>ETA:</th> -->
                        <th>Outstanding:</th>
                        <!-- <th>Arrived</th> -->
                        <?php if(Auth::user()->hasRole('CargoOwner')): ?>
                        <th>Status:</th>
                        <?php endif; ?>
                        <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
                        <th><?php echo e(__('lang.receiving_status')); ?>:</th>
                        <?php endif; ?>
                        <?php if(Auth::user()->hasRole('WarehouseManager')): ?>
                        <th><?php echo e(__('lang.assign_to')); ?>:</th>
                        <?php endif; ?>
                        <th><?php echo e(__('lang.created_at')); ?>:</th>
                        <th></th>
                    </tr>
                </thead>
                
                <tbody>
                <?php if(isset($collections_closed)): ?>
                <?php $__currentLoopData = $collections_closed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->id); ?></td>
                        <td><?php echo e($item->item_code); ?></td>
                        <td><?php echo e($item->item_ref_code); ?></td>
                        <td width="20%"><?php if($type=='inbound'): ?> <?php echo e($item->consingee_name); ?> <?php else: ?> <?php echo e($item->consingee_name); ?> <?php endif; ?></td>
                        <td>
                            <?php echo e((isset($item->item_storage_area)) ? $item->item_storage_area : '-'); ?>

                        </td>
                        <td><?php echo e(!empty($item->item_origin) ? $item->item_origin : ''); ?></td>
                        <td><?php echo e(!empty($item->item_destination) ? $item->item_destination : ''); ?></td>
                        <!-- <td><?php echo e($item->item_etd); ?></td>
                        <td><?php echo e($item->item_eta); ?></td> -->
                        <td align="center">
                            <?php echo e(number_format($item->item_outstanding, 2, ',', '.')); ?>

                            <p>
                                <select>
                                    <?php $__currentLoopData = $detail_advance_notices[$item->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option>
                                            <?php echo e($detail->item->name); ?> | <?php echo e($detail->ref_code); ?> | ots: <?php echo e($detail->detail_outstanding); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </p>
                        </td>
                        <!-- <td align="center">
                            <?php if($item->is_arrived == 0): ?>
                                No 
                            <?php else: ?>
                                Yes
                            <?php endif; ?>
                        </td> -->
                        <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
                            <td width="15%">
                                <?php echo e(($item->item_outstanding == 0  && ($item->item_status == 'Completed' || $item->item_status == 'Closed') ? 'Full' : 'Partial')); ?>

                            </td>
                        <?php endif; ?>
                        <?php if(Auth::user()->hasRole('WarehouseManager')): ?>
                            <td><?php echo e(($item->item_employee_name)); ?></td>
                        <?php endif; ?>
                        <td> <?php echo e($item->item_created_at); ?></td>
                        
                        <td>
                            <div class="btn-toolbar">
                                <?php if(Auth::user()->id == $item->user_id): ?>
                                    <?php if($item->editable): ?>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(url('advance_notices/' . $item->id .'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <form action="<?php echo e(url('advance_notices', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                                <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                            </form>
                                        </div>
                                        <?php else: ?>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(url('advance_notices/' . $item->id. '/show')); ?>" type="button" class="btn btn-primary" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(url('advance_notices/' . $item->id . '/show')); ?>" type="button" class="btn btn-primary" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
            </table>
        </div> 
    </div>
    <?php endif; ?>
</div
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
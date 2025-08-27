<?php $__env->startSection('adminlte_css'); ?>
    <link rel="stylesheet"
          href="<?php echo e(asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')); ?> ">
    <?php echo $__env->yieldPushContent('css'); ?>
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : '')); ?>

<?php $__env->startSection('body'); ?>
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>" class="navbar-brand">
                            <?php echo config('adminlte.logo', '<b>Admin</b>LTE'); ?>

                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <?php echo $__env->renderEach('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item'); ?>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            <?php else: ?>
            <!-- Logo -->
            <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><img src=<?php echo e(asset('logo-alt.png')); ?>></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img src=<?php echo e(asset('logo.png')); ?>></span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only"><?php echo e(trans('adminlte::adminlte.toggle_navigation')); ?></span>
                </a>
            <?php endif; ?>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <?php if(Auth::user()->hasRole(['WarehouseSupervisor', 'WarehouseOfficer'])): ?>
                            <li>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Gudang<span class="caret">
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a>   
                                            <?php echo e(session()->get('warehouse_name')); ?>

                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Branch<span class="caret">
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if(Auth::user()->branch): ?> 
                                        <li>
                                            <a>
                                                <?php echo e(Auth::user()->branch->name); ?>

                                            </a>
                                        </li> 
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li role="presentation" class="Form">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="">
                                    INFO <span class="caret"></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a style="padding:0px 10px">
                                        IP Address : <?php echo e(Request::ip()); ?>

                                    </a>
                                </li>
                                <li>
                                    <a style="padding:0px 10px">
                                        <?php echo e(\Carbon\Carbon::now()->format('l, j F Y h:i A')); ?>

                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
                            <li role="presentation" class="Form">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Notifications <span class="badge" style="font-size:9px !important;">0</span>
                                        
                                    </span>
                                </a>
                                
                            </li>
                        <?php endif; ?>
                        <?php if(Auth::user()->hasRole('WarehouseManager')): ?>
                            <li role="presentation" class="Form">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Notifications <span class="badge" style="font-size:9px !important;">0</span>
                                        
                                    </span>
                                </a>
                                
                            </li>
                        <?php endif; ?>
                        <?php if(Auth::user()->hasRole('Transporter')): ?>
                            <li role="presentation" class="Form">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Notifications <span class="badge" style="font-size:9px !important;">0</span>
                                        
                                    </span>
                                </a>
                                
                            </li>
                        <?php endif; ?>
                        <?php if(Auth::user()->hasRole('CargoOwner')): ?>
                            <li role="presentation" class="Form">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Notifications <span class="badge" style="font-size:9px !important;">0</span>
                                        
                                    </span>
                                </a>
                                
                            </li>
                        <?php endif; ?>                      
                        <?php if(!Auth::user()->hasRole('Superadmin')): ?>                  
                            <?php if(session()->get('projects')->count()>1): ?>
                                <li class="select-project-class">
                                    <select class="select2" id="select-project">
                                        <?php $__currentLoopData = session()->get('projects'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($project->id == session()->get('current_project')->id): ?>
                                                <option selected value="<?php echo e(url('profile/project/'.$project->id)); ?>">
                                                    <?php echo e($project->name); ?>

                                                </option>
                                            <?php else: ?>
                                                <option value="<?php echo e(url('profile/project/'.$project->id)); ?>">
                                                    <?php echo e($project->name); ?>

                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </li>
                            <?php else: ?>
                                <li>
                                    <li>
                                        <p class="navbar-text">
                                            <i class="fa fa-list-alt"></i> <?php echo e(empty(session()->get('current_project')) ?'': session()->get('current_project')->name); ?>

                                        </p>
                                    </li>
                                </li>
                            <?php endif; ?>
                            </li>
                        <?php endif; ?>
                        <li role="presentation" class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="">
                                    <i class="fa fa-user-circle"></i>
                                    <?php echo e(Auth::User()->user_id); ?>

                                    <span class="caret"></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo e(route('profile')); ?>" title="View Profile">Profile</a>
                                </li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <?php echo e(trans('adminlte::adminlte.log_out')); ?>

                                    </a>
                                    <form id="logout-form" action="<?php echo e(url(config('adminlte.logout_url', 'auth/logout'))); ?>" method="POST" style="display: none;">
                                        <?php if(config('adminlte.logout_method')): ?>
                                            <?php echo e(method_field(config('adminlte.logout_method'))); ?>

                                        <?php endif; ?>
                                        <?php echo e(csrf_field()); ?>

                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <?php if(config('adminlte.layout') == 'top-nav'): ?>
                </div>
                <?php endif; ?>
            </nav>
        </header>

        <?php if(config('adminlte.layout') != 'top-nav'): ?>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    <?php echo $__env->renderEach('adminlte::partials.menu-item', $adminlte->menu(), 'item'); ?>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        <?php endif; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            <div class="container">
            <?php endif; ?>

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <?php echo $__env->yieldContent('content_header'); ?>
            </section>

            <!-- Main content -->
            <section class="content">

                <?php echo $__env->make('includes.notification', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                <?php echo $__env->yieldContent('content'); ?>
            </section>
            
            <!-- /.content -->
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            </div>
            <!-- /.container -->
            <?php endif; ?>
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
              <b>Version</b> 1.0.0
            </div>
            <strong>© 2020 Powered By <a href="https://www.bgrlogistics.id/">BGR ACCESS</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('adminlte_js'); ?>
    <script src="<?php echo e(asset('vendor/adminlte/dist/js/adminlte.min.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('js'); ?>
    <?php echo $__env->yieldContent('js'); ?>
    <script>
        // the selector will match all input controls of type :checkbox
        // and attach a click event handler 
        $("input:checkbox.radiouser").on('click', function() {
          // in the handler, 'this' refers to the box clicked on
          var $box = $(this);
          if ($box.is(":checked")) {
            // the name of the box is retrieved using the .attr() method
            // as it is assumed and expected to be immutable
            var group = "input:checkbox[name='" + $box.attr("name") + "']";
            // the checked state of the group/box on the other hand will change
            // and the current value is retrieved using .prop() method
            $(group).prop("checked", false);
            $box.prop("checked", true);
          } else {
            $box.prop("checked", false);
          }
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('adminlte::master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
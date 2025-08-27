<!DOCTYPE html>
<html>
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-150476788-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-150476788-2');
    </script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
    @yield('title', config('adminlte.title', 'Internal System'))
    @yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">

    @if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
    @endif

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">

    @if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 style -->
    <link rel="stylesheet" href="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css">
    @endif

    <link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/a549aa8780dbda16f6cff545aeabc3d71073911e/build/css/bootstrap-datetimepicker.css">
    @yield('adminlte_css')

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="{{ asset('css/custom_style.css') }}">
</head>
<body class="hold-transition @yield('body_class')">


    @yield('body')

    <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/pace.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/pace.css') }}" />

    @if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    @endif

    @if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 renderer -->
    <script src="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script>
    @endif

    @if(config('adminlte.plugins.chartjs'))
    <!-- ChartJS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
    @endif

    <script type="text/javascript" src="/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    
    <!----- Datetimepicker Disabled -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/a549aa8780dbda16f6cff545aeabc3d71073911e/src/js/bootstrap-datetimepicker.js"></script>
    <!----- End Datetimepicker Disabled -->

    <script>
        var newwindow;
        var baseWeight = 0;
        var baseVolume = 0;
        function poptastic(url)
        {
            newwindow=window.open(url,'name','height=800,width=1600');
            if (window.focus) {newwindow.focus()}
        }
        $(document).ajaxStart(function() { Pace.restart(); });
        $(document).ready(function () {
            $.fn.dataTable.ext.errMode = 'none';

            Pace.options = {
                ajax: true,
                document: false, // disabled
                eventLag: false, // disabled
                elements: {
                    selectors: ['#item']
                }
            }

            $('.data-table').dataTable({
                "order": [[ 0, "desc" ]],
            });
            $('input').attr('autocomplete', 'off');
            $(".select2").select2(
                { width: '100%' }
            );

            $('.datepicker-normal').datepicker({
                autoclose: true,
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                onSelect: function(selected) {
                    $(".end-datepicker-normal").datepicker("option","minDate", selected)
                }
            });

            $('.end-datepicker-normal').datepicker({
                autoclose: true,
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                onSelect: function(selected) {
                    $(".datepicker-normal").datepicker("option","maxDate", selected)
                }
            });

            $('.datepicker').datepicker({
                minDate: 0,
                maxDate: '+1m',
                autoclose: true,
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                onSelect: function(selected) {
                    $(".end-datepicker").datepicker("option","minDate", selected);
                    $(".end-datepicker").datepicker("option","maxDate", selected+'+1m');
                },
                error:function(){
                    console.log('error '+ data);
                }
            });

            $('.gr-datepicker').datepicker({
                // minDate: 0,
                // maxDate: '+1m',
                autoclose: true,
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                // onSelect: function(selected) {
                //     $(".end-datepicker").datepicker("option","minDate", selected);
                //     $(".end-datepicker").datepicker("option","maxDate", selected+'+1m');
                // },
                error:function(){
                    console.log('error '+ data);
                }
            });

            $('.gr-datepicker-new').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate:new Date()
            });

            $('.end-datepicker').datepicker({
                minDate: 0,
                maxDate: '+1m',
                autoclose: true,
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                onSelect: function(selected) {
                    $(".datepicker").datepicker("option","maxDate", selected);
                },
            });

            $('.datetimepicker').datetimepicker({
                minDate:new Date()
            });

            $('.datepickercustom').datetimepicker({
                format: 'yyyy-mm-dd'
            });

            $(function () {     
                $('#datetimepickerdisable').datetimepicker({ 
                    format : 'YYYY-MM-DD HH:mm', 
                
                });
            });

            $(function () {     
                $('#datetimepickergr').datetimepicker({ 
                    format : 'YYYY-MM-DD HH:mm', 
                    minDate: moment().add('h')
                });
            });

            $('#fleet_arrived').datetimepicker({
                format : 'YYYY-MM-DD HH:mm'
            });

            $('#unloading_start').datetimepicker({
                format : 'YYYY-MM-DD HH:mm'
            });

            $('#unloading_end').datetimepicker({
                format : 'YYYY-MM-DD HH:mm'
            });

            $("#unloading_start").on("dp.change", function (e) {
                $('#unloading_end').prop('readonly', false);
                $('#unloading_end').data("DateTimePicker").minDate(e.date)
            });

            $(function () {     
                $('#datetimepickerptend').datetimepicker({ 
                    format : 'YYYY-MM-DD HH:mm', 
                });

                $(".datetimepickerdisable").on("dp.change", function (e) {
                    $('.datetimepickerptend').data("DateTimePicker").minDate(e.date)
                });
                
            });

                
            $("#party_id").on('change', function() {
                var value = $("#party_id").val();
                console.log(value);
                var warehouse_id = $("select[name='branch_id'] option:selected").attr('data-warehouse-id');
                
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "{{ route('get_region_city') }}",
                    type: "GET",
                    data: {
                        party_id: value,
                        warehouse_id: warehouse_id,
                    },
                    async: true,
                    success: function(res) {
                        $('#select_region_city').html(res);
                    }
                });
            });



            $("#warehouse_id").on('change', function() {
                var value = $("#warehouse_id").val();
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "{{ route('get_warehouse_officer') }}",
                    type: "GET",
                    data: {warehouse_id: value},
                    async: true,
                    success: function(res) {
                        $('#select_warehouse_officer').html(res);
                    }
                });
            });

            var value = $("#warehouse_id").val();

            if(value) {
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "{{ route('get_warehouse_officer') }}",
                    type: "GET",
                    data: {warehouse_id: value},
                    async: true,
                    success: function(res) {
                        $('#select_warehouse_officer').html(res);
                    }
                });
            }

            $(document).on('change', 'select[name="employee_name"]', function(){
                var id = $(this).find(':selected').data('spv');
                $('input[name="employee_id"]').val(id);
            });
                
            $("#region_id").on('change', function() {
                var value = $("#region_id").val();
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "{{ route('get_city') }}",
                    type: "GET",
                    data: {region_id: value},
                    async: true,
                    success: function(res) {
                        $('#select_city').html(res);
                    }
                });
            });

            $('body').on('change', '#region-id-warehouse', function(){
                var value = $(this).val();
                console.log(value, 'VALL');
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "/cities/get-list",
                    type: "GET",
                    data: {region_id: value},
                    async: true,
                    success: function(res) {
                        $('#select_city').html(res);
                    }
                });
            });

            $('#shipper_id').on('change', function(){
                $.ajax({
                    method: 'GET',
                    url: '/parties/get_json/' + $(this).val(),
                    dataType: "json",
                    success: function(data){
                      $('#shipper_address').val(data.address);
                  },
                  error:function(){
                    console.log('error '+ data);
                    }
                });
            });

            $('#consignee_id').on('change', function(){
                $.ajax({
                    method: 'GET',
                    url: '/parties/get_json/' + $(this).val(),
                    dataType: "json",
                    success: function(data){
                      $('#consignee_address').val(data.address);
                      if(!$('#destination_id').val()){
                          $('#destination_id').val(data.city_id).change();
                      }
                  },
                  error:function(){
                    console.log('error '+ data);
                    }
                });
            });

            // Untuk stock_transports form
            var get_method_form_stock_transport = $('#method_form_stock_transport').val();
            if (get_method_form_stock_transport == 'PUT') {
                $('#advance_notice_id').prop('disabled', true);
                $('select[name="origin_id"]').prop('disabled', true);
                $('#destination_id').prop('disabled', true);
                $('select[name="warehouse_id"]').prop('disabled', true);
                $('select[name="transport_type_id"]').prop('disabled', true);
                $('#shipper_id').prop('disabled', true);
                $('#consignee_id').prop('disabled', true);
                $('#etd').prop('disabled', true);
                $('#eta').prop('disabled', true);
                $('select[name="employee_name"]').prop('disabled', true);
                $('input[name="pickup_order"]').prop('disabled', true);
                $('#owner').prop('disabled', true);
                $('#pic').prop('disabled', true);
                // $('select[name="traffic"]').prop('disabled', true);
                // $('select[name="load_type"]').prop('disabled', true);
                // $('input[name="origin_address"]').prop('disabled', true);
                // $('input[name="origin_postcode"]').prop('disabled', true);
                // $('input[name="origin_latitude"]').prop('disabled', true);
                // $('input[name="origin_longitude"]').prop('disabled', true);
                // $('input[name="dest_address"]').prop('disabled', true);
                // $('input[name="dest_postcode"]').prop('disabled', true);
                // $('input[name="dest_latitude"]').prop('disabled', true);
                // $('input[name="dest_longitude"]').prop('disabled', true);
            } else {
                $('#advance_notice_id').on('change', function(){
                    $.ajax({
                        method: 'GET',
                        url: '/advance_notices/get_json/' + $(this).val(),
                        dataType: "json",
                        success: function(data){
                            console.log(data);
                            $('select[name="origin_id"]').val(data.origin_id);
                            $('#destination_id').val(data.destination_id);
                            $('#transport_type_id').val(data.transport_type_id);
                            $('#transport_type_name').val(data.transport_type_name);
                            $('#warehouse_id').val(data.warehouse_id);
                            $('#warehouse_name').val(data.warehouse_name);
                            $('select[name="employee_name"]').val(data.employee_name);
                            $('#shipper_id').val(data.shipper_id);
                            $('#consignee_id').val(data.consignee_id);
                            $('#ref_code').val(data.ref_code);
                            $('#etd').val(data.etd);
                            $('#shipper_address').val(data.shipper_address);
                            $('#eta').val(data.eta);
                            $('#consignee_address').val(data.consignee_address);
                            $('#annotation').val(data.annotation);
                            $('#contractor').val(data.contractor);
                            $('#head_ds').val(data.head_ds);
                            $('#owner').val(data.owner);
                            $('#pic').val(data.pic);

                            $('#annotation').val(data.annotation);
                            $('#contractor').val(data.contractor);
                            $('#head_ds').val(data.head_ds);

                            $('select[name="employee_name"]').empty();

                            $.each(data.warehouseOfficers, function(key, value) {
                                $('select[name="employee_name"]').append('<option value="'+ value.first_name +'">'+ value.first_name +' ('+ value.user_id +')</option>');
                            });

                            $('select[name="origin_id"]').prop('disabled', true);
                            $('#destination_id').prop('disabled', true);
                            $('#warehouse_name').prop('disabled', true);
                            $('#transport_type_name').prop('disabled', true);
                            $('#shipper_id').prop('disabled', true);
                            $('#consignee_id').prop('disabled', true);
                            $('#etd').prop('disabled', true);
                            $('#eta').prop('disabled', true);
                        },
                        error:function(){
                            console.log('error '+ data);
                        }
                    });
                });
            }

            // Untuk stock_entries form
            var get_method_form_stock_entries = $('#method_form_stock_entries').val();
            if (get_method_form_stock_entries == 'PUT') {
                $('#stock_transport_id').prop('disabled', true);
                $('#ref_code').prop('disabled', true);
                $('#employee_name').prop('disabled', true);
            } else {
                $('#stock_transport_id').on('change', function(){
                    $.ajax({
                        method: 'GET',
                        url: '/stock_transports/get_json/' + $(this).val(),
                        dataType: "json",
                        success: function(data){
                            $('select[name="warehouse_id"]').attr('readonly', true);
                            $('#ref_code').val(data.ref_code);
                            $('#consignee_entries').val(data.consignee.name);
                            $('#consignee_address_entries').val(data.consignee.address);


                            $('#ref_code').attr('readonly', true);
                            
                            $('select[name="employee_name"]').empty();
                            
                            $.each(data.warehouseOfficers, function(key, value) {
                                $('select[name="employee_name"]').append('<option value="'+ value.first_name +'">'+ value.first_name +' ('+ value.user_id +')</option>');
                            });
                            //$('#employee_name').attr('readonly', true);
                        },
                        error:function(){
                            console.log('error '+ data);
                        }
                    });
                });
            }

            // Untuk stock_deliveries form
            var get_method_form_stock_deliveries = $('#method_form_stock_deliveries').val();
            if (get_method_form_stock_deliveries == 'PUT') {
                $('#stock_entry_id').prop('disabled', true);
                $('select[name="origin_id"]').prop('disabled', true);
                $('select[name="destination_id"]').prop('disabled', true);
                $('select[name="transport_type_id"]').prop('disabled', true);
                $('#shipper_id').prop('disabled', true);
                $('#consignee_id').prop('disabled', true);
                $('#etd').prop('disabled', true);
                $('#eta').prop('disabled', true);
                $('input[name="pickup_order"]').prop('disabled', true);
                $('select[name="employee_name"]').prop('disabled', true);
            } else {
                $('#stock_entry_id').on('change', function(){
                    $.ajax({
                        method: 'GET',
                        url: '/stock_entries/get_json/' + $(this).val(),
                        dataType: "json",
                        success: function(data){
                            console.log(data);
                            $('select[name="origin_id"]').val(data.origin_id);
                            $('select[name="origin_id"]').prop('disabled', true);
                            $('select[name="destination_id"]').val(data.destination_id);
                            $('select[name="destination_id"]').prop('disabled', true);
                            $('select[name="transport_type_id"]').val(data.transport_type_id);
                            $('select[name="transport_type_id"]').prop('disabled', true);
                            $('#shipper_id').val(data.shipper_id);
                            $('#total_collie').val(data.total_koli);
                            $('#total_volume').val(data.total_volume);
                            $('#total_weight').val(data.total_berat);
                            $('#shipper_id').val(data.shipper_id);
                            $('#shipper_id').prop('disabled', true);
                            $('#consignee_id').val(data.consignee_id);
                            $('#consignee_id').prop('disabled', true);
                            $('#ref_code').val(data.ref_code);
                            $('#etd').val(data.etd);
                            $('#etd').prop('disabled', true);
                            $('#shipper_address').val(data.shipper_address);
                            $('#eta').val(data.eta);
                            $('#eta').prop('disabled', true);
                            $('#consignee_address').val(data.consignee_address);
                            $('#ref_code').val(data.ref_code);
                            $('#employee_name').val(data.employee_name);
                        },
                        error:function(){
                            console.log('error '+ data);
                        }
                    });
                });
            }

            $('#changeAttributeGoodReceiving').on('submit', function(){
                $('#advance_notice_id').prop('disabled', false);
                $('select[name="transport_type_id"]').prop('disabled', false);
                $('select[name="origin_id"]').prop('disabled', false);
                $('select[name="destination_id"]').prop('disabled', false);
                $('#shipper_id').prop('disabled', false);
                $('#consignee_id').prop('disabled', false);
                $('#etd').prop('disabled', false);
                $('#eta').prop('disabled', false);
                $('select[name="employee_name"]').prop('disabled', false);
                $('#pickup_order').prop('disabled', false);
            });

            $('#changeAttributeStockEntries').on('submit', function(){
                $('#stock_transport_id').prop('disabled', false);
                $('#ref_code').prop('disabled', false);
                $('#employee_name').prop('disabled', false);
            });

            $('#changeAttributeDelivery').on('submit', function(){
                $('#stock_entry_id').prop('disabled', false);
                $('select[name="origin_id"]').prop('disabled', false);
                $('select[name="destination_id"]').prop('disabled', false);
                $('select[name="transport_type_id"]').prop('disabled', false);
                $('#shipper_id').prop('disabled', false);
                $('#consignee_id').prop('disabled', false);
                $('#etd').prop('disabled', false);
                $('#eta').prop('disabled', false);
            });

            $('#changeStock').on('submit', function(){
                $('select[name="uom_id"]').prop('disabled', false);
            });

            $('.item-transaction-table').DataTable( {
                destroy: true,
                "order": [[ 0, "asc" ]]
            } );

            //$('.item-transaction-table td:nth-child(1),.item-transaction-table th:nth-child(1)').hide();


            $(document.body).on('submit', '.js-confirm', function () {
                var $el = $(this)
                var text = $el.data('confirm') ? $el.data('confirm') : 'Anda yakin melakukan tindakan ini ?'

                var c = confirm(text);
                return c;
            });

            $('#dest_qty_available').keyup(function(){
                var $currentVal = parseInt($(this).val());
                var $defaultVal = parseInt($('#qty-avail-hidden').val());

                if(!isNaN($currentVal)) {
                    if($currentVal > $defaultVal) {
                        $('#dest_qty_available').val($defaultVal);
                        $('#qty-avail-html').text(0);
                        return false;
                    }
                    $('#qty-avail-html').text($defaultVal - $currentVal);
                }
            });

            $('#select-project').on('change', function(){
                window.location.href = this.value;      
            });
        });
    </script>

    

    @yield('adminlte_js')
    @yield('custom_script')

</body>
</html>

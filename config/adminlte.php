<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'Integrated Logistics Solution',

    'title_prefix' => 'BGR - ',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>Integrated Logistics Solution</b>',

    'logo_mini' => '<b>I</b>LS',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'green-light',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        [
            'text'          => 'Dashboard',
            'url'           => '/home',
            'permission'    => 'read-Dashboard',
        ],
        [
            'text'    => 'Company Management',
            'submenu' => [
                [
                    'text'          => 'Company List',
                    'url'           => 'companies',
                    'permission'    => 'read-CompanyList',
                ],
                [
                    'text'          => 'Create Company',
                    'url'           => 'companies/create',
                    'permission'    => 'create-CompanyList',
                ],
            ],
            'permission'    => 'menu-CompanyManagement',
        ],
        [
            'text'    => 'Project Management',
            'submenu' => [
                [
                    'text'          => 'Project List',
                    'url'           => 'projects',
                    'permission'    => 'read-ProjectList',
                ],
                [
                    'text'          => 'Create Project',
                    'url'           => 'projects/create',
                    'permission'    => 'create-ProjectList',
                ],
                [
                    'text'          => 'Contract List',
                    'url'           => 'contracts',
                    'permission'    => 'read-ContractList',
                ],
                [
                    'text'          => 'SPK List',
                    'url'           => 'spks',
                    'permission'    => 'read-SpkList',
                ],
            ],
            'permission'    => 'menu-ProjectManagement',
        ],
        [
            'text'    => 'User Management',
            'submenu' => [
                [
                    'text'          => 'User List',
                    'url'           => 'users',
                    'permission'    => 'read-UserList',
                ],
                [
                    'text'          => 'Create User',
                    'url'           => 'users/create',
                    'permission'    => 'create-UserList',
                ],
                [
                    'text'          => 'Role List',
                    'url'           => 'roles',
                    'permission'    => 'read-RoleList',
                ],
                [
                    'text'          => 'Create Role',
                    'url'           => 'roles/create',
                    'permission'    => 'create-RoleList',
                ],
                [
                    'text'          => 'Locked Account',
                    'url'           => 'user_locked',
                    'permission'    => 'read-LockedAccount',
                ],
                [
                    'text'          => 'Logged In User',
                    'url'           => 'user_logged_in',
                    'permission'    => 'read-LoggedUser',
                ],
            ],
            'permission'    => 'menu-UserManagement',
        ],
        [
            'text'    => 'Inbound',
            'submenu' => [
                [
                    'text'          => 'AIN',
                    'url'           => 'advance_notices/inbound',
                    'permission'    => 'read-AinList',
                ],
                [
                    'text'          => 'Goods Receiving',
                    'url'           => 'stock_transports/inbound',
                    'permission'    => 'read-GoodReceiving',
                ],
                [
                    'text'          => 'Putaway',
                    'url'           => 'stock_entries/inbound',
                    'permission'    => 'read-ViewPutaway',
                ],
                // [
                //     'text'          => 'Tutup Storage',
                //     'url'           => 'to_storage_list/inbound',
                //     'permission'    => 'read-AdvanceNoticeStorageList',
                // ],
            ],
            'permission'    => 'menu-Inbound',

        ],
        [
            'text'    => 'Outbound',
            'submenu' => [
                [
                    'text'          => 'AON',
                    'url'           => 'advance_notices/outbound',
                    'permission'    => 'read-AonList',

                ],
                [
                    'text'          => 'Delivery Plan',
                    'url'           => 'stock_transports/outbound',
                    'permission'    => 'read-DeliveryPlan',
                ],
                [
                    'text'          => 'Picking Plan',
                    'url'           => 'stock_entries/outbound',
                    'permission'    => 'read-PickingPlan',
                ],
                [
                    'text'          => 'Goods Issue',
                    'url'           => 'stock_deliveries/outbound',
                    'permission'    => 'read-GoodIssue',
                ],
                // [
                //     'text'          => 'Buka Storage',
                //     'url'           => 'to_storage_list/outbound',
                //     'permission'    => 'read-AdvanceNoticeStorageList',
                // ],
            ],
            'permission'    => 'menu-Outbound',

        ],
        [
            'text'    => 'Internal Movement',
                'submenu' => [
                    [
                        'text'          => 'Movement',
                        'url'           => 'stock_internal_movements',
                        'permission'    => 'read-InternalMovement',
                    ],
                ],
                'permission'    => 'menu-Movement',

        ],
        [
            'text'    => 'Opname',
                'submenu' => [
                    [
                        'text'          => 'Stock opname',
                        'url'           => 'stock_opnames',
                        'permission'    => 'read-InternalMovement',
                    ],
                ],
            'permission'    => 'menu-StockOpname',

        ],
        // [
        //     'text'    => 'Internal Movement',
        //     'submenu' => [
        //         [
        //             'text'          => 'Available Storage',
        //             'url'           => 'stock_allocations',
        //             'permission'    => 'read-InternalMovement',
        //         ],
        //         // [
        //         //     'text'          => 'Stock Transfer Order',
        //         //     'url'           => 'stock_transfer_order/outbound',
        //         //     'permission'    => 'read-StockTransferOrder',
        //         // ]
        //     ],
        //     'permission'    => 'menu-InternalMovement',
        // ],
        [
            'text'    => 'Warehouse Management',
            'submenu' => [
                [
                    'text'          => 'Lokasi (Maps)',
                    'url'           => 'warehouses/maps',
                    'permission'    => 'read-Maps',
                ],
                [
                    'text'          => 'Warehouses (Gudang)',
                    'url'           => 'warehouses',
                    'permission'    => 'read-WarehousesList'
                ],
                [
                    'text'          => 'Storages (Penyimpanan)',
                    'url'           => 'storages',
                    'permission'    => 'read-Storages',
                ],
                [
                    'text'          => 'Items (Barang)',
                    'url'           => 'items',
                    'permission'    => 'read-Items',
                ],
                // [
                //     'text'          => 'Import Data',
                //     'url'           => 'to_import',
                //     // 'permission'    => 'import-Data',
                // ],
            ],
            'permission'    => 'menu-WarehouseManagement',
        ],
        [
            'text'    => 'Data Master',
            'submenu' => [
                [
                    'text'          => 'Commodities (Komoditas)',
                    'url'           => 'commodities',
                    'permission'    => 'read-Comodities',
                ],
                [
                    'text'          => 'Parties (Para Pihak)',
                    'url'           => 'parties',
                    'permission'    => 'read-Parties',
                ],
                [
                    'text'          => 'Unit of Measurements (UoM)',
                    'url'           => 'uoms',
                    'permission'    => 'read-UnitOfMeasurements',
                ],
                [
                    'text'          => 'Company Types (Jenis Perusahaan)',
                    'url'           => 'company_types',
                    'permission'    => 'read-CompanyTypes',
                ],
                [
                    'text'          => 'Control Methods (Metode Kontrol)',
                    'url'           => 'control_methods',
                    'permission'    => 'read-ControlMethods',
                ],
                [
                    'text'          => 'Transport Types (Moda Transportasi)',
                    'url'           => 'transport_types',
                    'permission'    => 'read-TransportTypes',
                ],
                [
                    'text'          => 'Advance Notice Activities (Jenis Kegiatan Barang Masuk/Keluar)',
                    'url'           => 'advance_notice_activities',
                    'permission'    => 'read-AdvanceNoticeActivities',
                ],
                [
                    'text'          => 'Provinces (Area)',
                    'url'           => 'regions',
                    'permission'    => 'read-Regions'
                ],
                [
                    'text'          => 'Cities (Kota)',
                    'url'           => 'cities',
                    'permission'    => 'read-Cities',
                ],
                [
                    'text'          => 'Types (Jenis)',
                    'url'           => 'types',
                    'permission'    => 'read-Types',
                ],
                [
                    'text'          => 'Packings (Packing)',
                    'url'           => 'packings',
                    'permission'    => 'read-Packings',
                ],
            ],
            'permission'    => 'menu-DataMaster',
        ],
        [
            'text'    => 'Reporting',
            'submenu' => [
                [
                    'text'          => 'Laporan Stock On Location',
                    'url'           => 'report/stock_on_location',
                    'permission'    => 'read-StockOnLocationReport',
                ],
                [
                    'text'          => 'Laporan Stock Per Lokasi Detail',
                    'url'           => 'report/storage_location_detail',
                    'permission'    => 'read-ManagementStockOnLocation',
                ],
                [
                    'text'          => 'Laporan Stock Mutasi Per Bulan',
                    'url'           => 'report/stock_mutation_month',
                    'permission'    => 'read-ManagementStockOnLocation',
                ],
                [
                    'text'          => 'Laporan Stock On Staging In',
                    'url'           => 'report/stock_on_staging/inbound',
                    'permission'    => 'read-StockOnStagingReport',
                ],
                [
                    'text'          => 'Laporan Stock On Staging Out',
                    'url'           => 'report/stock_on_staging/outbound',
                    'permission'    => 'read-StockOnStagingReport',
                ],
                [
                    'text'          => 'Laporan Summary Mutasi Stock',
                    'url'           => 'report/management_stock_report',
                    'permission'    => 'read-ManagementStockOnLocationReport',
                ],
                [
                    'text'          => 'Laporan Mutasi Stok Per Item',
                    'url'           => 'report/stock_mutation',
                    'permission'    => 'read-MutasiStockReport',
                ],
                [
                    'text'          => 'Laporan Handling In',
                    'url'           => 'report/handling/inbound',
                    'permission'    => 'read-HandlingInReport',
                ],
                [
                    'text'          => 'Laporan Handling Out',
                    'url'           => 'report/handling/outbound',
                    'permission'    => 'read-HandlingOutReport',
                ],
                [
                    'text'          => 'Laporan Transaksi per Item',
                    'url'           => 'report/sku_transaction',
                    'permission'    => 'read-SkuTransactionReport',
                ],
                [
                    'text'          => 'Laporan AIN AON',
                    'url'           => 'report/ain_aon_completed',
                    'permission'    => 'read-AinAonCompleteReport',
                ],
                [
                    'text'          => 'Warehouse Project Report',
                    'url'           => 'report/warehouse_projects',
                    'permission'    => 'read-WarehouseProjectReport',
                ],
                [
                    'text'          => 'Warehouse Contract Report',
                    'url'           => 'report/warehouse_contracts',
                    'permission'    => 'read-WarehouseContractReport',
                ],
                [
                    'text'          => 'Stock On Location',
                    'url'           => 'report/storage_location',
                    'permission'    => 'read-ManagementStockOnLocation',
                ],
                [
                    'text'          => 'Detail Transaksi Inbound',
                    'url'           => 'report/detail_inbound',
                    'permission'    => 'read-TransaksiInbound',
                ],
                [
                    'text'          => 'Detail Transaksi Outbound',
                    'url'           => 'report/detail_outbound',
                    'permission'    => 'read-TransaksiOutbound',
                ],
                [
                    'text'          => 'Laporan Good Issue',
                    'url'           => 'report/good_issue_management',
                    'permission'    => 'read-TransaksiOutbound',
                ],
                [
                    'text'          => 'Laporan POD per No Good Isue',
                    'url'           => 'report/good_issue_mutation',
                    'permission'    => 'read-GoodIssueMutation',
                ],
                [
                    'text'          => 'Laporan POD per SKU Item',
                    'url'           => 'report/good_issue_mutation_sku',
                    'permission'    => 'read-GoodIssueMutation',
                ],
                [
                    'text'          => 'Stock Opname Report',
                    'url'           => 'report/stock_opnames',
                    'permission'    => 'read-StockOpnamesReport',
                ],
            ],
            'permission'    => 'menu-Reporting',
        ],
        [
            'text'    => 'Command Center',
            'submenu' => [
                [
                    'text'          => 'Work Order',
                    'url'           => 'work_order',
                    'permission'    => 'read-WorkOrder',
                ],
                [
                    'text'          => 'Warehouse Activity',
                    'url'           => 'warehouse_activity',
                    'permission'    => 'read-WarehouseActivity',
                ],
            ],
            'permission'    => 'menu-CommandCenter',
        ],
        [
            'text'    => 'Delivery Order',
            'submenu' => [
                [
                    'text'          => 'Transport',
                    'url'           => 'deliveries',
                    'permission'    => 'update-Delivery',
                ],
            ],
            'permission'    => 'menu-Deliveries',
        ],
        [
            'text'    => 'Monitoring SUHU',
            'submenu' => [
                [
                    'text'          => 'Monitoring SUHU',
                    'url'           => 'suhu',
                    'permission'    => 'read-suhu',
                ],
            ],
            'permission'    => 'menu-Deliveries',
        ],
        
        // [
        //     'text'          => 'Activity Log',
        //     'url'           => 'activity_logs',
        //     'permission'    => 'read-ActivityLog',
        // ],
    ],
    'permission'    => 'menu-ActivityLog',


    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
    //    JeroenNoten\LaravelAdminLte\Menu\Filters\MyMenuGateway::class,
    //        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
       App\MyMenuFilter::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => true,
        'select2'    => true,
        'chartjs'    => true,
    ],
];

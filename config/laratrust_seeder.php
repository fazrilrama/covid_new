<?php

return [
    'role_structure' => [
        'Superadmin' => [
        	'Dashboard'=>'r',
            'AdminUsers' => 'c,r,u,d',
            'ResetAdminPassword' => 'r,u',
            'UnlockAdmin' => 'r,u',
			'InputUpdateDataMaster'=>'c,r,u,d'
        ],
        'Admin' => [
        	'Dashboard'=>'r',
            'users' => 'c,r,u,d',
            'userspassword' => 'r,u',
			'UnlockUser'=>'r,u'
        ],
        'CargoOwner' => [
        	'Dashboard'=>'r',
            'SPBK' => 'c,r,u,d',
			'SPBM' => 'c,r,u,d',
			'LoadingOrder'=> 'r',
			'StockOnLocation'=>'r',
			'DeliveryNoteReport'=>'r'
        ],
		'WarehouseSupervisor' => [
			'Dashboard'=>'r',
            'SPBK' => 'r',
			'SPBM' => 'r',
			'ReceivingGood'=>'c,r,u,d',
			'ProduceUnLoadingOrder'=>'c,r,u,d',
			'LoadingOrder'=>'r',
			'TallySheetForm'=>'c,r,u,d',
			'StoragePlanning'=>'c,r,u,d',
			'PutAwayList'=>'c,r,u,d',
			'StockOnLocation'=>'c,r,u,d',
			'DeliveryPlan'=>'c,r,u,d',
			'DeliveryPlanReport'=>'c,r',
			'PickingPlan'=>'c,r,u,d',
			'StockOnLocationReport'=>'r',
			'PickingList'=>'c,r,u,d',
			'LoadingOrder'=>'c,r,u,d',
			'DeliveryNote'=>'c,r,u,d',
			'DeliveryNoteReport'=>'r',
			'StorageDetailReport'=>'r'
        ],
		'WarehouseOfficer' => [
			'Dashboard'=>'r',
			'LoadingOrder'=> 'r',
			'TallySheetForm'=>'r',
			'PutAwayList'=>'r',
			'StockOnLocation'=>'r',
			'PickingList'=>'r',
			'LoadingOrder'=>'r',
			'DeliveryNoteReport'=>'r'
        ],
		'Finance' => [
			'StockOnLocation'=>'r',
			'StorageDetailReport'=>'r',
			'Dashboard'=>'r',
			'DeliveryNote'=>'r'
        ],
		'Operation' => [
			'SPBK'=>'r',
			'LoadingOrder'=>'r',
			'TallySheetForm'=>'r',
			'StockOnLocation'=>'r',
			'DeliveryNote'=>'r',
			'StorageDetailReport'=>'r',
			'Dashboard'=>'r'
        ],
		'Transporter' => [
			'Dashboard'=>'r',
			'DeliveryNote'=>'c,r,u,d',
			'DeliveryNoteReport'=>'r'
        ],
    ],
    'permission_structure' => [],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ]
];

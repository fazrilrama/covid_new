<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as Controller;
use App\Warehouse;
use App\Transformers\MyWarehouseTransformer;

class WarehouseController extends Controller
{
    public function myWarehouse()
    {
        $warehouses = Warehouse::leftJoin('warehouse_officer', 'warehouses.id', 'warehouse_officer.warehouse_id')
                                        ->where('warehouse_officer.user_id', auth()->id())
                                        ->select(['warehouse_id as id', 'warehouses.name as name'])->get();

        return fractal($warehouses, new MyWarehouseTransformer());
    }
}

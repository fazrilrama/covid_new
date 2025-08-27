<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class InboundPaList extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($model)
    {
        return [
            'id' => $model->id,
            'type' => $model->type,
            'code' => $model->code,
            'gr_code' => $model->stock_transport->code,
            'ref_code' => $model->ref_code,
            'status' => $model->status,
            'total_pallet' => $model->total_pallet,
            'total_labor' => $model->total_labor,
            'total_forklift' => $model->total_forklift,
            'forklift_start_time' => $model->forklift_start_time,
            'forklift_end_time' => $model->forklift_end_time,
            'is_editable' => $model->status != 'Processed' ? false : true
        ];
    }
}

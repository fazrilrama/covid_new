<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class InboundTsList extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($model)
    {
        $response = [];
        $total_qty = 0;
        $total_plan_qty = 0;
        $total_weight = 0;
        $total_plan_weight = 0;
        $total_volume = 0;
        $total_plan_volume = 0;

        foreach($model->details as $row){
            $content_weight = 0;
            $empty_weight = 0;

            if($row->item_weigher->first()) {
                $content_weight = $row->item_weigher->first()->content_weight;
            }

            if($row->item_weigher->first()) {
                $empty_weight = $row->item_weigher->first()->empty_weight;
            }

            $response[] = [
                'stock_transport_id' => $row->stock_transport_id,
                'stock_transport_detail_id' => $row->id,
                'item_id' => $row->item->name,
                'uom_id' => $row->uom_id,
                'qty' => $row->qty,
                'plan_qty' => $row->plan_qty,
                'weight' => $row->weight,
                'plan_weight' => $row->plan_weight,
                'volume' => $row->volume,
                'plan_volume' => $row->plan_volume,
                'control_date' => $row->control_date,
                'ref_code' => $row->ref_code,
                'content_weight' => (float)$content_weight,
                'empty_weight' => (float)$empty_weight,
            ];

            $total_qty += $row->qty;
            $total_plan_qty += $row->plan_qty;
            $total_weight += $row->weight;
            $total_plan_weight += $row->plan_weight;
            $total_volume += $row->volume;
            $total_plan_volume += $row->plan_volume;
        }


        return [
            'id' => $model->id,
            'type' => $model->type,
            'code' => $model->code,
            'advance_notice_id' => (isset($model->advance_notice->code)) ? $model->advance_notice->code : '-',
            'transport_type_id' => (isset($model->transport_type->name)) ? $model->transport_type->name : '-',
            'ref_code' => $model->ref_code,
            'warehouse_id' => $model->warehouse->name,
            'shipper_id' => (isset($model->shipper->name)) ? $model->shipper->name : '-',
            'shipper_address' => (isset($model->shipper->address)) ? $model->shipper->address : '-',
            'origin_id' => (isset($model->origin->name)) ? $model->origin->name : '-',
            'etd' => $model->etd,
            'destination_id' => (isset($model->destination->name)) ? $model->destination->name : '-',
            'consignee_id' => (isset($model->consignee->name)) ? $model->consignee->name : '-',
            'consignee_address' => (isset($model->consignee->address)) ? $model->consignee->address : '-',
            'eta' => $model->eta,
            'pickup_order' => $model->pickup_order,
            'employee_name' => $model->employee_name,
            'status' => $model->status,
            'data' => $response,
            'total_qty' => $total_qty,
            'total_plan_qty' => $total_plan_qty,
            'total_weight' => $total_weight,
            'total_plan_weight' => $total_plan_weight,
            'total_volume' => $total_volume,
            'total_plan_volume' => $total_plan_volume,
            'is_editable' => $model->status != 'Processed' ? false : true
            /**
             * For FOMS
             */
            // 'user_id' => $model->user_id,
            // 'project_id' => $model->project_id,
            // 'traffic' => $model->traffic,
            // 'load_type' => $model->load_type,
            // 'origin_address' => $model->origin_address,
            // 'origin_postcode' => $model->origin_postcode,
            // 'origin_latitude' => $model->origin_latitude,
            // 'origin_longitude' => $model->origin_longitude,
            // 'dest_address' => $model->dest_address,
            // 'dest_postcode' => $model->dest_postcode,
            // 'dest_latitude' => $model->dest_latitude,
            // 'dest_longitude' => $model->dest_longitude,
            // 'is_sent' => $model->is_sent,
        ];
    }
}

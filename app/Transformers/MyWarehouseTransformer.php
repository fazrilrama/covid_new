<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class MyWarehouseTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($response)
    {
        return [
            'id' => $response->id,
            'name' => $response->name, 
        ];
    }
}

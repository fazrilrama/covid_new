<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User as ModelUser;

class User extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(ModelUser $user)
    {
        return [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'active' => [
                'id' => session('ap_id', NULL),
                'name' => session('ap_name', NULL),
            ],
            'projects' => $user->projects->map(function($item){
                return [
                    'id' => $item->id,
                    'project' => $item->name,
                ];
            })
        ];
    }
}

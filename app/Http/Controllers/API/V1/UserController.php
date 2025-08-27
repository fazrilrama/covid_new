<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as Controller;
use App\User;
use App\Transformers\User as UserTransformer;

class UserController extends Controller
{
    public function me()
    {
        return fractal(auth()->user(), new UserTransformer());
    }

    public function setProject()
    {
        session()->put('ap_id', (int)request('ap_id'));
        session()->put('ap_name', request('ap_name'));

        return $this->me();
    }
}

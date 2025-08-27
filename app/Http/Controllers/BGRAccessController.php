<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\LastLogger;
use Auth;

class BGRAccessController extends Controller
{
    public function getToken(Request $request)
    {
        $header = $request->header('x-api-key');    
        if($header != 'umHOc8wXhiJhePNeRlSLNLK34v10DiaWhUdxjVcwlUcSRqjgyujlp9zPFJjf') {
            return response()->json(array(
                'status' => 'error',
                'message' => "x-api-key Not Found in Api Key Access Company",
                'code' => 401,
                'data' => [],
            ),401);
        } else {
            if($request->nik == null) {
                
                return response()->json(array(
                    'status' => 'error',
                    'message' => "Nik required",
                    'code' => 400,
                    'data' => [],
                ),400);
                // abort(400);
            }
            $user = User::where('nik',$request->nik)->first();
            if($user){
                $accessToken = $user->createToken('authToken')->accessToken;
                $user->api_token= $accessToken;
                $user->save();
                return response()->json(array(
                'status' => 'success',
                'message' => 'success get token',
                'code' => 200,
                'data' => "https://covid.bgrlogistics.id/api/login_sso?nik=".$user->nik."&token=".$accessToken,
                ),200);
            } else {
                return response()->json(array(
                    'status' => 'error',
                    'message' => "Access Wina Covid Not Found",
                    'code' => 401,
                    'data' => [],
                ),401);
                // abort(401);
            }
        }
    }

    public function login(Request $request)
    {
        // $header = $request->header('x-api-key');   
        // if($header == 'yQ2UVLU6IPhZJATv4R7UjQTff0D8GSb7thH2XP9v6WcqL0xdiqeN3EpvTA7w') {
        //     if($request->bearerToken()){
                $user = User::where('nik',$request->nik)->where('api_token', $request->token)->with('roles')->first();
                if($user) {
                    try {
                        LastLogger::create([
                            'user_id' => $user->id,
                            'responses' => $user,
                            'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now(),
                        ]);
                        // Auth::attempt($user);
                        Auth::login($user, true);
                        $data = Auth::user();
                        // dd($data);

                        // dd($data->hasRole('Admin-BGR'));
                        if ( $data->hasRole('Superadmin') ) {
                            return redirect('/companies');
                        }
                        if ( $data->hasRole('Admin') || $data->hasRole('Admin-BGR') ) {

                            return redirect('/projects');
                        }
                        if ( $data->hasRole('CargoOwner') ) {
                            return redirect('/advance_notices/inbound');
                        }
                        if ($data->hasRole('CommandCenter')) {
                            return redirect('/projects');
                        }
                        if ($data->hasRole('WarehouseChecker') || $data->hasRole('WarehouseOfficer')) {
                            return redirect('/stock_transports/inbound');
                        }
                        if ($data->hasRole('Admin-Client')) {
                            return redirect('/warehouses');
                        }
                        if ($data->hasRole('SPI')) {
                            return redirect('/warehouses');
                        }
                        if ($data->hasRole('SPI')) {
                            return redirect('/warehouses');
                        }
                        if ($data->hasRole('CommandCenterDKI')) {
                            return redirect('/home');
                        }
                        return redirect('/advance_notices/inbound');
                    } catch (\Exception $e) {
                        return response()->json(array(
                            'status' => 'error',
                            'message' => "Something wrong",
                            'code' => 500,
                            'data' => [],
                        ),500);
                    }
                } else {
                    return response()->json(array(
                        'status' => 'error',
                        'message' => "User Not Found",
                        'code' => 404,
                        'data' => [],
                    ),404);
                    // abort(404);
                }
        //     } else {
        //         return response()->json(array(
        //             'status' => 'error',
        //             'message' => "Token not valid, request again",
        //             'code' => 401,
        //             'data' => [],
        //         ),401);
        //     }
        // } else {
        //     abort(401);
        // }
        
    }
}
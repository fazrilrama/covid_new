<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use App\Project;
use App\Role;
use Auth;
use App\Party;
use App\Permission;
use App\PartyType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterCustomerAdminAccount;
use App\Mail\RegisterCustomerUserAccount;
use DB;


class UserController extends Controller
{
    public function index()
    {
        if(Auth::user()->hasRole('Superadmin')) {
            // Display all users
            $collections = User::where('users.id','!=','1')->orderBy('user_id','ASC')->get();
        } else {
            if (empty(session()->get('current_project'))) {
                return redirect()->back()->with('error','Anda tidak mempunyai project');
            }
            $projectId = session()->get('current_project')->id;
            $collections = Auth::user()->projects->where('id', $projectId)->first()->users()->orderBy('user_id','ASC')->get();
        }
        
        return view('users.index',compact('collections'));
    }

    public function activation($id) {
        $user = User::find($id);
        $finishTime = Carbon::now();
        $startTime = new Carbon($user->created_at);

        $totalDuration = $finishTime->diffInSeconds($startTime);

        if ($totalDuration >= 86400) {
            return 'expired';
        }

        $user->verified = 1;
        $user->save();

        return view('users.verify');
    }

    public function create()
    {

        $action = route('users.store');
        $method = 'POST';
        $branches = Party::whereHas('party_types', function ($query) {
            $query->where('name','branch');
        })->get();
        $roleExcluded = ['Superadmin'];
        if (Auth::user()->hasRole('Superadmin')) {

        }else if(Auth::user()->hasRole('Admin-BGR')){
            array_push($roleExcluded, 'Admin-BGR');
            array_push($roleExcluded, 'Admin-Client');
        }else if (Auth::user()->hasRole('Admin-Client')) {
            array_push($roleExcluded, 'Admin-BGR');
            array_push($roleExcluded, 'Admin-Client');
            $roles = Role::whereNotIn('name', $roleExcluded)->where('name', '<>', 'CargoOwner')->get(['name']);
            foreach ($roles as $role) {
                array_push($roleExcluded, $role->name);
            }
        }
        $user = new User;
        $permissions = Permission::all();
        $roles = Role::whereNotIn('name', $roleExcluded)->orderBy('display_name')->get();
        return view('users.create',compact('action','method','user','branches','roles','permissions'));
    }

    public function store(Request $request)
    {
        //Validate
        $request->validate([
            'user_id' => 'required|min:8|max:12|alpha_dash|unique:users,user_id',
            'first_name' => 'required',
            'mobile_number' => 'required|min:10',
            'work_position' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:12|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,12}$/',
            'roles' => 'present|filled',
            'permissions' => 'present|filled',
        ]);

        $model = new User;
        $model->user_id = $request->get('user_id');
        $model->first_name = $request->get('first_name');
        $model->last_name = $request->get('last_name');
        $model->mobile_number = $request->get('mobile_number');
        $model->work_position = $request->get('work_position');
        $model->email = $request->get('email');
        $model->password = bcrypt($request->get('password'));
        $model->branch_id = $request->get('branch_id');
        $model->is_active = 1;
        $model->save();
        
        if (!Auth::user()->hasRole('Superadmin')) {
            $projectId = session()->get('current_project')->id;
            $model->projects()->sync([$projectId]);
        }

        $model->roles()->sync($request->get('roles'));
        $model->permissions()->sync($request->get('permissions'));
        
        $email_sent = $request->get('email');
        Mail::to($email_sent)->queue(new RegisterCustomerAdminAccount($model->id, $model->user_id, $request->get('password')));

        return redirect()->route('users.edit',$model->id)->with('success', 'Data berhasil disimpan, jangan lupa untuk memberikan akses minimal ke satu project.');
    }

    public function show(User $user)
    {
        //
    }

    public function edit( User $user)
    {
        if(!Auth::user()->hasRole('Superadmin') && $user->id == 1) {
            abort(403);
        }
        // dd($user->permissions);
        $action = route('users.update', $user->id);
        $method = "PUT";
        $branches = Party::whereHas('party_types', function ($query) {
            $query->where('name','branch');
        })->get();
        $roles = Role::where('name','!=','Superadmin')->get();
        $permissions = Permission::all();
        // dd($permissions);

        return view('users.edit',compact('action','method','user','roles','branches','permissions'));
    }

    public function edit_projects(User $user)
    {
        
        $action = route('users.update_projects', $user->id);
        $method = "PUT";
        if(Auth::user()->hasRole('Superadmin')) {
            $projects = Project::all();
        } else {

            //session(['projects'=>Auth::user()->projects]);
            $projects = Auth::user()->projects;
        }
        $roles = Role::where('name','!=','Superadmin')->get();
        return view('users.projects',compact('action','method','user','roles','projects'));
    }

    public function update(Request $request, User $user)
    {   
        if(Auth::user()->hasRole('CommandCenter') && $user->id == 1) {
            abort(403);
        }
        
        //Validate
        $request->validate([
            'first_name' => 'required',
            'mobile_number' => 'required|min:10',
            'work_position' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'roles' => 'present|filled',
            'permissions' => 'present|filled',
        ]);


        // dd($request->all());

        $model = $user;
        $model->first_name = $request->get('first_name');
        $model->last_name = $request->get('last_name');
        $model->mobile_number = $request->get('mobile_number');
        $model->work_position = $request->get('work_position');
        $model->email = $request->get('email');
        $model->branch_id = $request->get('branch_id');


            
        if($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|max:12|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,12}$/'
            ]);
            $model->password = bcrypt($request->get('password'));
        }

        $model->save();

        $model->roles()->sync($request->get('roles'));
        $model->permissions()->sync($request->get('permissions'));
        // dd($model->permissions());
        return redirect()->route('users.edit',$user->id)->with('success', 'Data berhasil disimpan');
    }

    public function update_projects(Request $request, User $user)
    {
        //Validate
        $request->validate([
            'projects' => 'required|present|filled',
        ]);

        $model = $user;
        $model->projects()->sync($request->get('projects'));

        // Return edit user model
        return redirect()->route('users.edit',$model->id)->with('success', 'Data berhasil disimpan');
    }

    // public function getProjects()
    // {
    //     $collections = $company->users()->orderBy('id', 'desc')->get();
    //     return view('companies.users',compact('collections'));
    // }

    public function destroy(User $user)
    {
        if($user->id === 1) {
            return redirect('users')->with('message','Data tidak bisa dihapus');
        } else {
            $user->delete();
            return redirect('users')->with('success','Data berhasil dihapus');
        }
    }

    public function getDataPermission($id) {
        
        $permission = Permission::find($id)->get();
        $permission = DB::table('permission_role')
        ->join('permissions', 'permissions.id', '=','permission_role.permission_id' )
        ->join('roles', 'roles.id', '=', 'permission_role.role_id')
        
        // ->join('permission_user','permission_user.permission_id','=','permission.id')
        // ->join('users','users.id','=','permission_user.user_id')
        ->where('permission_role.role_id', '=', $id)
        ->get();
        
        return $permission;
    }
     public function getDataPermissionedit($id) {
        
        // $permission = Permission::find($id)->get();
        $permission = DB::table('permission_user')
        ->join('users', 'users.id', '=', 'permission_user.user_id')
        ->join('permissions', 'permissions.id', '=','permission_user.permission_id' )
        ->join('permission_role','permission_role.permission_id','=','permissions.id')
        ->join('roles','roles.id','=', 'permission_role.role_id')
        ->where('permission_user.user_id', '=', $id)
        ->distinct()
        ->get();
        
        return $permission;
    }
}

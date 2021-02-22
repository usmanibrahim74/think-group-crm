<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;

class ManagementController extends Controller
{


    public function getUsers(Request $request){

        $user = auth()->user();
        $perPage = $request->perPage?(int)$request->perPage:10;

        $page = $request->page?(int)$request->page:1;
        $search = $request->search?$request->search:"";
        $toSearch = "%".$search."%";
        $query = User::where(function ($q)use($toSearch){
            return $q->where('name', 'LIKE' ,$toSearch)
                ->orWhere('email', 'LIKE' , $toSearch);
        })
            ->where('id','!=',$user->id)
            ->whereHas('roles',function ($q){
            return $q->where('name','!=','employer');
        });
        $totalCount = $query->count();
        $data = $query->take($perPage)->skip(($page-1)*$perPage)->get()  ;

        $toReturn = generatePagination($page,$perPage,$totalCount, $data);
        return response()->json($toReturn);
    }

    public function getUser($user_id){

        $user = User::with(['roles'])->findOrFail($user_id);
        return response()->json($user);
    }

    /**
     * add new user
     * */
    public function addUser(Request $request){

        $messages = array(
            'role_id.required' => "The role field is required"
        );
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email:filter|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required'
        ],$messages);

        $data = $request->only('name','email','password');

        $role = Role::findOrFail($request->role_id);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->save();
        $user->attachRole($role);


        return response()->json([
            "status" => "success",
            "message" => "User has been added",
        ]);
    }

    /**
     * add new user
     * */
    public function updateUser(Request $request,$id){


        $messages = array(
            'role_id.required' => "The role field is required"
        );
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email:filter|max:255|unique:users,email,'.$id,
            'role_id' => 'required'
        ],$messages);

        $user = User::findOrFail($id);
        $role = Role::findOrFail($request->role_id);


        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();
        $user->syncRoles([$role]);


        return response()->json([
            "status" => "success",
            "message" => "User has been updated",
        ]);
    }

    public function changeUserPassword(Request $request, $id){

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);


        $user->password = bcrypt($request->password);

        $user->save();

        return response()->json([
            "status" => "success",
            "message" => "User's password has been updated",
        ]);
    }



    /**
     * return a list of all roles
     * */
    public function getALlRoles(){

        $roles = Role::all();
        return response()->json($roles);

    }


    public function getRoles(Request $request){

        $perPage = $request->perPage?(int)$request->perPage:10;

        $page = $request->page?(int)$request->page:1;
        $search = $request->search?$request->search:"";
        $toSearch = "%".$search."%";
        $query = Role::where('name', 'LIKE' ,$toSearch)
                            ->orWhere('display_name', 'LIKE' ,$toSearch)
                            ->orWhere('description', 'LIKE' , $toSearch);
        $totalCount = $query->count();
        $roles = $query->take($perPage)->skip(($page-1)*$perPage)->get()  ;

        $toReturn = generatePagination($page,$perPage,$totalCount, $roles);
        return response()->json($toReturn);

    }




    /**
     * add new role
     * */
    public function addRole(Request $request){

        $request->validate([
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
        ]);

        $role = Role::create($request->only('name','display_name','description'));
        if(count($request->permissions)){
            $role->permissions()->sync(array_column($request->permissions,'id'));
        }
        return response()->json([
            "status" => "success",
            "message" => "Role has been added",
        ]);
    }

    /**
     * return a role
     * */
    public function getRole($role_id){

        $role = Role::findOrFail($role_id);
        $role->load('permissions');
        return response()->json($role);
    }

    /**
     * update role
     * */
    public function updateRole(Request $request, $role_id){

        $request->validate([
            'name' => 'required|unique:roles,name,'.$role_id,
            'display_name' => 'required',
        ]);

        $role = Role::findOrFail($role_id);
        if(count($request->permissions)){
            $role->syncPermissions(array_column($request->permissions,'id'));
        }
        $role->fill($request->only('name','display_name','description'))->save();
        return response()->json([
            "status" => "success",
            "message" => "Role has been updated",
        ]);
    }

    /**
     * delete role
     * */
    public function deleteRole(Request $request, $role_id){

        $role = Role::findOrFail($role_id);
        $role = $role->delete();

        return response()->json([
            "status" => "success",
            "message" => "Role has been deleted",
        ]);
    }


    /**
     * return a list of all permissions
     * */
    public function getALlPermissions(){

        $permissions = Permission::all();
        return response()->json($permissions);

    }

    public function getPermissions(Request $request){

        $perPage = $request->perPage?(int)$request->perPage:10;

        $page = $request->page?(int)$request->page:1;
        $search = $request->search?$request->search:"";
        $toSearch = "%".$search."%";
        $query = Permission::where('name', 'LIKE' ,$toSearch)
            ->orWhere('display_name', 'LIKE' ,$toSearch)
            ->orWhere('description', 'LIKE' , $toSearch);
        $totalCount = $query->count();
        $roles = $query->take($perPage)->skip(($page-1)*$perPage)->get()  ;

        $toReturn = generatePagination($page,$perPage,$totalCount, $roles);
        return response()->json($toReturn);
    }

    /**
     * return a permission
     * */
    public function getPermission($permission_id){

        $permission = Permission::findOrFail($permission_id);
        $permission->load('roles');
        return response()->json($permission);
    }


    /**
     * add new permission
     * */
    public function addPermission(Request $request){

        $permission = new Permission();
        $permission->create($request->only('name','display_name','description'));
        if(count($request->roles)){
            $permission->roles()->sync(array_column($request->roles,'id'));
        }
        return response()->json([
            "status" => "success",
            "message" => "Permission has been added",
        ]);

    }

    /**
     * update permission
     * */
    public function updatePermission(Request $request, $permission_id){

        $permission = Permission::findOrFail($permission_id);
        $permission->fill($request->only('name','display_name','description'))->save();
        if($request->roles && count($request->roles)){
            $permission->roles()->sync(array_column($request->roles,'id'));
        }
        return response()->json([
            "status" => "success",
            "message" => "Permission has been updated",
        ]);

    }

    /**
     * delete permission
     * */
    public function deletePermission(Request $request, $permission_id){

        $permission = Permission::findOrFail($permission_id);
        $permission->delete();
        return response()->json([
            "status" => "success",
            "message" => "Permission has been deleted",
        ]);

    }


}

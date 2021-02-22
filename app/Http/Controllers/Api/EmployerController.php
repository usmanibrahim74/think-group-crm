<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployerController extends Controller
{
    public function getShortlisted(Request $request, $id){

        $perPage = $request->perPage?(int)$request->perPage:10;

        $page = $request->page?(int)$request->page:1;
        $search = $request->search?$request->search:"";
        $toSearch = "%".$search."%";

        $query = Candidate::where(function ($q)use($toSearch){
            return $q->where('title', 'LIKE' ,$toSearch)
                ->orWhere('title', 'LIKE' ,$toSearch)
                ->orWhere('first_name', 'LIKE' ,$toSearch)
                ->orWhere('last_name', 'LIKE' ,$toSearch)
                ->orWhere('email', 'LIKE' ,$toSearch)
                ->orWhere('city', 'LIKE' ,$toSearch)
                ->orWhere('postcode', 'LIKE' ,$toSearch)
                ->orWhere('contact', 'LIKE' ,$toSearch);
        });
        $query->whereHas('shortlistedBy',function ($q)use($id){
            return $q->where('id',$id);
        });
        $totalCount = $query->count();

        $data = $query->take($perPage)->skip(($page-1)*$perPage)->get()  ;


        $toReturn = generatePagination($page,$perPage,$totalCount, $data);

        return response()->json($toReturn);
    }
    public function getAllProfiles(){


        $employers = Employer::all();

        return response()->json($employers);

    }

    public function getProfiles(Request $request){

        $perPage = $request->perPage?(int)$request->perPage:10;

        $page = $request->page?(int)$request->page:1;
        $search = $request->search?$request->search:"";
        $toSearch = "%".$search."%";

        $query = Employer::where('name', 'LIKE' ,$toSearch)
            ->orWhere('display_name', 'LIKE' ,$toSearch)
            ->orWhere('email', 'LIKE' ,$toSearch)
            ->orWhere('city', 'LIKE' ,$toSearch)
            ->orWhere('postcode', 'LIKE' ,$toSearch)
            ->orWhere('phone', 'LIKE' ,$toSearch);
        $totalCount = $query->count();

        $data = $query->take($perPage)->skip(($page-1)*$perPage)->get();


        $toReturn = generatePagination($page,$perPage,$totalCount, $data);

        return response()->json($toReturn);

    }

    public function addProfile(Request $request){

        $messages = array(
            'phone.integer' => 'The phone field must be a valid number',
        );
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:candidates,email',
            'phone' => 'required|integer',
            'address_1' => 'required',
            'address_2' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'logo' => 'required',
        ], $messages);


        $data = $request->except('logo');

        $employer = new Employer();
        $employer->fill($data);

        if($request->logo){
            $folder = base64_encode($employer->email);
            $folder = str_replace('=','',$folder);
            $logo = $request->logo;
            $extension = explode('/', mime_content_type($logo))[1];
            $name = uniqid().'_'.time().".".$extension;
            $path = "uploads/employer/".$folder."/".$name;
            Storage::disk('public')->put($path, file_get_contents($logo));
            $employer->logo= $path;

        }

        $employer->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Employer has been added'
        ]);
    }

    public function getProfile($id){

        $employer = Employer::with('candidates')->findOrFail($id);
        return response()->json($employer);
    }

    public function updateProfile(Request $request, $id){


        $messages = array(
            'phone.integer' => 'The phone field must be a valid number',
        );
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:candidates,email,'.$id,
            'phone' => 'required|integer',
            'address_1' => 'required',
            'address_2' => 'required',
            'city' => 'required',
            'postcode' => 'required',
        ], $messages);


        $profile = Employer::findOrFail($id);
        $data = $request->except('logo');
        $profile->fill($data);

        if($request->logo){
            $folder = base64_encode($profile->email);
            $folder = str_replace('=','',$folder);
            $logo = $request->logo;
            $extension = explode('/', mime_content_type($logo))[1];
            $name = uniqid().'_'.time().".".$extension;
            $path = "uploads/employer/".$folder."/".$name;
            Storage::disk('public')->put($path, file_get_contents($logo));
            $profile->logo= $path;

        }

        $profile->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Employer has been update'
        ]);

    }

    public function deleteProfile($id){
        $profile = Employer::findOrFail($id);
        $profile->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Employer has been deleted'
        ]);
    }

    public function syncCandidates(Request $request, $id){
        $profile = Employer::findOrFail($id);
        $candidates = array_column($request->candidates,'id');
        $profile->candidates()->sync($candidates);

        return response()->json([
            'status' => 'success',
            'message' => 'Candidate has been attached to this employer'
        ]);
    }

    public function getAccounts(Request $request){

        $perPage = $request->perPage?(int)$request->perPage:10;

        $page = $request->page?(int)$request->page:1;
        $search = $request->search?$request->search:"";
        $toSearch = "%".$search."%";

        $query = User::whereRoleIs('employer')->where(function ($q)use($toSearch){
          return $q->where('name', 'LIKE' ,$toSearch)
              ->orWhere('email', 'LIKE' ,$toSearch);
        });
          $totalCount = $query->count();

        $data = $query->take($perPage)->skip(($page-1)*$perPage)->get()  ;


        $toReturn = generatePagination($page,$perPage,$totalCount, $data);

        return response()->json($toReturn);

    }

    public function addAccount(Request $request){

        $messages = array(
            'profile.required' => 'The employer field is required',
        );
        $request->validate([
            'profile' => 'required',
            'name' => 'required|max:255',
            'email' => 'required|email:filter|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ], $messages);

        $profile = $request->profile;
        $role = Role::where('name','employer')->first();
        $account = new User();
        $account->name = $request->name;
        $account->email = $request->email;
        $account->password = bcrypt($request->password);
        $account->employer_id = $profile['id'];
        $account->save();

        $account->roles()->attach($role); //employer

        return response()->json([
            'status' => 'success',
            'message' => 'Employer account has been created'
        ]);

    }

    public function getAccount($id){

        $account = User::with('profile')->findOrFail($id);
        return response()->json($account);
    }

    public function updateAccount(Request $request, $id){

        $messages = array(
            'profile.required' => 'The employer field is required',
        );
        $request->validate([
            'profile' => 'required',
            'name' => 'required|max:255',
            'email' => 'required|email:filter|max:255|unique:users,email,'.$id,
        ], $messages);


        $profile = $request->profile;
        $account = User::findOrFail($id);
        $account->name = $request->name;
        $account->email = $request->email;
        $account->employer_id = $profile['id'];

        $account->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Employer account has been updated'
        ]);

    }

    public function deleteAccount($id){

        $account = User::findOrFail($id);
        $account->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Employer account has been deleted'
        ]);
    }

    public function changeAccountPassword(Request $request, $id){
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $account = User::findOrFail($id);
        $account->password = bcrypt($request->password);
        $account->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Employer account password has been changed'
        ]);
    }

}

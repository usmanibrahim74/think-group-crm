<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Industry;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{

    public function getCandidates(Request $request){
        $user = auth()->user();
        if($user->hasPermission('view-candidate')){

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
            if($user->hasPermission('view-only-associated-candidate')){
                $employer_id = $user->profile->id;
                $query = $query->whereHas('employers',function ($q)use($employer_id){
                    return $q->where('id',$employer_id);
                });
            }
            $totalCount = $query->count();

            $data = $query->take($perPage)->skip(($page-1)*$perPage)->get()  ;


            $toReturn = generatePagination($page,$perPage,$totalCount, $data);

            return response()->json($toReturn);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "Access Denied"
            ])->setStatusCode(403);
        }

    }
    public function getShortlistedCandidates(Request $request){
        $user = auth()->user();
        if($user->hasPermission('view-shortlisted-candidate')){

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
            if($user->hasRole('admin')){
                $query = $query->has('shortlistedBy');
            }else{
                $employer_id = $user->profile->id;
                $query = $query->whereHas('shortlistedBy',function ($q)use($employer_id){
                    return $q->where('id',$employer_id);
                });
            }
            $totalCount = $query->count();

            $data = $query->take($perPage)->skip(($page-1)*$perPage)->get()  ;


            $toReturn = generatePagination($page,$perPage,$totalCount, $data);

            return response()->json($toReturn);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => "Access Denied"
            ])->setStatusCode(403);
        }

    }


    public function addCandidate(Request $request){

        $messages = array(
            'contact.integer' => 'The contact field must be a valid number',
        );
        $request->validate([
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:candidates,email',
            'contact' => 'required|integer',
            'address_1' => 'required',
            'address_2' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'industries' => 'required',
        ], $messages);
        $data = $request->except('industries','documents');
        $candidate = new Candidate();
        $candidate->fill($data)->save();
        $folder = base64_encode($candidate->email);
        $folder = str_replace('=','',$folder);

        if(gettype($request->documents) == "array"){

            $documents = $request->file('documents');
            foreach ($documents as $document){
                $name = uniqid().'_'.$document->getClientOriginalName();
                $path = "uploads/candidates/".$folder."/".$name;
                Storage::disk('public')->put($path, file_get_contents($document));
                $upload = new Upload();
                $upload->name = $name;
                $upload->path = $path;
                $candidate->uploads()->save($upload);
            }
        }

        $industries = array_column($request->industries,'id');
        $candidate->industries()->sync($industries);

        return response()->json([
            'status' => 'success',
            'message' => 'Candidate has been added'
        ]);
    }

    public function updateCandidate(Request $request,$id){

        $messages = array(
            'contact.integer' => 'The contact field must be a valid number',
        );
        $request->validate([
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:candidates,email,'.$id,
            'contact' => 'required|integer',
            'address_1' => 'required',
            'address_2' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'industries' => 'required',
        ], $messages);
        $data = $request->except('industries','documents');
        $candidate = Candidate::findOrFail($id);
        $candidate->fill($data)->save();
        $folder = base64_encode($candidate->email);
        $folder = str_replace('=','',$folder);


        if(gettype($request->documents) == "array"){

            $documents = $request->file('documents');
            foreach ($documents as $document){
                $name = uniqid().'_'.$document->getClientOriginalName();
                $path = "uploads/candidates/".$folder."/".$name;
                Storage::disk('public')->put($path, file_get_contents($document));
                $upload = new Upload();
                $upload->name = $name;
                $upload->path = $path;
                $candidate->uploads()->save($upload);
            }
        }


        $industries = array_column($request->industries,'id');
        $candidate->industries()->sync($industries);

        return response()->json([
            'status' => 'success',
            'message' => 'Candidate has been updated'
        ]);
    }

    public function getCandidate($id){

        $candidate = Candidate::with(['uploads','industries','shortlistedBy'])->findOrFail($id);
        return response()->json($candidate);
    }

    public function shortlistCandidate(Request $request, $id){

        $candidate = Candidate::findOrFail($id);
        $user = auth()->user()->profile;
        if($request->attach){
            $candidate->shortlistedBy()->attach($user->id);
            return response()->json([
                'status' => 'success',
                'message' => 'Candidate has been shortlisted'
            ]);
        }else{
            $candidate->shortlistedBy()->detach($user->id);
            return response()->json([
                'status' => 'success',
                'message' => 'Candidate has been removed from shortlist'
            ]);
        }

    }


    public function getIndustries(Request $request){

        $perPage = $request->perPage?(int)$request->perPage:10;

        $page = $request->page?(int)$request->page:1;
        $search = $request->search?$request->search:"";
        $toSearch = "%".$search."%";
        $query = Industry::where('name', 'LIKE' ,$toSearch);
        $totalCount = $query->count();
        $industries = $query->take($perPage)->skip(($page-1)*$perPage)->get()  ;
        $toReturn = generatePagination($page,$perPage,$totalCount, $industries);
        return response()->json($toReturn);

    }

    public function getAllIndustries(){

        $industries = Industry::all();
        return response()->json($industries);

    }

    public function addIndustry(Request $request){
        $industry = new Industry();
        $industry->name = $request->name;
        $industry->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Industry has been added',
        ]);
    }

    public function updateIndustry(Request $request, $id){
        $industry = Industry::findOrFail($id);
        $industry->name = $request->name;
        $industry->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Industry has been updated',
        ]);
    }

    public function deleteIndustry($id){
        $industry = Industry::findOrFail($id);
        $industry->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Industry has been deleted',
        ]);
    }


    public function deleteUpload($id){
        $upload = Upload::findOrFail($id);
        $upload->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Document has been deleted'
        ]);
    }


}

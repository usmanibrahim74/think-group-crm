<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Comment;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\ShortlistedCandidate;
use App\Models\Upload;
use App\Models\User;
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
            if(isset($request->department) && $request->department != ""){
                if($request->department == "healthcare"){
                    $query = $query->where('department_id',1);
                }else if($request->department == "education"){
                    $query = $query->where('department_id',2);
                }
            }
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
                $employer_id = $user->id;
                $query = $query->whereHas('shortlistedBy',function ($q)use($employer_id){
                    return $q->where('user_id',$employer_id);
                });
                $query = $query->with(['shortlist'=>function($q)use($employer_id){
                    return $q->with('comments')->where('user_id',$employer_id);
                }]);
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

    public function getShortlistedBy(Request $request, $id){

        $perPage = $request->perPage?(int)$request->perPage:10;

        $page = $request->page?(int)$request->page:1;
        $search = $request->search?$request->search:"";
        $toSearch = "%".$search."%";


        $query = User::with(['shortlist'=>function($q)use($id){
            return $q->with('comments')->where('candidate_id',$id);
        }])->where(function ($q)use($toSearch){
            return $q->where('name', 'LIKE' ,$toSearch)
                ->orWhere('email', 'LIKE' ,$toSearch);

        });
        $query = $query->whereHas('shortlistedCandidates',function ($q)use($id){
            return $q->where('candidate_id',$id);
        });
//        dd($query->toSql());
        $totalCount = $query->count();

        $data = $query->take($perPage)->skip(($page-1)*$perPage)->get();


        $toReturn = generatePagination($page,$perPage,$totalCount, $data);

        return response()->json($toReturn);

    }


    public function addCandidate(Request $request){

        $messages = array(
            'contact.integer' => 'The contact field must be a valid number',
            'department_id.required' => 'The department field is required',
        );
        $request->validate([
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date',
//            'email' => 'required|email|unique:candidates,email',
            'contact' => 'required|integer',
            'address_1' => 'required',
//            'address_2' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'department_id' => 'required',
//            'industries' => 'required',
        ], $messages);

        $data = $request->except('industries','documents');
        $candidate = new Candidate();
        $candidate->fill($data)->save();
        $folder = base64_encode($candidate->email);
        $folder = str_replace('=','',$folder);

        if(gettype($request->documents) == "array"){
            $documents = $request->documents;
            foreach ($documents as $document){

                if($document['file'] != 'null' && $document['name'] != 'null'){
                    $file = $document['file'];
                    $name = $document['name'];
                    $fileName = uniqid().'_'.$file->getClientOriginalName();
                    $path = "uploads/candidates/".$folder."/".$fileName;
                    Storage::disk('public')->put($path, file_get_contents($file));
                    $upload = new Upload();
                    $upload->name = $name;
                    $upload->file = $fileName;
                    $upload->path = $path;
                    $upload->expires_on = $document['expires_on'];
                    $candidate->uploads()->save($upload);
                }
            }
        }

        if($request->industries){
            $industries = array_column($request->industries,'id');
            $candidate->industries()->sync($industries);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Candidate has been added'
        ]);
    }

    public function deleteCandidate($id){
        $candidate = Candidate::findOrFail($id);
        $candidate->industries()->sync([]);
        $candidate->uploads()->delete();
        $candidate->employers()->sync([]);
        $candidate->shortlistedBy()->sync([]);
        $candidate->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Candidate has been deleted',
        ]);
    }

    public function updateCandidate(Request $request,$id){

        $messages = array(
            'contact.integer' => 'The contact field must be a valid number',
            'department_id.required' => 'The department field is required',
        );
        $request->validate([
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:candidates,email,'.$id,
            'contact' => 'required|integer',
            'address_1' => 'required',
//            'address_2' => 'required',
            'city' => 'required',
            'postcode' => 'required',
//            'industries' => 'required',
            'department_id' => 'required',
        ], $messages);
        $data = $request->except('industries','documents');
        $candidate = Candidate::findOrFail($id);
        $candidate->fill($data)->save();
        $folder = base64_encode($candidate->email);
        $folder = str_replace('=','',$folder);


        if(gettype($request->documents) == "array"){
            $names = $candidate->uploads->pluck('name')->all();
            $documents = $request->documents;

            foreach ($documents as $document){
                if(isset($document['file']) && $document['file'] != "null" && isset($document['name'])){

                    $file = $document['file'];
                    $name = $document['name'];
                    $fileName = uniqid().'_'.$file->getClientOriginalName();
                    $path = "uploads/candidates/".$folder."/".$fileName;
                    Storage::disk('public')->put($path, file_get_contents($file));
                    if(in_array($name,$names)){
                        $upload = Upload::where('name',$name)->first();
                        $upload->file = $fileName;
                        $upload->path = $path;
                        $upload->expires_on = $document['expires_on'];
                        $upload->save();
                    }else{
                        $upload = new Upload();
                        $upload->name = $name;
                        $upload->file = $fileName;
                        $upload->path = $path;
                        $upload->expires_on = $document['expires_on'];
                        $candidate->uploads()->save($upload);
                    }
                }
            }
        }

        $industries = [];
        if($request->industries){
            $industries = array_column($request->industries,'id');
        }
        $candidate->industries()->sync($industries);

        return response()->json([
            'status' => 'success',
            'message' => 'Candidate has been updated'
        ]);
    }

    public function addComment(Request $request, $id){

        $messages = array(
            'body.required' => 'comment is required.'
        );
        $request->validate([
            'body' => 'required',
        ], $messages);

        $user = auth()->user();
        $candidate = Candidate::findOrFail($id);
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->body = $request->body;
        $candidate->comments()->save($comment);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment has been added'
        ]);

    }

    public function addShortlistComment(Request $request, $id){

        $messages = array(
            'body.required' => 'comment is required.'
        );
        $request->validate([
            'body' => 'required',
        ], $messages);

        $user = auth()->user();
        $shortlist = ShortlistedCandidate::findOrFail($id);
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->body = $request->body;
        $shortlist->comments()->save($comment);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment has been added'
        ]);

    }

    public function getCandidate($id){
        $user = auth()->user();
        $isEmployer = $user->hasRole('employer');
        $candidate = Candidate::with(['uploads' => function($q)use($isEmployer){
            if($isEmployer){
                return $q->where('name', 'Safe CV');
            }
            return $q;
        },'industries'])->findOrFail($id);
        if(!$isEmployer){
            $candidate->load('comments');
        }else{
            $candidate->load(['shortlistedBy' => function ($q)use($user){
               $q->where('user_id',$user->id);
            }]);
        }
        return response()->json($candidate);
    }

    public function shortlistCandidate(Request $request, $id){

        $candidate = Candidate::findOrFail($id);
        $user = auth()->user();
        if($request->attach){
            $request->validate([
                'contact_via' => 'required',
            ]);

            $shortlisted = new ShortlistedCandidate();
            $shortlisted->user_id = $user->id;
            $shortlisted->candidate_id = $candidate->id;
            $shortlisted->contact_via = $request->contact_via;
            $shortlisted->employer_notes = $request->employer_notes;
            $shortlisted->save();

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

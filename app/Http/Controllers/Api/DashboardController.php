<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\ShortlistedCandidate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function get(){

        $user = auth()->user();
        $widgets = [];
        if($user->hasRole('admin')){
            $widget = new \stdClass();
            $widget->name = "Total Jobs";
            $widget->value = 1000;
            $widget->logo = "briefcase";

            array_push($widgets,$widget);


            $widget = new \stdClass();
            $widget->name = "Total Employers";
            $widget->value = Employer::count();
            $widget->logo = "box";

            array_push($widgets,$widget);


            $widget = new \stdClass();
            $widget->name = "Applications";
            $widget->value = 1000;
            $widget->logo = "file-text";

            array_push($widgets,$widget);


        }else if($user->hasRole('employer')){

            $widget = new \stdClass();
            $widget->name = "Available Candidates";
            $widget->value = $user->profile->candidates()->count();
            $widget->logo = "users";

            array_push($widgets,$widget);


            $widget = new \stdClass();
            $widget->name = "Shortlisted Candidates";
            $widget->value = $user->shortlistedCandidates()->count();
            $widget->logo = "users";

            array_push($widgets,$widget);

        }

        return response()->json($widgets);


    }
}

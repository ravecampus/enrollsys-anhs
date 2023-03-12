<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Enroll;

class DashboardController extends Controller
{
    public function getStudent(){
        $user = User::where('role', 0)->where('deleted', 0)->get();
        return response()->json($user, 200);
    }

    public function getEnrolled(){

        $sy = SchoolYear::where('is_active', 1)->first();
        $enr = Enroll::where('school_year_id', $sy->id)->get();
        return response()->json($enr, 200);
    }

    public function getSection(){
        $sec = Section::all();
        return response()->json($sec, 200);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JHSGrade;
use App\Models\User;
use App\Models\Subject;
use App\Models\SchoolYear;

class JHSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        // $student = User::where('student_type', 1)->where('role', 0)->get();
        // $subject = Subject::where('identity', 0)->get();
        // $actyer = SchoolYear::where('is_active', 1)->first();

        // foreach ($student as $key => $value) {
        //     foreach ($subject as $key => $val) {

        //         $chk = JHSGrade::where('school_year_id', $actyer->id)
        //         ->where('subject_id', $val['id'])
        //         ->where('subject_id', $val['id'])
        //         ->where('user_id', $value['id'])->get();

        //         if(count($chk) == 0){
        //             JHSGrade::create([
        //                 'user_id'=> $value['id'],
        //                 'subject_id'=> $val['id'],
        //                 'school_year_id'=> $actyer->id,
        //                 ]);
        //         }
        //     }
           
        // }

        $columns = ['created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;

        $sy = SchoolYear::where('is_active', 1)->first();

        $query = JHSGrade::with('studentd', 'subjectd')
            ->where('grade', $request->grade)
            ->where('school_year_id', $sy->id)
            ->where('section_id', $request->section)
            ->where('subject_id', $request->subject)
            ->orderBy('created_at', $dir);
    
        // if($searchValue){
        //     $query->where(function($query) use ($searchValue){
        //         $query->where('last_name', 'like', '%'.$searchValue.'%')
        //         ->orWhere('first_name', 'like', '%'.$searchValue.'%')
        //         ->orWhere('middle_name', 'like', '%'.$searchValue.'%');
        //     });
        // }
        $projects = $query->paginate($length);
        return ['data'=>$projects, 'draw'=> $request->draw];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_grade' => 'required',
            'second_grade' => 'required',
            'third_grade' => 'required',
            'fourth_grade' => 'required',
        ]);
        $jhs = JHSGrade::find($id);
        $jhs->first_grade = $request->first_grade;
        $jhs->second_grade = $request->second_grade;
        $jhs->third_grade = $request->third_grade;
        $jhs->fourth_grade = $request->fourth_grade;
        $jhs->average = $request->average;
        $jhs->remark = $request->remark;
        $jhs->save();
        return response()->json($jhs, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

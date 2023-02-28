<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JHSGrade;
use App\Models\SHSGrade;
use App\Models\User;
use App\Models\Subject;
use App\Models\SchoolYear;
use App\Models\Enroll;
use App\Models\EnrollSched;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class EnrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $auth = Auth::id();
        if($request->student_type == 1){
            $conf = Enroll::where('user_id', $auth)->where('school_year_id', $request->school_year)->first();
        }else if($request->student_type == 2){
            $conf = Enroll::where('user_id', $auth)
            ->where('school_year_id', $request->school_year)
            ->where('term', $request->term)
            ->first();
        }
       
     
        if(isset($conf)){
            $errors = ['errors'=>['errs' => ['You Already enrolled on this school year!']]];
            return response()->json($errors,401);
        }

        if($request->student_type == 1){
            $request->validate([
                'grade' => 'required',
                'section' => 'required',
            ]);

            $sched = Schedule::where('school_year', $request->school_year)
            ->where('grade', $request->grade)
            ->where('type', $request->student_type)
            ->where('section', $request->section)
            ->get();

            if(count($sched) <= 0){
                $errors = ['errors'=>['errs' => ['No schedule available!']]];
                return response()->json($errors,401);
            }
            // junior grade
            // $subject = Subject::where('identity', 0)->get();
            // $actyer = SchoolYear::where('is_active', 1)->first();
            foreach ($sched as $key => $val) {
                $chk = JHSGrade::where('school_year_id', $request->school_year)
                ->where('subject_id', $val['subject'])
                ->where('grade', $request->grade)
                ->where('section_id', $request->section)
                ->where('user_id', $request->user_id)->get();

                if(count($chk) == 0){
                    JHSGrade::create([
                        'user_id'=> Auth::id(),
                        'subject_id'=> $val['subject'],
                        'section_id'=> $request->section,
                        'grade'=> $request->grade,
                        'school_year_id'=> $request->school_year,
                        ]);
                }
            }
            
            $enroll = Enroll::create([
                'user_id' => Auth::id(),
                'student_type' => $request->student_type,
                'school_year_id' => $request->school_year,
                'grade' => $request->grade,
                'section_id' => $request->section
            ]);
           
            
            foreach ($sched as $key => $value) {
                EnrollSched::create([
                    'enroll_id'=> $enroll->id,
                    'schedule_id'=> $value['id'],
                    ]);
            }
        
        }else{
            $request->validate([
                'grade' => 'required',
                'section' => 'required',
                'term' => 'required',
                'strand' => 'required',
            ]);
            $sched = Schedule::where('school_year', $request->school_year)
                ->where('term', $request->term)
                ->where('grade', $request->grade)
                ->where('type', $request->student_type)
                ->where('section', $request->section)
                ->get();

            if(count($sched) <= 0){
                $errors = ['errors'=>['errs' => ['No schedule available!']]];
                return response()->json($errors,401);
            }
            // Senior Grade
            // $subject = Subject::where('identity', 1)->get();
            // $actyer = SchoolYear::where('is_active', 1)->first();
            foreach ($sched as $key => $val) {
                $chks = SHSGrade::where('school_year_id', $request->school_year)
                ->where('subject_id', $val['subject'])
                ->where('term', $request->term)
                ->where('grade', $request->grade)
                ->where('section_id', $request->section)
                ->where('user_id', $request->user_id)->get();

                if(count($chks) == 0){
                    SHSGrade::create([
                        'user_id'=> Auth::id(),
                        'subject_id'=> $val['subject'],
                        'section_id'=> $request->section,
                        'grade'=> $request->grade,
                        'term'=> $request->term,
                        'school_year_id'=> $request->school_year,
                        ]);
                }
            }

            $enroll = Enroll::create([
                'user_id' => Auth::id(),
                'student_type' => $request->student_type,
                'school_year_id' => $request->school_year,
                'grade' => $request->grade,
                'term' => $request->term,
                'strand' => $request->strand,
                'section_id' => $request->section
            ]);
         
            
            foreach ($sched as $key => $value) {
                EnrollSched::create([
                    'enroll_id'=> $enroll->id,
                    'schedule_id'=> $value['id'],
                    ]);
            }
        



        }
        return response()->json($enroll, 200);



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
        //
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

    public function getActiveEnrolled(){
        $sy = SchoolYear::where('is_active', 1)->first();
        $enr = Enroll::with('sectiond', 'strand','ensched')
            ->where('school_year_id', $sy->id)
            ->where('student_type', 2)
            ->where('user_id', Auth::id())->first();

        if(isset($enr)){
            $enrl = Enroll::with('sectiond', 'strand','ensched')
            ->where('school_year_id', $sy->id)
            ->where('student_type', 2)
            ->where('user_id', Auth::id())->get();
            $type = 2;
        }else{
            $enrl = Enroll::with('sectiond', 'strand','ensched')
            ->where('school_year_id', $sy->id)
            ->where('student_type', 1)
            ->where('user_id', Auth::id())->first();
            $type = 1;
        }

        
        
        return response()->json(['enr'=>$enrl,'type'=>$type] , 200);
    }
}

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
use App\Models\TransactionLog;
use Illuminate\Support\Facades\Auth;

class EnrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $length = $request->length;
        $dir = $request->dir;
        $searchValue = $request->search;
        $actyer = SchoolYear::where('is_active', 1)->first();
        $query = Enroll::with('sectiond','strand', 'sy')->select('enroll.*', 'users.first_name', 'users.last_name','users.middle_name','users.lrn')
                ->join('users', 'users.id', '=', 'enroll.user_id')
                ->where('enroll.school_year_id', $actyer->id)
                ->orderBy('created_at', 'asc');
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                // $query->where('last_name', 'like', '%'.$searchValue.'%')
                // ->orWhere('lrn', 'like', '%'.$searchValue.'%')
                // ->orWhere('first_name', 'like', '%'.$searchValue.'%')
                // ->orWhere('middle_name', 'like', '%'.$searchValue.'%');
            });
        }
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
        $auth = Auth::id();
       
        $congrade = Enroll::where('user_id', $auth)->where('grade', $request->grade)->first();
        if(isset($congrade) && ($request->student_type == 1)){
            $errors = ['errors'=>['grade' => ['You Already take that GRADE LEVEL!']]];
            return response()->json($errors,422);
        }

        $congrade_ = Enroll::where('user_id', $auth)
        ->where('grade', $request->grade)
        ->where('term', 1)
        ->first();
        $congrade_2 = Enroll::where('user_id', $auth)
        ->where('grade', $request->grade)
        ->where('term', 2)
        ->first();
        if(isset($congrade_) && isset($congrade_2) && ($request->student_type == 2)){
            $errors = ['errors'=>['grade' => ['You Already take that GRADE LEVEL2!']]];
            return response()->json($errors,422);
        }
        
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
                'section_id' => $request->section,
                'status' => 0
            ]);
           
            
            foreach ($sched as $key => $value) {
                EnrollSched::create([
                    'enroll_id'=> $enroll->id,
                    'schedule_id'=> $value['id'],
                    'user_id'=> Auth::id(),
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
                'section_id' => $request->section,
                'status' => 0,
            ]);
         
            
            foreach ($sched as $key => $value) {
                EnrollSched::create([
                    'enroll_id'=> $enroll->id,
                    'schedule_id'=> $value['id'],
                    'user_id'=> Auth::id(),
                    ]);
            }

        }

        if(isset($enroll)){
            TransactionLog::create([
                'user_id'=>Auth::id(),
                'event'=>'Enrollment',
                'data' => 'has been enrolled on Grade '.$request->grade
                 ]);
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
        $enroll = Enroll::with('sectiond','strand', 'sy', 'ensched')->select('enroll.*', 'users.first_name', 'users.last_name','users.middle_name','users.lrn')
                ->join('users', 'users.id', '=', 'enroll.user_id')
                ->where('enroll.id', $id)->first();
        return response()->json($enroll, 200);
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
        $enr = Enroll::find($id);
        
        $end = EnrollSched::where('enroll_id', $id)->delete();
        $jhs = JHSGrade::where('user_id', $enr->user_id)->delete();
        $jhs = SHSGrade::where('user_id', $enr->user_id)->delete();
        $enr->delete();
        if($enr->delete()){
            TransactionLog::create([
                'user_id'=>Auth::id(),
                'event'=>'Deleted',
                'data' => 'Enrollment has been deleted'.$enr->id
                 ]);
        }

        return response()->json($enr, 200);
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
            // ->where('status', 1)
            ->where('student_type', 2)
            ->where('user_id', Auth::id())->get();
            $type = $enrl->student_type;
        }else{
            $enrl = Enroll::with('sectiond', 'strand','ensched')
            ->where('school_year_id', $sy->id)
            ->where('student_type', 1)
            // ->where('status', 0)
            ->where('user_id', Auth::id())->first();
           
            if(isset($enrl)){
                $type = $enrl->student_type;
            }else{
                $type = 0;
            }
        }

        
        
        return response()->json(['enr'=>$enrl,'type'=>$type] , 200);
    }

    public function dropEnr(Request $request){
        $enrl = Enroll::find($request->id);
        $enrl->status = $request->status;
        $enrl->save();

        if(isset($enrl)){
            if($request->status == 1){
                $ev = "Enrolled";
                $enr = "Set as enrolled";
            }else{
                $ev = "Dropped";
                $enr = "Set as dropped";
            }
            TransactionLog::create([
                'user_id'=>Auth::id(),
                'event'=>$ev,
                'data' => $enr
                 ]);
        }

        return response()->json($enrl, 200);
    }

    public function enrollmentHistory(Request $request){

        $request->validate(['school_year'=>'required']);

        $length = $request->length;
        $dir = $request->dir;
        $searchValue = $request->search;
        $actyer = $request->school_year;
        $query = Enroll::with('sectiond','strand', 'sy')->select('enroll.*', 'users.first_name', 'users.last_name','users.middle_name','users.lrn')
                ->join('users', 'users.id', '=', 'enroll.user_id')
                ->where('enroll.school_year_id', $actyer)
                ->orderBy('created_at', 'asc');
    
        $projects = $query->paginate($length);
        return ['data'=>$projects, 'draw'=> $request->draw];
    }

    public function authEnrollYear(){
        $sy = SchoolYear::select('school_year.*', 'enroll.user_id')
            ->join('enroll', 'enroll.school_year_id','=','school_year.id')
            ->where('enroll.user_id', Auth::id())
            ->get();
        
        return response()->json($sy, 200);
    }

    public function acceptEnroll(Request $request){
        $enrl = Enroll::find($request->id);
        $enrl->status = 1;
        $enrl->save();
        return response()->json($enrl, 200);
    }

    public function studentEnroll(Request $request){
        $auth = $request->id;
       
        $congrade = Enroll::where('user_id', $auth)->where('grade', $request->grade)->first();
        if(isset($congrade) && ($request->student_type == 1)){
            $errors = ['errors'=>['grade' => ['You Already take that GRADE LEVEL!']]];
            return response()->json($errors,422);
        }

        $congrade_ = Enroll::where('user_id', $auth)
        ->where('grade', $request->grade)
        ->where('term', 1)
        ->first();
        $congrade_2 = Enroll::where('user_id', $auth)
        ->where('grade', $request->grade)
        ->where('term', 2)
        ->first();
        if(isset($congrade_) && isset($congrade_2) && ($request->student_type == 2)){
            $errors = ['errors'=>['grade' => ['You Already take that GRADE LEVEL2!']]];
            return response()->json($errors,422);
        }
        
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
                        'user_id'=> $auth,
                        'subject_id'=> $val['subject'],
                        'section_id'=> $request->section,
                        'grade'=> $request->grade,
                        'school_year_id'=> $request->school_year,
                        ]);
                }
            }
            
            $enroll = Enroll::create([
                'user_id' => $auth,
                'student_type' => $request->student_type,
                'school_year_id' => $request->school_year,
                'grade' => $request->grade,
                'section_id' => $request->section,
                'status' => 1
            ]);
           
            
            foreach ($sched as $key => $value) {
                EnrollSched::create([
                    'enroll_id'=> $enroll->id,
                    'schedule_id'=> $value['id'],
                    'user_id'=> $auth,
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
                        'user_id'=> $auth,
                        'subject_id'=> $val['subject'],
                        'section_id'=> $request->section,
                        'grade'=> $request->grade,
                        'term'=> $request->term,
                        'school_year_id'=> $request->school_year,
                        ]);
                }
            }

            $enroll = Enroll::create([
                'user_id' => $auth,
                'student_type' => $request->student_type,
                'school_year_id' => $request->school_year,
                'grade' => $request->grade,
                'term' => $request->term,
                'strand' => $request->strand,
                'section_id' => $request->section,
                'status' => 1,
            ]);
         
            
            foreach ($sched as $key => $value) {
                EnrollSched::create([
                    'enroll_id'=> $enroll->id,
                    'schedule_id'=> $value['id'],
                    'user_id'=>$auth,
                    ]);
            }

        }

        if(isset($enroll)){
            TransactionLog::create([
                'user_id'=>$auth,
                'event'=>'Enrollment',
                'data' => 'has been enrolled on Grade '.$request->grade
                 ]);
        }
        return response()->json($enroll, 200);

    }

}

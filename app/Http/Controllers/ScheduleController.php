<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\Section;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionLog;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $type = $request->type;
        $grade = $request->grade;
        $term = $request->term;
        $strand = $request->strand;
        $section = $request->section;
        $searchValue = $request->search;
        $actyer = SchoolYear::where('is_active', 1)->first();
        if(isset($actyer)){
            if($type == 1){
                $query = Schedule::with('subjectd', 'sectiond', 'teacherd')
                ->where('type', $type)
                ->where('grade', $grade)
                ->where('section', $section)
                ->where('school_year', $actyer->id)
                ->orderBy('created_at', $dir);
            }else if($type == 2){
                $query = Schedule::with('subjectd', 'sectiond', 'teacherd')
                ->where('type', $type)
                ->where('grade', $grade)
                ->where('term', $term)
                ->where('strand', $strand)
                ->where('section', $section)
                ->where('school_year', $actyer->id)
                ->orderBy('created_at', $dir);
            }
          
        }else{
            $errors = ['errors'=>['errs' => ['No active School Year!']]];
            return response()->json($errors,401);
        }
      
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                // $query->where('strand_code', 'like', '%'.$searchValue.'%');
                // ->orWhere('descriptive', 'like', '%'.$searchValue.'%');
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
        
       

        $sched = Schedule::where('school_year', $request->school_year)
                ->where('section', $request->section)
                ->where('subject', $request->subject)->first();

        if(isset($sched)){
            $errors = ['errors'=>['errs' => ['Subject has been already added for this section!']]];
            return response()->json($errors,422);
        }

        
        if($request->type == 1){
            $request->validate([
                'school_year' => 'required',
                'type' => 'required',
                // 'strand' => 'required',
                'adviser' => 'required',
                'grade' => 'required',
                // 'term' => 'required',
                'section' => 'required',
                'subject' => 'required',
                "day"    => "required|array|min:1",
                "day.*"  => "required|min:1",
                't_from' => 'required',
                't_to' => 'required',
                'teacher' => 'required',
            ]);
        }else{
            $request->validate([
                'school_year' => 'required',
                'type' => 'required',
                'strand' => 'required',
                'adviser' => 'required',
                'grade' => 'required',
                'term' => 'required',
                'section' => 'required',
                'subject' => 'required',
                "day"    => "required|array|min:1",
                "day.*"  => "required|min:1",
                't_from' => 'required',
                't_to' => 'required',
                'teacher' => 'required',
            ]);
        }
        
        $frm = Carbon::createFromTime($request->t_from['hours'], $request->t_from['minutes'], $request->t_from['seconds']);
        $to = Carbon::createFromTime($request->t_to['hours'], $request->t_to['minutes'], $request->t_to['seconds']);
          
        $con = Schedule::where('school_year', $request->school_year)
                ->where('section', $request->section)
                ->whereTime('t_from','>=',$frm)
                ->whereTime('t_from','<=', $to)
                ->whereTime('t_to','>=', $frm)
                ->whereTime('t_to','<=', $to)
                ->first();

        $reqday = json_decode($request->days);
        $dbday = json_decode($con->day);

        if(isset($con)){
            $data = array_intersect($reqday, $dbday);
            if(count($data) > 0){
                $errors = ['errors'=>['errs' => ['Conflict of time in the day of schedule!']]];
                return response()->json($errors,422);
            }
          
        }
        


        $sched = Schedule::create([
            'school_year' => $request->school_year,
            'type' => $request->type,
            'strand' => $request->strand,
            'adviser' => $request->adviser,
            'grade' => $request->grade,
            'term' => $request->term,
            'section' => $request->section,
            'subject' => $request->subject,
            'day' => $request->days,
            't_from' => $frm->format('H:i:s'),
            't_to' => $to->format('H:i:s'),
            'teacher' => $request->teacher,
        ]);

         if(isset($sched)){
            $auth = Auth::id();
            TransactionLog::create([
            'user_id'=>$auth,
            'event'=>'Schedule',
            'data' => 'Schedule has been added'
             ]);
        }

        return response()->json($sched, 200);
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
        if($request->type == 1){
            $request->validate([
                'school_year' => 'required',
                'type' => 'required',
                // 'strand' => 'required',
                'adviser' => 'required',
                'grade' => 'required',
                // 'term' => 'required',
                'section' => 'required',
                'subject' => 'required',
                "day"    => "required|array|min:1",
                "day.*"  => "required|min:1",
                't_from' => 'required',
                't_to' => 'required',
                'teacher' => 'required',
            ]);
        }else{
            $request->validate([
                'school_year' => 'required',
                'type' => 'required',
                'strand' => 'required',
                'adviser' => 'required',
                'grade' => 'required',
                'term' => 'required',
                'section' => 'required',
                'subject' => 'required',
                "day"    => "required|array|min:1",
                "day.*"  => "required|min:1",
                't_from' => 'required',
                't_to' => 'required',
                'teacher' => 'required',
            ]);
        }
        
        if(is_string($request->t_from)){
            
            $frm = Carbon::createFromTimeString($request->t_from);
        }else{
          
            $frm = Carbon::createFromTime($request->t_from['hours'], $request->t_from['minutes'], $request->t_from['seconds']);
        }

        if(is_string($request->t_to)){
            $to = Carbon::createFromTimeString($request->t_to);
        }else{
            $to = Carbon::createFromTime($request->t_to['hours'], $request->t_to['minutes'], $request->t_to['seconds']);
        }
      
    
        $sched = Schedule::find($id);
        $sched->school_year = $request->school_year;
        $sched->type = $request->type;
        $sched->strand = $request->strand;
        $sched->adviser = $request->adviser;
        $sched->grade = $request->grade;
        $sched->term = $request->term;
        $sched->section = $request->section;
        $sched->subject = $request->subject;
        $sched->day =  $request->days;
        $sched->t_from = $frm->format('H:i:s');
        $sched->t_to = $to->format('H:i:s');
        $sched->teacher = $request->teacher;
        $sched->save();

        return response()->json($sched, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sched = Schedule::find($id);
        $sched->delete();

        return response()->json($sched, 200);
    }

    public function defaultAcademicYear(Request $request){

        $sy = SchoolYear::where('is_active', 1)->first();
        return response()->json($sy, 200);
    }

    public function getStrand(){
        $strand = Strand::all();
        return response()->json($strand, 200);
    }
    public function getSection($id){
        $sec = Section::with('advise')->where('year_level', $id)->get();
        return response()->json($sec, 200);
    }

    public function getSubject($id){
        $subject = Subject::where('year_level', $id)->get();
        return response()->json($subject, 200);
    }

    public function getEnrollSchedule(Request $request){

        $sy = $request->school_year;
        $type = $request->type;
        $strand = $request->strand;
        $section = $request->section;
        $term = $request->term;
        $grade = $request->grade;

        if($type == 1){
            $sched = Schedule::with('subjectd','sectiond','teacherd')->where('school_year', $sy)
                    ->where('grade', $grade)
                    ->where('type', $type)
                    ->where('section', $section)
                    ->get();
        }else{
            $sched = Schedule::with('subjectd','sectiond','teacherd')->where('school_year', $sy)
                    ->where('grade', $grade)
                    ->where('type', $type)
                    ->where('section', $section)
                    ->where('strand', $strand)
                    ->where('term', $term)->get();
        }
        

        return response()->json($sched, 200);
    }
}

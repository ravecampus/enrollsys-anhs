<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\schedule;
use App\Models\SchoolYear;

class ClassListController extends Controller
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
        $searchValue = $request->search;
        $grade = $request->grade;
        $section = $request->section;
        $term = $request->term;
        $subject = $request->subject;

        $sy = SchoolYear::where('is_active', 1)->first();

        if($request->senior == 2){
            $request->validate([
                'grade' => 'required',
                'section' => 'required',
                'term' => 'required',
                'subject' => 'required',
            ]);
            $query = schedule::with('enrollsched', 'teacherd', 'school_year','sectiond','subjectd')->where('school_year', $sy->id)
            ->where('grade', $grade)
            ->where('section', $section)
            ->where('term', $term)
            ->where('subject', $subject)
            ->orderBy('created_at', $dir);
        }else{
            $request->validate([
                'grade' => 'required',
                'section' => 'required',
                'subject' => 'required',
            ]);
            $query = schedule::with('enrollsched', 'teacherd', 'school_year', 'sectiond','subjectd')->where('school_year', $sy->id)
            ->where('grade', $grade)
            ->where('section', $section)
            ->where('subject', $subject)
            ->orderBy('created_at', $dir);
        }
      
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                // $query->where('description', 'like', '%'.$searchValue.'%');
            });
        }
        $projects = $query->first();
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SHSGrade;
use App\Models\SchoolYear;

class SHSController extends Controller
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

        $sy = SchoolYear::where('is_active', 1)->first();

        $query = SHSGrade::with('studentd', 'subjectd')
            ->where('grade', $request->grade)
            ->where('school_year_id', $sy->id)
            ->where('section_id', $request->section)
            ->where('subject_id', $request->subject)
            ->where('term', $request->term)
            ->orderBy('created_at', $dir);

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
            'first_quarter' => 'required',
            'second_quarter' => 'required',
            ]);
            
        $jhs = SHSGrade::find($id);
        $jhs->first_quarter = $request->first_quarter;
        $jhs->second_quarter = $request->second_quarter;
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

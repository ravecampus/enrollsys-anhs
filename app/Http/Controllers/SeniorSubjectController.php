<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SeniorSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['subject_code','descriptve_title','created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = Subject::where('identity', 1)->orderBy('created_at', $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('subject_code', 'like', '%'.$searchValue.'%')
                ->orWhere('descriptive_title', 'like', '%'.$searchValue.'%');
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
        $request->validate([
            'subject_code'=> 'string|required',
            'descriptive_title'=> 'string|required',
            'unit'=> 'required',
            'year_level'=> 'required',
            'type'=> 'required',
            ]);

        $subject = Subject::create([
            'subject_code' => $request->subject_code,
            'descriptive_title' => $request->descriptive_title,
            'unit' => $request->unit,
            'year_level' => $request->year_level,
            'type' => $request->type,
            'identity' => 1,
        ]);

        return response()->json($subject, 200);
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
            'subject_code'=> 'string|required',
            'descriptive_title'=> 'string|required',
            'unit'=> 'required',
            'year_level'=> 'required',
            'type'=> 'required',
        ]);

        $subject = Subject::find($id);
        $subject->subject_code = $request->subject_code;
        $subject->descriptive_title = $request->descriptive_title;
        $subject->unit = $request->unit;
        $subject->year_level = $request->year_level;
        $subject->type = $request->type;
        $subject->save();
        
        return response()->json($subject, 200);
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

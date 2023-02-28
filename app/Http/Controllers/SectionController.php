<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;


class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['grade_level','section_name','created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = Section::with('advise')->orderBy('year_level', 'asc');
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('section_name', 'like', '%'.$searchValue.'%');
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
            'year_level' => 'required',
            'section_name' => 'string|required',
            'adviser' => 'required',
        ]);
        $sec = Section::create([
            'year_level' => $request->year_level,
            'section_name' => $request->section_name,
            'adviser' => $request->adviser,
        ]);

        return response()->json($sec, 200);
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
            'year_level' => 'required',
            'section_name' => 'string|required',
            'adviser' => 'required',
        ]);
        $sec = Section::find($id);

        $sec->year_level = $request->year_level;
        $sec->section_name = $request->section_name;
        $sec->adviser = $request->adviser;
        $sec->save();

        return response()->json($sec, 200);
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

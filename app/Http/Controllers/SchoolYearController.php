<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolYear;

class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['description','created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = SchoolYear::orderBy('created_at', $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('description', 'like', '%'.$searchValue.'%');
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
        $request->validate(['description'=>'required']);

        $sy = SchoolYear::create([
            'description' => $request->description
        ]);
        return response()->json($sy, 200);
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
        $request->validate(['description'=>'required']);

        $sy = SchoolYear::find($id);
        $sy->description = $request->description;
        $sy->save();
        return response()->json($sy, 200);
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

    public function setActive(Request $request){
        SchoolYear::where('is_active', 1)->update(array('is_active' => 0));
        $sy = SchoolYear::find($request->id);
        $sy->is_active = 1;
        $sy->save();
        return response()->json($sy, 200);
    }

    public function getActiveSY(){
        $sy = SchoolYear::where('is_active', 1)->first();

        return response()->json($sy, 200);
    }
}

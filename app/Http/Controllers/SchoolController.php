<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;

class SchoolController extends Controller
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
        $request->validate([
            'school_id' => 'required|string',
            'school_name' => 'required|string',
            'region' => 'required|string',
            'division' => 'required|string',
            'address' => 'required|string',
        ]);
        $school = School::find(1);
        if(isset($school)){
            $school->school_id = $request->school_id;
            $school->school_name = $request->school_name;
            $school->region = $request->region;
            $school->division = $request->division;
            $school->address = $request->address;
            $school->save();

        }else{
            $school = School::create([
                'school_id'=>$request->school_id,
                'school_name'=>$request->school_name,
                'region'=>$request->region,
                'division'=>$request->division,
                'address'=>$request->address,
            ]);
        }

        return response()->json($school, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $school = School::find($id);
        return response()->json($school, 200);
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

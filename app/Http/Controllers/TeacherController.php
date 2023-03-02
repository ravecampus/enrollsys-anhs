<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TeacherController extends Controller
{
    
    public function index(Request $request)
    {
        $columns = ['last_name','first_name','middle_name','created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = User::where('role', 1)->orderBy('last_name', $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('last_name', 'like', '%'.$searchValue.'%')
                ->orWhere('first_name', 'like', '%'.$searchValue.'%')
                ->orWhere('middle_name', 'like', '%'.$searchValue.'%');
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
            'last_name' => 'string|required',
            'first_name' => 'string|required',
            'middle_name' => 'string|required',
            'address' => 'string|required',
            'contact' => 'string|required'
        ]);

        $user = User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'address' => $request->address,
            'contact' => $request->contact,
            'role' => 1
        ]);

        return response()->json($user, 200);
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
            'last_name' => 'string|required',
            'first_name' => 'string|required',
            'middle_name' => 'string|required',
            'address' => 'string|required',
            'contact' => 'string|required'
        ]);

        $teacher = User::find($id);
        $teacher->last_name = $request->last_name;
        $teacher->first_name = $request->first_name;
        $teacher->middle_name = $request->middle_name;
        $teacher->address = $request->address;
        $teacher->contact = $request->contact;
        $teacher->save();

        return response()->json($teacher, 200);
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

    public function listTeacher(){
        $teacher = User::where('deleted', 0)->where('role',1)->get();
        return response()->json($teacher, 200);
    }
}

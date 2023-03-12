<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['last_name','first_name','middle_name','created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = User::where('role', 0)->where('deleted', 0)->orderBy('last_name', 'asc');
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('last_name', 'like', '%'.$searchValue.'%')
                ->orWhere('lrn', 'like', '%'.$searchValue.'%')
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
            'lrn' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'required|string',
            'age' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'birth_place' => 'required',
            'civil_status' => 'required',
            'citizenship' => 'required',
            'religion' => 'required|string',
            'contact' => 'required',
            'address' => 'required|string',
            'student_type' => 'required',
            'email' => 'required|string|email|unique:users,email',
        ]);

        $user = User::create([
            'lrn' => $request->lrn,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'birthdate' => Carbon::parse($request->birthdate)->format('Y-m-d'),
            'birth_place' => $request->birth_place,
            'civil_status' => $request->civil_status,
            'citizenship' => $request->citizenship,
            'religion' => $request->religion,
            'contact' => $request->contact,
            'address' => $request->address,
            'email' => $request->email,
            'student_type' => $request->student_type,
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
            'lrn' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'required|string',
            'age' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'birth_place' => 'required',
            'civil_status' => 'required',
            'citizenship' => 'required',
            'religion' => 'required|string',
            'contact' => 'required',
            'address' => 'required|string',
            'student_type' => 'required',
            'email' => 'required|string|email',
        ]);

        $user = User::find($id);
        $user->lrn = $request->lrn;
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->age = $request->age;
        $user->gender = $request->gender;
        $user->birthdate = Carbon::parse($request->birthdate)->format('Y-m-d');
        $user->birth_place = $request->birth_place;
        $user->civil_status = $request->civil_status;
        $user->citizenship = $request->citizenship;
        $user->religion = $request->religion;
        $user->contact = $request->contact;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->student_type = $request->student_type;
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->deleted = 1;
        $user->save();

        return response()->json($user, 200);
    }

    public function archiveStudent(Request $request){
        $columns = ['last_name','first_name','middle_name','created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = User::where('role', 0)->where('deleted', 1)->orderBy('last_name', 'asc');
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('last_name', 'like', '%'.$searchValue.'%')
                ->orWhere('lrn', 'like', '%'.$searchValue.'%')
                ->orWhere('first_name', 'like', '%'.$searchValue.'%')
                ->orWhere('middle_name', 'like', '%'.$searchValue.'%');
            });
        }
        $projects = $query->paginate($length);
        return ['data'=>$projects, 'draw'=> $request->draw];
    }

    public function restoreStudent(Request $request){
        $user = User::find($request->id);
        $user->deleted = 0;
        $user->save();

        return response()->json($user, 200);
    }

    public function listOfStudent(Request $request){
        $length = $request->length;
        $searchValue = $request->search;
        $query = User::with('enroll','shsgrade','jhsgrade')->where('role', 0)->where('deleted', 0)->orderBy('last_name', 'asc');
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('last_name', 'like', '%'.$searchValue.'%')
                ->orWhere('lrn', 'like', '%'.$searchValue.'%')
                ->orWhere('first_name', 'like', '%'.$searchValue.'%')
                ->orWhere('middle_name', 'like', '%'.$searchValue.'%');
            });
        }
        $projects = $query->paginate($length);
        return ['data'=>$projects, 'draw'=> $request->draw];
    }
}

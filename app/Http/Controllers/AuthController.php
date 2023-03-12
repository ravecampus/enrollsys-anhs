<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Session;
use Carbon\Carbon;

class AuthController extends Controller
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'required|string',
            'age' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'birth_place' => 'required',
            'civil_status' => 'required',
            'citizenship' => 'required',
            'student_type' => 'required',
            'religion' => 'required|string',
            'contact' => 'required',
            'address' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
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
            'student_type' => $request->student_type,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
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
            'email' => 'required|string|email',
            'student_type' => 'required',
            'username' => 'required|string',
            // 'password' => 'required|string|min:6|confirmed',
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
        $user->username = $request->username;
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
        //
    }

    public function signin(LoginRequest $request){
        $credentials = $request->getCredentials();

        if(!Auth::attempt($credentials)):
            // $request->session()->regenerate();
            $errors = ['errors'=>['errs' => ['Invalid Credentials!']]];
            return response()->json($errors,401);
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        $a = Auth::user();
        return response()->json($a,200);
   }

   public function logout(){
       try {
           Session::flush();
           $success = true;
           $message = 'Successfully logged out';
       } catch (\Illuminate\Database\QueryException $ex) {
           $success = false;
           $message = $ex->getMessage();
       }

       // response
       $response = [
           'success' => $success,
           'message' => $message,
       ];
       return response()->json($response);
   }

   public function changePassword(Request $request){
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find($request->id);
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json($user, 200);
   }

   public function updateAdmin(Request $request){
    $request->validate([
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'middle_name' => 'required|string',
        'gender' => 'required',
        'contact' => 'required',
        'address' => 'required|string',
        'username' => 'required|string',
    ]);

    $user = User::find($request->id);

    $user->last_name = $request->last_name;
    $user->first_name = $request->first_name;
    $user->middle_name = $request->middle_name;
    $user->gender = $request->gender;
    $user->contact = $request->contact;
    $user->address = $request->address;
    $user->username = $request->username;
    $user->save();

    return response()->json($user, 200);
   }

   public function authUser(){
       $user = User::with('enroll','shsgrade','jhsgrade')->where('id', Auth::id())->first();
       return response()->json($user, 200);
   }
}

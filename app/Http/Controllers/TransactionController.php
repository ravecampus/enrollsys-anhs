<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionLog;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $columns = ['last_name','first_name','middle_name','created_at'];
        // $length = $request->length;
        // $column = $request->column;
        // $dir = $request->dir;
        // $searchValue = $request->search;
        $query = TransactionLog::with('usercon')->orderBy('created_at', 'desc');
    
        // if($searchValue){
        //     $query->where(function($query) use ($searchValue){
        //         $query->where('last_name', 'like', '%'.$searchValue.'%')
        //         ->orWhere('first_name', 'like', '%'.$searchValue.'%')
        //         ->orWhere('middle_name', 'like', '%'.$searchValue.'%');
        //     });
        // }
        $projects = $query->paginate(10);
        return response()->json($projects, 200);
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

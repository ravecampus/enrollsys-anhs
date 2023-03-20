<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strand;

class StrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['strand_code','descriptive','created_at'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = Strand::orderBy('created_at', $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('strand_code', 'like', '%'.$searchValue.'%')
                ->orWhere('descriptive', 'like', '%'.$searchValue.'%');
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
            'strand_code'=>'string|required',
            'descriptive'=>'string|required',
            ]);
        $strand = Strand::create([
            'strand_code'=>$request->strand_code,
            'descriptive'=>$request->descriptive,
        ]);

        return response()->json($strand, 200);
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
            'strand_code'=>'string|required',
            'descriptive'=>'string|required',
            ]);
        $strand = Strand::find($id);
        $strand->strand_code = $request->strand_code;
        $strand->descriptive = $request->descriptive;
        $strand->save();
        
        return response()->json($strand, 200);
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

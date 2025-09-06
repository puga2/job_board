<?php

namespace App\Http\Controllers;

use App\Models\Employment_type;
use Illuminate\Http\Request;

class EmploymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(['data'=>Employment_type::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'type' => 'required|string|max:100'
        ]);
        $type = Employment_type::create($data);

        return response()->json(['message'=>'Employment type created successfully',
        'data'=>$type]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $type = Employment_type::find($id);

        if(!$type){
            return response()->json(['message'=>'Employment type not found'],404);
        }
        return response()->json(['data'=>$type]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $type = Employment_type::find($id);
        $data = $request->validate([
            'type'=>'required|string|max:100'
        ]);
        $type->update($data);

        return response()->json(['message'=>'Employment type updated successfully',
        'data'=>$type]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $type = Employment_type::find($id);

        if(!$type){
            return response()->json(['message'=>'Employment type not found'],404);

        }
        $type->delete();
        return response()->json(['message'=>'Employment type deleted successfully']);
    }
}

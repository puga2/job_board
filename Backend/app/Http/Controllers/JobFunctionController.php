<?php

namespace App\Http\Controllers;

use App\Models\Job_function;
use Illuminate\Http\Request;

class JobFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Job_function::with('category')->get());
    }
    
        /**
         * Display the specified resource.
         */
        public function show(string $id)
        {
            //
            return response()->json(Job_function::with('category')->findOrFail($id));
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'category_id'=>'nullable|exists:categories,id',
            'name'=>'required|string|max:120',
            'description'=>'nullable|string',

        ]);

        $jobFunction = Job_function::create($data);
        return response()->json([
            'message'=>'Job function created successfully',
            'data'=>$jobFunction
        ],201);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $jobFunction = Job_function::findOrFail($id);
        $data= $request->validate([
            'name'=>'required|string|max:120',
            'description'=>'nullable|string',

        ]);
        $jobFunction->update($data);
        return response()->json([
            'message'=>'Job function updated successfully',
            'data'=>$jobFunction
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $jobFunction = Job_function::findOrFail($id);
        $jobFunction->delete();
        return response()->json([
            'message'=>'Job Function delete successfully',
            'data'=>$jobFunction
        ],200);
    }
}

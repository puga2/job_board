<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
$query = Job::query()->with('job_function','company','location','category');
        
        if($request->category_id){
            $query->whereHas('category',function($q) use ($request){
                $q->where('id',$request->category_id);
            });
        }
        if($request->job_function_id){
            $query->where('job_function_id',$request->job_function_id);
        }
        if($request->keyword){
            $query->where('title','like','%'.$request->keyword.'%');
        }
        if($request->location_id){
            $query->where('location_id',$request->location_id);
        }
        $jobs = $query->paginate(10);

        return response()->json(['message'=>'Jobs fetched successfully','data'=>$jobs],200);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    
    public function show(string $id)
    {
        //
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'required|string',
            'salary_min'=>'required|numeric',
            'salary_max'=>'required|numeric',
            'job_function_id'=>'required|exists:job_functions,id',
            'location_id'=>'required|exists:locations,id',
            'company_id'=>'required|exists:companies,id',
            'employment_type_id'=>'required|exists:employment_types,id',
            'posted_at'=>'required|date',
            'expires_at'=>'nullable|date',
            'status'=>'sometimes|in:draft,published,closed',
        ]);

        $job = Job::create($validated);
        return response()->json([
            'message'=>'Job created successfully',
            'data'=>$job
        ],201);

    }

    /**
     * Display the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $job = Job::findOrFail($id);
        $data = $request->validate([
            'title'=>'sometimes|required|string|max:255',
            'description'=>'sometimes|required|string',
            'salary_min'=>'sometimes|required|numeric',
            'salary_max'=>'sometimes|required|numeric',
            'job_function_id'=>'sometimes|required|exists:job_functions,id',
            'location_id'=>'sometimes|required|exists:locations,id',
            'company_id'=>'sometimes|required|exists:companies,id',
            'employment_type_id'=>'sometimes|required|exists:employment_types,id',
            'posted_at'=>'sometimes|required|date',
            'expires_at'=>'nullable|date',
            'status'=>'sometimes|in:draft,published,closed',
        ]);
        $job->update($data);
        return response()->json([
            'message'=>'Job updated successfully',
            'data'=>$job
        ],200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $job = Job::findOrFail($id);
        $job->delete();

        return response()->json([
            'message'=>'Job deleted successfully',
            'data'=>$job->only('id','title')
        ],200);
    }
}

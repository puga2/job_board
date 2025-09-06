<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(['data'=>Location::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'city'=>'required|string|max:100',
            'state'=>'required|string|max:100'
        ]);

        $location = Location::create($data);
         return response()->json([
            'message'=>'Location created successfully',
            'data'=>$location->only(['id','city','state'])
         ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $location = Location::find($id);
        if(!$location){
            return response()->json(['message'=>'Location not found'],404);
        }
        return response()->json(['data'=>$location]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $location = Location::find($id);
        if(!$location){
            return response()->json(['message'=>'Location not found'],404);

        }
        $data= $request->validate([
            'city'=>'sometimes|string|max:100',
            'state'=>'sometimes|string|max:100'
        ]);
        $location->update($data);
        return response()->json(['message'=>'Location updated successfully','data'=>$location->only(['id','city','state'])]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $location = Location::find($id);

        if(!$location){
            return response()->json(['message'=>'Location not found'],404);
        }
        $location->delete();
        return response()->json(['message'=>'Location deleted successfully']);
    }
}

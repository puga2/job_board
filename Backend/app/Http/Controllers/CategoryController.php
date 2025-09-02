<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all categories
        return response()->json(['data'=>Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Create a new category

        $data = $request->validate([
            'name'=>'required|string|max:100',
            'description'=>'nullable|string|max:100',
        ]);
        $category = Category::create($data);

        return  response()->json([
            'message'=>'Category created successfully',
            'data'=>$category->only(['id','name','description'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $category = Category::find($id);

        if(!$category){
            return response()->json(['message'=>'Category not found'],404);
        }

        return response()->json(['data'=>$category]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message'=>'Category not found'],404);
        }
        $data= $request->validate([
            'name'=>'sometimes|string|max:100',
            'description'=>'sometimes|nullable|string|max:100',
        ]);
        $category->update($data);

        return response()->json(['message'=>'Category updated successfully','data'=>$category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = Category::find($id);
        if(!$category){
            return response()->json(['message'=>'Category not found'],404);
        }
        $category->delete();

        return response()->json(['message'=>'Category deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     */
    public function index()
    {
        // Get all users
        return response()->json(User::all());
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Get a single user by ID

        $user = User::find($id);
        
        if(!$user){
            return response()->json(['message'=>'User not found'],404);
        }

        return response()->json([
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'avatar'=>$user->avatar ? asset('storage/'.$user->avatar) : null,
            'role'=>$user->role,
            'resume'=>$user->job_seeker ? asset('storage/'.$user->job_seeker->resume) : null,
            'bio'=>$user->job_seeker ?  $user->job_seeker->bio : null,
            'created_at'=>$user->created_at,
            'updated_at'=>$user->updated_at
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    $fields = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
    ]);
    $user->update($fields);

    return response()->json(['message' => 'User updated successfully', 'data' => $user->only(['id','name','phone'])], 200);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        //Delete user
        $user = User::find($id);
            if(!$user){
            return response()->json(['message'=>'User not found'],404);
        }
        // delete avatar if it's not default
        if($user->avatar && $user->avatar !== 'users/default.png' && Storage::disk('public')->exists($user->avatar)){
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();
        return response()->json(['message'=>'User deleted successfully']);
    }

    // upload image

    public function uploadImage(Request $request,$id){
        $user = User::find($id);

        if(!$user){
            return response()->json(['message'=>'User not found'],404);

        }
        $request->validate([
            'avatar'=>'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        // only delete old image if it's not the default

        if($user->avatar !== 'users/default.png' && Storage::disk('public')->exists($user->avatar)){
            Storage::disk('public')->delete($user->avatar);
        }
        // store new image
        $path = $request->file('avatar')->store('users','public');
        // update column
        $user->avatar = $path;
        $user->save();

        return response()->json([
            'message'=>'Profile image updated successfully',
            'image_url'=>asset('storage/'.$path),
            'user'=>$user
        ]);
    }

    public function getProfile(Request $request){
        // automatically gets authenticated user
         
        $user = $request->user();

        
        // $user = User::with('job_seeker')->find($id);
            if(!$user){
                return response()->json([
                    'message'=>'User not found'
                ],404);
            }

            return response()->json([
                'message'=>'User profile fetched successfully',
                'data'=>[
                    'id'=>$user->id,
                    'name'=>$user->name,
                    'email'=>$user->email,
                    'phone'=>$user->phone,
                    'avatar'=>$user->avatar ? asset('storage/'.$user->avatar) : null,
                    'role'=>$user->role,
                    'resume'=>$user->job_seeker ? asset('storage/'.$user->job_seeker->resume) : null,
                    'bio'=>$user->job_seeker ?  $user->job_seeker->bio : null,
                    'created_at'=>$user->created_at,
                    'updated_at'=>$user->updated_at
                ]
            ],200);
    }

public function saveSeeker(Request $request, $id)
{
    $user = User::with('job_seeker')->find($id);

    if(!$user){
        return response()->json(['message'=>'User not found'], 404);
    }

    if($user->role !== 'job_seeker'){
        return response()->json(['message'=>'User is not a job seeker'], 400);
    }

    $data = $request->validate([
        'resume' => 'sometimes|nullable|mimes:pdf,doc,docx|max:2048',
        'bio' => 'sometimes|nullable|string|max:255'
    ]);

    // Handle resume upload
    if($request->hasFile('resume')){
        if($user->job_seeker && $user->job_seeker->resume 
           && Storage::disk('public')->exists($user->job_seeker->resume)){
            Storage::disk('public')->delete($user->job_seeker->resume);
        }
        $path = $request->file('resume')->store('resumes','public');
        $data['resume'] = $path;
    }

    if($user->job_seeker){
        // update existing
        $user->job_seeker->update($data);
        $message = 'Job seeker profile updated successfully';
    } else {
        // create new
        $data['user_id'] = $user->id;
        $user->job_seeker()->create($data);
        $message = 'Job seeker profile created successfully';
    }

    // Reload the relationship to get fresh data (important!)
    $user->load('job_seeker');

    return response()->json([
        'message' => $message,
        'data' => $user->job_seeker
    ]);
   }
}

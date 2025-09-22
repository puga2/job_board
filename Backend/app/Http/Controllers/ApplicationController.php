<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jobSeeker = $request->user()->job_seeker;
        if (!$jobSeeker) {
            return response()->json(['message' => 'Only job seekers can apply for jobs'], 403);
        }

        $data = $request->validate([
            'job_post_id' => 'required|exists:job_posts,id',
            'cover_letter' => 'sometimes|file|mimetypes:application/pdf|max:2048',
        ]);

        if (!$request->hasFile('cover_letter')) {
            return response()->json(['message' => 'cover_letter file missing'], 422);
        }
        $path = $request->file('cover_letter')->store('cover_letters', 'public');
        $data['cover_letter'] = $path;
        
        $application = Application::create([
            'job_post_id' => $data['job_post_id'],
            'job_seeker_id' => $jobSeeker->user_id, // ✅ must match your migration
            'cover_letter' => $data['cover_letter'],
        ]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'data' => $application
        ], 201);
    }


    /**
     * List all applications for the authenticated job seeker.
     */
    public function index(Request $request)
    {
        $jobSeeker = $request->user()->job_seeker;

        if (!$jobSeeker) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $applications = Application::where('job_seeker_id', $jobSeeker->id)->get();

        return response()->json([
            'message' => 'Applications retrieved successfully',
            'data' => $applications
        ], 200);
    }

    /**
     * Display a single application.
     */
    public function show(Request $request, string $id)
    {
        $jobSeeker = $request->user()->job_seeker;
        $application = Application::where('id', $id)
            ->where('job_seeker_id', $jobSeeker->id)
            ->first();

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        return response()->json([
            'message' => 'Application retrieved successfully',
            'data' => $application
        ], 200);
    }

    /**
     * Update an application (only cover letter typically).
     */
    public function update(Request $request, string $id)
    {
        $jobSeeker = $request->user()->job_seeker;

        $application = Application::where('id', $id)
            ->where('job_seeker_id', $jobSeeker->id)
            ->first();

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        $data = $request->validate([
            'cover_letter' => 'sometimes|required|string|max:255',
        ]);

        $application->update($data);

        return response()->json([
            'message' => 'Application updated successfully',
            'data' => $application
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $jobSeeker = $request->user()->job_seeker;

        $application = Application::where('id', $id)
            ->where('job_seeker_id', $jobSeeker->id)
            ->first();

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        $application->delete();

        return response()->json([
            'message' => 'Application deleted successfully'
        ], 200);
    }
}

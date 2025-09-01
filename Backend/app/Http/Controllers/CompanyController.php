<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all companies
        return response()->json(Company::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function apply(Request $request)
    {

        $user= $request->user();
        
        
        $data = $request->validate([

            'company_name'=> [
                'required','string','max:150',
                Rule::unique('companies','company_name')
                ->where(fn($q)=>$q->where('user_id',$user->id))
            ],
            'website'=> 'nullable|url|max:255',
            'description'=> 'required|string',
            'logo'=> 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        if(!$request->hasFile('logo')){
            return response()->json(['message'=>'Logo file missing'],422);
        }

        // store file in  storage //app/public/storage/employers
        $path  = $request->file('logo')->store('employers','public');
        $data['logo'] = $path;
        $data['user_id'] =  $request->user()->id;

        $company = $request->user()->companies()->create($data);

        return response()->json([
            'message'=>'Company Created',
            'data'=>$company,
            'logo_url'=>Storage::url($company->logo),
        ],201);


    }   



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Get a specific company
        $company = Company::find($id);
        if(!$company){
            return response()->json(['message'=>'Company not found'],404);
        }
             return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $user = $request->user();
    $company = Company::find($id);

    if (!$company) {
        return response()->json(['message' => 'Company not found'], 404);
    }

    // Check if the company belongs to the authenticated user
    if ($company->user_id !== $user->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Validate fields dynamically
    $data = $request->validate([
        'company_name' => [
            'sometimes', 'string', 'max:150',
            Rule::unique('companies', 'company_name')
                ->where(fn($q) => $q->where('user_id', $user->id))
                ->ignore($company->id) // Allow same name for this company
        ],
        'website' => 'sometimes|nullable|url|max:255',
        'description' => 'sometimes|string',
        'logo' => 'sometimes|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
    ]);

    // Only replace logo if a new one is uploaded
    if ($request->hasFile('logo')) {
        // Delete old logo if it exists
        if ($company->logo && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }

        $path = $request->file('logo')->store('employers', 'public');
        $data['logo'] = $path;
    }

    $company->update($data); // Corrected line

    return response()->json([
        'message' => 'Company information updated',
        'data' => $company->fresh()->only(['id', 'company_name', 'website', 'description', 'logo']),
    ]);
}

public function ChangeStatus(Request $request,string $id){
    // approve or reject from user admin
    // approve employer
    $company = Company::find($id);
    
    if(!$company){
        return response()->json(['message'=>'Company not found'],404);

    }
    $data = $request->validate([
        'status' => 'required|in:approved,rejected', // Adjust allowed values as needed
    ]);
    $company->update($data);

    return response()->json([
        'message' => 'Company status updated successfully',
        'data' => $company->fresh()->only(['id','status']),
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}

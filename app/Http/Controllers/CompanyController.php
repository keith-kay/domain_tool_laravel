<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }
    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);
        // Create a new Company instance with the validated data
        $company = new Company();
        $company->name = $validatedData['name'];
        $company->address = $validatedData['address'];
        $company->location = $validatedData['location'];
        
        // Save the company to the database
        $company->save();
        return redirect()->route('companies.index')->with('success', 'Company created successfully');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        // Validate input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        // Update the company
        $company->update($validatedData);

        // Redirect back to the index page with success message
        return redirect()->route('companies.index')->with('success', 'Company updated successfully');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully');
    }
}

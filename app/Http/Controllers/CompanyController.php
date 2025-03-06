<?php
namespace App\Http\Controllers;

use App\Models\Company;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::paginate(10);
//        return inertia('Company/Index', [
//            'companies' => $companies
//        ]);
        return Inertia::render('Company/Index', [
            'companies' => $companies
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Company/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'website' => 'required|url',
            'logo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $company = new Company();
        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('companies/logos', 'public');
            $company->logo = $logoPath;
        }


        $company->save($validatedData);

        return redirect()->route('companies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'website' => 'required|url',
        ]);

        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;


        $company->update($validatedData);

        return redirect()->route('companies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index');
    }
}

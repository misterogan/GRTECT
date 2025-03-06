<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Notifications\NewEmployeeNotification;

class EmployeeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with('company')->paginate(10);
        $companies = Company::all();

        return Inertia::render('Employee/Index', [
            'employees' => $employees,
            'companies' => $companies
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Employee/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        echo $request->full_name;die();
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:15',
            'company_id' => 'required|exists:companies,id',
        ]);

        $employee = new Employee();

        $full_name = explode(" ", $request->full_name);

        $first_name = array_shift($full_name);

        $last_name = implode(" ", $full_name);

        $employee->first_name = $first_name;
        $employee->last_name = $last_name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->company_id = $request->company_id;

        $company = Company::find($request->company_id);


        if ($company && $company->email) {
            $company->notify(new NewEmployeeNotification($employee));
        }


        $employee->save();
        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
//        echo $request->full_name;die();
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:15',
            'company_id' => 'required|exists:companies,id',
        ]);


        $full_name = explode(" ", $request->full_name);

        $first_name = array_shift($full_name);

        $last_name = implode(" ", $full_name);


        $employee->first_name = $first_name;
        $employee->last_name = $last_name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->company_id = $request->company_id;

        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index');
    }
}

<?php

namespace Vanguard\Http\Controllers\Web;
use Illuminate\Http\Request;
use \Vanguard\Employee;
use \Vanguard\Department;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('department')->get();
        return view('employees.index', compact('employees'));
    }

//    public function create()
//    {
//        return view('employees.add-edit', ['edit' => false]);
//    }
    public function create()
    {
        $departments = Department::all();
        return view('employees.add-edit', ['departments' => $departments, 'edit' => false]);
    }

    public function store(Request $request)
    {
        $employee = new Employee;
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->department_id = $request->input('department_id');
        // Assign other fields as needed
        $employee->save();
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

//    public function edit(Employee $employee)
//    {
//        return view('employees.add-edit', ['edit' => true, 'employee' => $employee]);
//    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.add-edit', ['employee' => $employee, 'edit' => true, 'departments' => $departments]);
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->department_id = $request->input('department_id');

        $employee->update();
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        // Deletion logic
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}

<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\MockTestResult;
use Vanguard\Http\Requests\MockTestResult\CreateMockTestResultRequest;
use Vanguard\Http\Requests\MockTestResult\UpdateMockTestResultRequest;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Vanguard\Imports\MockTestResultImport;

class MockTestResultController extends Controller
{
    /**
     * Display a listing of mock test results.
     */
    public function index(Request $request)
    {
        $query = MockTestResult::query();
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('mobile', 'like', '%' . $search . '%');
        }
    
        $results = $query->orderBy('mocktest_date', 'desc')->paginate(20);
        
        return view('mocktestresult.index', compact('results'))->with('search', $search ?? '');
    }

    /**
     * Show the form for creating a new mock test result.
     */
    public function create()
    {
        return view('mocktestresult.add-edit', ['edit' => false]);
    }

    /**
     * Store a newly created mock test result in storage.
     */
    public function store(CreateMockTestResultRequest $request)
    {
        MockTestResult::create($request->validated());

        return redirect()->route('mock_test_results.index')->with('success', 'Mock test result created successfully.');
    }

    /**
     * Show the form for editing the specified mock test result.
     */
    public function edit(MockTestResult $mockTestResult)
    {
        return view('mocktestresult.add-edit', compact('mockTestResult'))->with('edit', true);
    }

    /**
     * Update the specified mock test result in storage.
     */
    public function update(UpdateMockTestResultRequest $request, MockTestResult $mockTestResult)
    {
        $mockTestResult->update($request->validated());

        return redirect()->route('mock_test_results.index')->with('success', 'Mock test result updated successfully.');
    }

    /**
     * Remove the specified mock test result from storage.
     */
    public function destroy(MockTestResult $mockTestResult)
    {
        $mockTestResult->delete();

        return redirect()->route('mock_test_results.index')->with('success', 'Mock test result deleted successfully.');
    }


     /**
     * Show the form for importing mock test results.
     */
    public function importForm()
    {
        return view('mocktestresult.import');  // This will show the import form
    }

    /**
     * Handle the import of mock test results from an Excel file.
     */
    public function import(Request $request)
    {
        // Validate that the file is an Excel file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Adjust max size if needed
        ]);

        // Import the Excel file using the MockTestResultImport class
        Excel::import(new MockTestResultImport, $request->file('file'));

        return redirect()->route('mock_test_results.index')->with('success', 'Mock test results imported successfully.');
    }
}



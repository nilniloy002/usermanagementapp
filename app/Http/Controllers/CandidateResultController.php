<?php

namespace Vanguard\Http\Controllers;

use Vanguard\MockTestResult;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Carbon\Carbon;

class CandidateResultController extends Controller
{
    /**
     * Display the candidate result search form.
     */
    public function view()
    {
        //dd('hello');
        return view('candidate-result.view');
    }

    public function search(Request $request)
    {
        // Validate the inputs
        $request->validate([
            'mocktest_date' => 'required|date_format:d/m/Y', // Ensure the format is d/m/Y
            'mobile' => 'required|numeric',
        ]);
    
        try {
            // Parse the date into Y-m-d format for the database query
            $mockTestDate = Carbon::createFromFormat('d/m/Y', $request->mocktest_date)->format('Y-m-d');
        } catch (\Exception $e) {
            // Handle parsing errors, like invalid date format
            return redirect()->back()->withErrors(['Invalid date format. Please use d/m/Y format.']);
        }
    
        // Search for the candidate's result
        $result = MockTestResult::where('mocktest_date', $mockTestDate)
                                ->where('mobile', $request->mobile)
                                ->first();
    
        // If no result found, redirect back with error
        if (!$result) {
            return redirect()->back()->withErrors(['No result found for the provided details.']);
        }

         // Format numeric values to always show one decimal point
            $result->lstn_score = $result->lstn_score;
            $result->speak_score =$result->speak_score;
            $result->read_score = $result->read_score;
            $result->wrt_score = $result->wrt_score;
            $result->overall_score = $result->overall_score;

    
        // Pass the result data to the view if found
        return view('candidate-result.result', compact('result'));
    }
    
}


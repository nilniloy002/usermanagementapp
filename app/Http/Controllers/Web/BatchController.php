<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Batch;
use Vanguard\Course;
use Vanguard\Http\Controllers\Controller;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with('course')
        ->orderBy('created_at', 'DESC')
        ->get();
        return view('batches.index', compact('batches'));
    }

    public function create()
    {
        $courses = Course::where('status', 'On')->get();
        return view('batches.add-edit', compact('courses'), ['edit' => false]);
    }

    public function store(Request $request)
    {
        // Validation logic here

        Batch::create($request->all());

        return redirect()->route('batches.index')->with('success', 'Batch created successfully.');
    }

    public function edit(Batch $batch)
    {
        $courses = Course::where('status', 'On')->get();
        return view('batches.add-edit', compact('batch', 'courses'), ['edit' => true]);
    }

    public function update(Request $request, Batch $batch)
    {
        // Validation logic here

        $batch->update($request->all());

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();

        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }
    //     public function checkBatchCode(Request $request)
    // {
    //     $batchCode = $request->get('batch_code');
    //     $exists = Batch::where('batch_code', $batchCode)->exists();
        
    //     return response()->json(['exists' => $exists]);
    // }

    public function checkBatchCode(Request $request)
    {
        $batchCode = $request->get('batch_code');
        $exists = Batch::where('batch_code', $batchCode)->exists();
        
        return response()->json(['exists' => $exists]);
    }
}

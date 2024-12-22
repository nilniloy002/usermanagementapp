<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanguard\AnotherDay;
use Vanguard\Http\Requests\AnotherDay\CreateAnotherDayRequest;
use Vanguard\Http\Requests\AnotherDay\UpdateAnotherDayRequest;


class AnotherDayController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Modify the query to include the search functionality if needed
        $anotherDays = AnotherDay::query()
            ->where('candidate_email', 'like', '%' . $search . '%')
            ->orWhere('trainers_email', 'like', '%' . $search . '%')
            ->paginate(10); // Paginate with 10 results per page
        
        return view('anotherdays.index', compact('anotherDays'));
    }
    

    public function create()
    {
        return view('anotherdays.add-edit', ['edit' => false]);
    }

    public function store(CreateAnotherDayRequest $request)
    {
        AnotherDay::create($request->validated());

        return redirect()->route('another_days.index')->with('success', 'Booking created successfully.');
    }

    public function edit(AnotherDay $anotherDay)
    {
        return view('anotherdays.add-edit', compact('anotherDay'), ['edit' => true]);
    }

    public function update(UpdateAnotherDayRequest $request, AnotherDay $anotherDay)
    {
        $anotherDay->update($request->validated());

        return redirect()->route('another_days.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(AnotherDay $anotherDay)
    {
        $anotherDay->delete();

        return redirect()->route('another_days.index')->with('success', 'Booking deleted successfully.');
    }
}

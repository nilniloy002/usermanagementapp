<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\MockTestDate;
use Vanguard\Http\Requests\MockTestDate\CreateMockTestDateRequest;
use Vanguard\Http\Requests\MockTestDate\UpdateMockTestDateRequest;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class MockTestDateController extends Controller
{
    public function index()
    {
        $mockTestDates = MockTestDate::orderBy('created_at', 'desc')->paginate(10);
        return view('mocktestdates.index', compact('mockTestDates'));
    }

    public function create()
    {
        return view('mocktestdates.add-edit', ['edit' => false]);
    }

    public function store(CreateMockTestDateRequest $request)
    {
        MockTestDate::create($request->validated());
        return redirect()->route('mock_test_dates.index')->withSuccess(__('Mock Test Date added successfully.'));
    }

    public function edit(MockTestDate $mockTestDate)
    {
        return view('mocktestdates.add-edit', ['mockTestDate' => $mockTestDate, 'edit' => true]);
    }

    public function update(UpdateMockTestDateRequest $request, MockTestDate $mockTestDate)
    {
        $mockTestDate->update($request->validated());
        return redirect()->route('mock_test_dates.index')->withSuccess(__('Mock Test Date updated successfully.'));
    }

    public function destroy(MockTestDate $mockTestDate)
    {
        $mockTestDate->delete();
        return redirect()->route('mock_test_dates.index')->withSuccess(__('Mock Test Date deleted successfully.'));
    }
}

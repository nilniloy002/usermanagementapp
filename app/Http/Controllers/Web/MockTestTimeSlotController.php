<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\MockTestTimeSlot;
use Vanguard\Http\Requests\MockTestTimeSlot\CreateMockTestTimeSlotRequest;
use Vanguard\Http\Requests\MockTestTimeSlot\UpdateMockTestTimeSlotRequest;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class MockTestTimeSlotController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $mockTestTimeSlots = MockTestTimeSlot::when($search, function ($query, $search) {
            return $query->where('slot_key', 'like', '%' . $search . '%');
        })
        ->orderBy('id', 'asc')
        ->paginate(15);

        return view('mocktesttimeslots.index', compact('mockTestTimeSlots', 'search'));
    }

    public function create()
    {
        return view('mocktesttimeslots.add-edit', ['edit' => false]);
    }

    public function store(CreateMockTestTimeSlotRequest $request)
    {
        MockTestTimeSlot::create($request->validated());
        return redirect()->route('mock_test_time_slots.index')->with('success', 'Time slot created successfully.');
    }

    public function edit(MockTestTimeSlot $mockTestTimeSlot)
    {
        return view('mocktesttimeslots.add-edit', ['edit' => true, 'mockTestTimeSlot' => $mockTestTimeSlot]);
    }

    public function update(UpdateMockTestTimeSlotRequest $request, MockTestTimeSlot $mockTestTimeSlot)
    {
        $mockTestTimeSlot->update($request->validated());
        return redirect()->route('mock_test_time_slots.index')->with('success', 'Time slot updated successfully.');
    }

    public function destroy(MockTestTimeSlot $mockTestTimeSlot)
    {
        $mockTestTimeSlot->delete();
        return redirect()->route('mock_test_time_slots.index')->with('success', 'Time slot deleted successfully.');
    }
}

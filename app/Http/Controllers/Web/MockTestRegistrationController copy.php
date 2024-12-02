<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\MockTestRegistration;
use Vanguard\MockTestDate;
use Vanguard\MockTestTimeSlot;
use Vanguard\MockTestRoom;
use Vanguard\MockTestStatus;
use Vanguard\Http\Requests\MockTestRegistration\CreateMockTestRegistrationRequest;
use Vanguard\Http\Requests\MockTestRegistration\UpdateMockTestRegistrationRequest;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class MockTestRegistrationController extends Controller
{
    /**
     * List all Mock Test Registrations
     */
    public function index()
    {
        $mockTestRegistrations = MockTestRegistration::all();
        return view('mocktestregistrations.index', compact('mockTestRegistrations'));
    }

    /**
     * Show the form to create a new Mock Test Registration
     */
    public function create()
    {
        $dates = MockTestDate::where('status', 'On')->get();
        $statuses = MockTestStatus::where('status', 'On')->get();
        $lrwTimeSlots = MockTestTimeSlot::where('slot_key', 'LRW Slot')->where('status', 'On')->get();
        $speakingTimeSlots = MockTestTimeSlot::whereIn('slot_key', ['Speaking Slot Morning', 'Speaking Slot Afternoon'])->where('status', 'On')->get();
        $rooms = MockTestRoom::where('status', 'On')->get();
    
        // Optional: If you want to pre-check availability based on speaking time slot
        // You could check if a room is already booked for a speaking time slot here and pass it to the view.
        $roomAvailability = [];
    
        foreach ($speakingTimeSlots as $slot) {
            // Get available rooms for each speaking time slot
            $roomAvailability[$slot->id] = MockTestRoom::whereDoesntHave('mockTestRegistrations', function ($query) use ($slot) {
                $query->where('speaking_time_slot_id', $slot->id);
            })->get(); // Get rooms where no bookings exist for this slot
        }
    
        return view('mocktestregistrations.add-edit', compact('dates', 'statuses', 'lrwTimeSlots', 'speakingTimeSlots', 'rooms', 'roomAvailability'));
    }
    /**
     * Store a newly created Mock Test Registration
     */
    public function store(CreateMockTestRegistrationRequest $request)
    {
        $data = $request->validated();

        // Handle "Another Day" logic for speaking time slot and room
        if ($data['speaking_time_slot_id_another_day']) {
            $data['speaking_time_slot_id'] = null; // Clear the speaking time slot
            $data['speaking_room_id'] = null; // Clear the speaking room
        }

        // Optionally, you may want to check for room availability here (if needed)
        // Example: Check if a room is already booked for the same speaking time slot
        if (isset($data['speaking_time_slot_id']) && $data['speaking_time_slot_id_another_day'] != 1) {
            $roomAvailability = MockTestRegistration::where('speaking_time_slot_id', $data['speaking_time_slot_id'])
                                                     ->where('speaking_room_id', $data['speaking_room_id'])
                                                     ->exists();

            if ($roomAvailability) {
                return redirect()->route('mock_test_registrations.create')
                                 ->with('error', __('The selected room is already booked for this speaking time slot.'));
            }
        }

        MockTestRegistration::create($data);

        return redirect()->route('mock_test_registrations.index')
                         ->with('success', __('Mock Test Registration created successfully.'));
    }

    /**
     * Show the form to edit an existing Mock Test Registration
     */
    public function edit(MockTestRegistration $mockTestRegistration)
    {
        $dates = MockTestDate::where('status', 'On')->get();
        $statuses = MockTestStatus::where('status', 'On')->get();
        $lrwTimeSlots = MockTestTimeSlot::where('slot_key', 'LRW Slot')->where('status', 'On')->get();
        $speakingTimeSlots = MockTestTimeSlot::whereIn('slot_key', ['Speaking Slot Morning', 'Speaking Slot Afternoon'])->where('status', 'On')->get();
        $rooms = MockTestRoom::where('status', 'On')->get();

        // Optional: If you want to pre-check availability based on speaking time slot
        $roomAvailability = [];

        foreach ($speakingTimeSlots as $slot) {
            // Get available rooms for each speaking time slot
            $roomAvailability[$slot->id] = MockTestRoom::whereDoesntHave('mockTestRegistrations', function ($query) use ($slot) {
                $query->where('speaking_time_slot_id', $slot->id);
            })->get(); // Get rooms where no bookings exist for this slot
        }

        return view('mocktestregistrations.add-edit', compact('mockTestRegistration', 'dates', 'statuses', 'lrwTimeSlots', 'speakingTimeSlots', 'rooms', 'roomAvailability'));
    }

    /**
     * Update a Mock Test Registration
     */
    public function update(UpdateMockTestRegistrationRequest $request, MockTestRegistration $mockTestRegistration)
    {
        $data = $request->validated();

        // Handle "Another Day" logic for speaking time slot and room
        if ($data['speaking_time_slot_id_another_day']) {
            $data['speaking_time_slot_id'] = null; // Clear the speaking time slot
            $data['speaking_room_id'] = null; // Clear the speaking room
        }

        // Optionally, you may want to check for room availability here (if needed)
        // Example: Check if a room is already booked for the same speaking time slot
        if (isset($data['speaking_time_slot_id']) && $data['speaking_time_slot_id_another_day'] != 1) {
            $roomAvailability = MockTestRegistration::where('speaking_time_slot_id', $data['speaking_time_slot_id'])
                                                     ->where('speaking_room_id', $data['speaking_room_id'])
                                                     ->exists();

            if ($roomAvailability) {
                return redirect()->route('mock_test_registrations.edit', $mockTestRegistration)
                                 ->with('error', __('The selected room is already booked for this speaking time slot.'));
            }
        }

        $mockTestRegistration->update($data);

        return redirect()->route('mock_test_registrations.index')
                         ->with('success', __('Mock Test Registration updated successfully.'));
    }

    /**
     * Delete a Mock Test Registration
     */
    public function destroy(MockTestRegistration $mockTestRegistration)
    {
        $mockTestRegistration->delete();

        return redirect()->route('mock_test_registrations.index')
                         ->with('success', __('Mock Test Registration deleted successfully.'));
    }
}

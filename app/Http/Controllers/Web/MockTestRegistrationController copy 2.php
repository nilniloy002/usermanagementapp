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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Vanguard\Mail\MockTestTokenEmail;


class MockTestRegistrationController extends Controller
{
    /**
     * List all Mock Test Registrations
     */
    public function index(Request $request)
    {
        // Retrieve filter values from the request
        $examDate = $request->input('exam_date');
        $lrwTime = $request->input('lrw_time');
    
        // Start query with base model
        $query = MockTestRegistration::with(['mockTestDate', 'examStatus', 'speakingTimeSlot', 'speakingRoom']);
    
        // Apply filters if provided
        if ($examDate) {
            $query->whereHas('mockTestDate', function ($q) use ($examDate) {
                $q->whereDate('mocktest_date', $examDate);
            });
        }
    
        if ($lrwTime) {
            $query->where('lrw_time_slot', $lrwTime);
        }
    
        // Order the results in descending order (e.g., by creation date)
        $query->orderBy('created_at', 'desc');
    
        // Paginate the results
        $totalRegistrations = $query->count(); // Get total count of all filtered records

        //$mockTestRegistrations = $query->paginate(10);
        $mockTestRegistrations = $query->get();  // Fetch all records

       
        //dd($mockTestRegistrations);
    
        // Fetch distinct LRW times for the filter dropdown
        $lrwTimes = MockTestRegistration::distinct()->pluck('lrw_time_slot');
    
        // Fetch distinct exam dates from MockTestDate model for the filter dropdown
        $examDates = MockTestDate::distinct()->pluck('mocktest_date');
        
        // Return view with data
        return view('mocktestregistrations.index', compact('totalRegistrations','mockTestRegistrations', 'lrwTimes', 'examDates'));
    }
    
    
    

    public function create()
    {
        $dates = MockTestDate::where('status', 'On')->get();
        $statuses = MockTestStatus::where('status', 'On')->get();
        $lrwTimeSlots = MockTestTimeSlot::where('slot_key', 'LRW Slot')->where('status', 'On')->get();
        $speakingTimeSlots = MockTestTimeSlot::whereIn('slot_key', ['Speaking Slot Morning', 'Speaking Slot Afternoon'])->where('status', 'On')->get();
        $rooms = MockTestRoom::where('status', 'On')->get();
    
        // Get room availability for each date and speaking time slot
        $roomAvailability = [];
        foreach ($dates as $date) {
            $roomAvailability[$date->id] = [];
            foreach ($speakingTimeSlots as $slot) {
                $bookedRooms = MockTestRegistration::where('mock_test_date_id', $date->id)
                    ->where('speaking_time_slot_id', $slot->id)
                    ->pluck('speaking_room_id')
                    ->toArray();
                $roomAvailability[$date->id][$slot->id] = $bookedRooms;
            }
        }

        // Check LRW Time Slot availability
        $morningSlotAvailable = $this->checkLRWSlotAvailability('10:30AM-02:30PM');
        $eveningSlotAvailable = $this->checkLRWSlotAvailability('03:30PM-06:30PM');
    
        return view('mocktestregistrations.add-edit', compact(
            'dates', 'statuses', 'lrwTimeSlots', 'speakingTimeSlots', 'rooms', 
            'roomAvailability', 'morningSlotAvailable', 'eveningSlotAvailable'
        ));
    }

    public function store(CreateMockTestRegistrationRequest $request)
    {
        $data = $request->validated();

        // Assign default value if 'invoice_no' is not provided
        if (empty($data['invoice_no'])) {
                $data['invoice_no'] = 'N/A';
        }
        // Validate Room Availability for Speaking Slots
        if (!empty($data['speaking_time_slot_id'])) {
            $isRoomBooked = MockTestRegistration::where('mock_test_date_id', $data['mock_test_date_id'])
                ->where('speaking_time_slot_id', $data['speaking_time_slot_id'])
                ->where('speaking_room_id', $data['speaking_room_id'])
                ->exists();

            if ($isRoomBooked) {
                return redirect()->back()->with('error', __('The selected room is already booked for this time slot.'));
            }
        }

        // Create Registration
        MockTestRegistration::create($data);

        return redirect()->route('mock_test_registrations.index')->with('success', __('Mock Test Registration created successfully.'));
    }

    /**
     * Show the form to edit an existing Mock Test Registration
     */
    public function edit($id)
    {
        $mockTestRegistration = MockTestRegistration::with(['examStatus', 'speakingTimeSlot'])->findOrFail($id);
    
        $dates = MockTestDate::where('status', 'On')->get();
        $statuses = MockTestStatus::where('status', 'On')->get(); // For Candidate Status
        $lrwTimeSlots = MockTestTimeSlot::where('slot_key', 'LRW Slot')->where('status', 'On')->get();
        $speakingTimeSlots = MockTestTimeSlot::whereIn('slot_key', ['Speaking Slot Morning', 'Speaking Slot Afternoon'])->where('status', 'On')->get();
        $rooms = MockTestRoom::where('status', 'On')->get();
    
        $roomAvailability = $this->getRoomAvailability();
    
        return view('mocktestregistrations.add-edit', compact(
            'mockTestRegistration', 'dates', 'statuses', 'lrwTimeSlots', 
            'speakingTimeSlots', 'rooms', 'roomAvailability'
        ));
    }
    
    
    
    public function update(UpdateMockTestRegistrationRequest $request, $id)
    {
        $data = $request->validated();

        // Assign default value if 'invoice_no' is not provided
        if (empty($data['invoice_no'])) {
            $data['invoice_no'] = 'N/A';
        }


        $mockTest = MockTestRegistration::findOrFail($id);

        // Validate room availability for Speaking Slots
        if (!empty($data['speaking_time_slot_id']) && !empty($data['speaking_room_id'])) {
            $isRoomBooked = MockTestRegistration::where('mock_test_date_id', $data['mock_test_date_id'])
                ->where('speaking_time_slot_id', $data['speaking_time_slot_id'])
                ->where('speaking_room_id', $data['speaking_room_id'])
                ->where('id', '!=', $id) // Exclude current registration
                ->exists();

            if ($isRoomBooked) {
                return redirect()->back()->withErrors([
                    'speaking_room_id' => __('The selected room is already booked for this time slot.')
                ]);
            }
        }

        // Update the registration
        $mockTest->update($data);

        return redirect()->route('mock_test_registrations.index')->with('success', __('Mock Test Registration updated successfully.'));
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

    /**
     * Helper method to check LRW Slot availability
     */
    private function checkLRWSlotAvailability($timeSlot)
    {
        $bookedCount = MockTestRegistration::where('lrw_time_slot', $timeSlot)->count();
        $maxSlotCount = 39; // Assuming the limit is 39 registrations per slot
        return $bookedCount < $maxSlotCount;
    }

    private function getRoomAvailability()
    {
        // Fetch booked rooms grouped by date and time slot
        $bookedRooms = MockTestRegistration::select('mock_test_date_id', 'speaking_time_slot_id', 'speaking_room_id')
            ->whereNotNull('speaking_time_slot_id')
            ->whereNotNull('speaking_room_id')
            ->get()
            ->groupBy(['mock_test_date_id', 'speaking_time_slot_id']);

        // Transform data into the required structure
        $roomAvailability = [];
        foreach ($bookedRooms as $date => $timeSlots) {
            foreach ($timeSlots as $timeSlot => $records) {
                $roomAvailability[$date][$timeSlot] = $records->pluck('speaking_room_id')->toArray();
            }
        }

        return $roomAvailability;
    }

    public function generateToken(MockTestRegistration $registration)
    {
        // $candidateNumber = 'S' 
        //     . $registration->no_of_mock_test 
        //     . $registration->mock_test_no 
        //     . strtoupper(Str::substr($registration->name, 0, 2));
        $candidateNumber = substr($registration->mobile, -9);
    
        $details = [
            'examDate' => \Carbon\Carbon::parse($registration->mockTestDate->mocktest_date)->format('d-m-Y'),
            'name' => $registration->name,
            'mobile' => $registration->mobile,
            'candidateNumber' => $candidateNumber,
            'lrwTime' => $registration->lrw_time_slot,
            'speakingTime' => $registration->speakingTimeSlot?->time_range ?? '-',
            'room' => $registration->speakingRoom?->mocktest_room ?? '-',
        ];
    

        $details = [
          'examDate' => \Carbon\Carbon::parse($registration->mockTestDate->mocktest_date)->format('d-m-Y'),
            'name' => $registration->name,
            'mobile' => $registration->mobile,
            'candidateNumber' => $candidateNumber,
            'no_of_mock_test' => $registration->no_of_mock_test,
            'current_mock_test' => $registration->mock_test_no,
            'lrwTime' => $registration->lrw_time_slot,
            'speakingTime' => $registration->speakingTimeSlot?->time_range,
            'speakingTimeAnotherDay' => $registration->speaking_time_slot_id_another_day, // True or False
            'room' => $registration->speakingRoom?->mocktest_room
        ];
        return view('mocktestregistrations.token', compact('details'));
    }

    // Send Email Action
    public function sendEmail(MockTestRegistration $registration)
    {
        //dd('hello');
        $candidateEmail = $registration->email; // Assuming the 'email' field exists in the registration model.

        // Prepare details for the email
        $candidateNumber = substr($registration->mobile, -9);
        $details = [
            'examDate' => \Carbon\Carbon::parse($registration->mockTestDate->mocktest_date)->format('d-m-Y'),
            'name' => $registration->name,
            'mobile' => $registration->mobile,
            'candidateNumber' => $candidateNumber,
            'no_of_mock_test' => $registration->no_of_mock_test,
            'current_mock_test' => $registration->mock_test_no,
            'lrwTime' => $registration->lrw_time_slot,
            'speakingTime' => $registration->speakingTimeSlot?->time_range,
            'room' => $registration->speakingRoom?->mocktest_room,
        ];

        // Send the email
        Mail::send('mocktestregistrations.mocktest-token', compact('details'), function ($message) use ($candidateEmail) {
            $message->to($candidateEmail)
                    ->subject(__('IELTS Mock Test Booking Token | STS'))
                    ->from('mocktest@sts.institute', 'STS Institute');
        });

        return redirect()->back()->with('success', __('Email sent successfully to the candidate.'));
    }
}

@extends('layouts.app')

@section('page-title', isset($mockTestRegistration) ? __('Edit IoC Mock Test Registration') : __('Add IoC Mock Test Registration'))
@section('page-heading', isset($mockTestRegistration) ? __('Edit IoC Mock Test Registration') : __('Add IoC Mock Test Registration'))

@section('breadcrumbs')
<li class="breadcrumb-item">
<a href="{{ route('mock_test_registrations.indexioc') }}">@lang('IoC Mock Test Registrations')</a>
</li>
<li class="breadcrumb-item active">
{{ isset($mockTestRegistration) ? __('Edit') : __('Add') }}
</li>
@stop

@section('content')

<div class="card"> <div class="card-body"> 
    <form action="{{ isset($mockTestRegistration) ? route('mock_test_registrations.updateioc', $mockTestRegistration) : route('mock_test_registrations.storeioc') }}" method="POST" id="mock-test-registration-form"> @csrf @if (isset($mockTestRegistration)) @method('PUT') @endif
        <!-- Hidden field to store exam pattern -->
        <input type="hidden" name="exam_pattern" id="exam_pattern" value="{{ $examPattern ?? 'IoP' }}">

        <!-- Mock Test Date -->
        <div class="form-group">
            <label for="mock_test_date_id">@lang('IoC Mock Test Date')</label>
            <select name="mock_test_date_id" id="mock_test_date_id" class="form-control" required>
                <option value="">@lang('Select IoC Mock Test Date')</option>
                @foreach ($dates as $date)
                    <option value="{{ $date->id }}" 
                        {{ (isset($mockTestRegistration) && $mockTestRegistration->mock_test_date_id == $date->id) || old('mock_test_date_id') == $date->id ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::parse($date->mocktest_date)->format('d-m-Y') }} ({{ $date->exam_pattern }})
                    </option>
                @endforeach
            </select>
            @if(count($dates) === 0)
                <div class="alert alert-warning mt-2">
                    @lang('No available dates for the selected exam pattern.')
                </div>
            @endif
        </div>

        <!-- Test Venue -->
        <div class="form-group">
            <label for="test_venue">@lang('Test Venue')</label>
            <select name="test_venue" id="test_venue" class="form-control" required>
                <option value="">@lang('Select Test Venue')</option>
                <option value="At the Venue" {{ old('test_venue', $mockTestRegistration->test_venue ?? '') == 'At the Venue' ? 'selected' : '' }}>@lang('At the Venue')</option>
                <option value="Online Platform" {{ old('test_venue', $mockTestRegistration->test_venue ?? '') == 'Online Platform' ? 'selected' : '' }}>@lang('Online Platform')</option>
            </select>
        </div>

        <!-- Name -->
        <div class="form-group">
            <label for="name">@lang('Name')</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="{{ old('name', $mockTestRegistration->name ?? '') }}" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">@lang('Email')</label>
            <input type="email" name="email" id="email" class="form-control" 
                   value="{{ old('email', $mockTestRegistration->email ?? '') }}" required>
        </div>

        <!-- Mobile -->
        <div class="form-group">
            <label for="mobile">@lang('Mobile')</label>
            <input type="text" name="mobile" id="mobile" class="form-control" 
                   value="{{ old('mobile', $mockTestRegistration->mobile ?? '') }}" required>
        </div>

        <!-- Exam Status -->
        <div class="form-group">
            <label for="exam_status_id">@lang('Candidate Mock Test Purchase Status')</label>
            <select name="exam_status_id" id="exam_status_id" class="form-control" required>
                <option value="">@lang('Select Candidate Status')</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->id }}" 
                        {{ old('exam_status_id', $mockTestRegistration->exam_status_id ?? '') == $status->id ? 'selected' : '' }}>
                        {{ $status->mock_status }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Number of Mock Tests -->
        <div class="form-group">
            <label for="no_of_mock_test">@lang('No. of Mock Tests')</label>
            <input type="number" name="no_of_mock_test" id="no_of_mock_test" class="form-control" 
                   value="{{ old('no_of_mock_test', $mockTestRegistration->no_of_mock_test ?? '') }}" required min="1">
        </div>

        <!-- Mock Test Number -->
        <div class="form-group">
            <label for="mock_test_no">@lang('Current Mock Test No')</label>
            <input type="number" name="mock_test_no" id="mock_test_no" class="form-control" 
                   value="{{ old('mock_test_no', $mockTestRegistration->mock_test_no ?? '') }}" required min="1">
        </div>

        <!-- Booking Category -->
        <div class="form-group">
            <label for="booking_category">@lang('Booking Category')</label>
            <select name="booking_category" id="booking_category" class="form-control" required>
                <option value="">@lang('Select Booking Category')</option>
                <option value="Student" {{ old('booking_category', $mockTestRegistration->booking_category ?? '') == 'Student' ? 'selected' : '' }}>@lang('Student')</option>
                <option value="Outsider" {{ old('booking_category', $mockTestRegistration->booking_category ?? '') == 'Outsider' ? 'selected' : '' }}>@lang('Outsider')</option>
            </select>
        </div>

        <!-- Exam Type -->
        <div class="form-group">
            <label for="exam_type">@lang('Exam Type')</label>
            <select name="exam_type" id="exam_type" class="form-control" required>
                <option value="">@lang('Select Exam Type')</option>
                <option value="General" {{ old('exam_type', $mockTestRegistration->exam_type ?? '') == 'General' ? 'selected' : '' }}>@lang('General')</option>
                <option value="Academic" {{ old('exam_type', $mockTestRegistration->exam_type ?? '') == 'Academic' ? 'selected' : '' }}>@lang('Academic')</option>
            </select>
        </div>

        <!-- Invoice No -->
        <div class="form-group">
            <label for="invoice_no">@lang('Student ID/ Invoice No.')</label>
            <!-- <p><small>(If the candidate has no invoice number, leave it blank or use "N/A")</small></p> -->
            <input type="text" name="invoice_no" id="invoice_no" class="form-control" 
                value="{{ old('invoice_no', $mockTestRegistration->invoice_no ?? '') }}">
        </div>

        <!-- LRW Time Slot -->
        <div class="form-group">
            <label for="lrw_time_slot">@lang('LRW Time Slot')</label>
            <select name="lrw_time_slot" id="lrw_time_slot" class="form-control" required>
                <option value="">@lang('Select LRW Time Slot')</option>
                @foreach ($lrwTimeSlots as $slot)
                    <option value="{{ $slot->time_range }}" 
                        {{ (isset($mockTestRegistration) && $mockTestRegistration->lrw_time_slot == $slot->time_range) || old('lrw_time_slot') == $slot->time_range ? 'selected' : '' }}>
                        {{ $slot->time_range }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Speaking Time Slot -->
        <div class="form-group">
            <label>@lang('Speaking Time Slot')</label>
            <div class="form-check">
                <input type="radio" name="speaking_day" id="speaking_same_day" value="same_day" class="form-check-input" 
                    {{ old('speaking_time_slot_id_another_day', $mockTestRegistration->speaking_time_slot_id_another_day ?? 0) == 0 ? 'checked' : '' }}>
                <label class="form-check-label" for 'speaking_same_day'>@lang('Same Day')</label>
            </div>
            <!-- <div class="form-check">
                <input type="radio" name="speaking_day" id="speaking_another_day" value="another_day" class="form-check-input" 
                    {{ old('speaking_time_slot_id_another_day', $mockTestRegistration->speaking_time_slot_id_another_day ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="speaking_another_day">@lang('Another Day')</label>
            </div> -->
            <select name="speaking_time_slot_id" id="speaking_time_slot" class="form-control mt-2" 
                {{ old('speaking_time_slot_id_another_day', $mockTestRegistration->speaking_time_slot_id_another_day ?? 0) == 1 ? 'disabled' : '' }}>
                <option value="">@lang('Select Speaking Time Slot')</option>
                @foreach ($speakingTimeSlots as $slot)
                    <option value="{{ $slot->id }}" 
                        {{ (old('speaking_time_slot_id', $mockTestRegistration->speaking_time_slot_id ?? '') == $slot->id) ? 'selected' : '' }}>
                        {{ $slot->time_range }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="speaking_time_slot_id_another_day" id="speaking_time_slot_id_another_day" 
                value="{{ old('speaking_time_slot_id_another_day', $mockTestRegistration->speaking_time_slot_id_another_day ?? 0) }}">
        </div>

        <!-- Speaking Room -->
        <div class="form-group">
            <label for="speaking_room_id">@lang('Speaking Room')</label>
            <select name="speaking_room_id" id="speaking_room_id" class="form-control" 
                {{ old('speaking_time_slot_id_another_day', $mockTestRegistration->speaking_time_slot_id_another_day ?? 0) == 1 ? 'disabled' : '' }}>
                <option value="">@lang('Select Speaking Room')</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" 
                        {{ (isset($mockTestRegistration) && $mockTestRegistration->speaking_room_id == $room->id) || old('speaking_room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->mocktest_room }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div class="form-group">
            <label for="status">@lang('Status')</label>
            <select name="status" id="status" class="form-control" required>
                <option value="On" {{ (isset($mockTestRegistration) && $mockTestRegistration->status == 'On') || old('status') == 'On' ? 'selected' : '' }}>@lang('On')</option>
                <option value="Off" {{ (isset($mockTestRegistration) && $mockTestRegistration->status == 'Off') || old('status') == 'Off' ? 'selected' : '' }}>@lang('Off')</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">
            {{ isset($mockTestRegistration) ? __('Update') : __('Create') }}
        </button>
        
        <!-- Cancel Button -->
        <a href="{{ route('mock_test_registrations.indexioc') }}" class="btn btn-secondary">
            @lang('Cancel')
        </a>
    </form>
</div>
</div>
@stop

@section('scripts')
@if (isset($mockTestRegistration))
{!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestRegistration\UpdateMockTestRegistrationRequest', '#mock-test-registration-form') !!}
@else
{!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestRegistration\CreateMockTestRegistrationRequest', '#mock-test-registration-form') !!}
@endif

text
<script>
$(document).ready(function () {
    const testVenueDropdown = $('#test_venue');
    const lrwTimeSlotDropdown = $('#lrw_time_slot');
    const speakingTimeSlotDropdown = $('#speaking_time_slot');
    const speakingRoomDropdown = $('#speaking_room_id');
    const speakingAnotherDayInput = $('#speaking_time_slot_id_another_day');
    const roomAvailability = @json($roomAvailability); // Pass availability data from the controller

    // Function to toggle venue-related fields
    function toggleVenueFields() {
        const isOnlinePlatform = testVenueDropdown.val() === 'Online Platform';
        
        // Disable/enable venue-related fields
        lrwTimeSlotDropdown.prop('disabled', isOnlinePlatform);
        $('input[name="speaking_day"]').prop('disabled', isOnlinePlatform);
        speakingTimeSlotDropdown.prop('disabled', isOnlinePlatform || speakingAnotherDayInput.val() == 1);
        speakingRoomDropdown.prop('disabled', isOnlinePlatform || speakingAnotherDayInput.val() == 1);

        // If online platform, clear venue-related fields
        if (isOnlinePlatform) {
            lrwTimeSlotDropdown.val('');
            speakingTimeSlotDropdown.val('');
            speakingRoomDropdown.val('');
            speakingAnotherDayInput.val(0);
            $('input[name="speaking_day"][value="same_day"]').prop('checked', true);
        }
    }

    // Populate Speaking Slots based on LRW time slot
    function populateSpeakingSlots(lrwTimeSlot) {
        let speakingSlots = [];

        if (lrwTimeSlot === '11:00AM-02:00PM') {
            speakingSlots = @json($speakingTimeSlots->where('slot_key', 'IoC Speaking Slot Afternoon')->pluck('id', 'time_range'));
        } else if (lrwTimeSlot === '02:00PM-05:00PM') {
            speakingSlots = @json($speakingTimeSlots->where('slot_key', 'IoC Speaking Slot Morning')->pluck('id', 'time_range'));
        }

        speakingTimeSlotDropdown.empty().append('<option value="">@lang("Select Speaking Time Slot")</option>');
        $.each(speakingSlots, function (timeRange, id) {
            speakingTimeSlotDropdown.append(`<option value="${id}">${timeRange}</option>`);
        });
    }

    // Check Room Availability and Update Options
    function updateRoomAvailability(date, timeSlot) {
        speakingRoomDropdown.find('option').each(function () {
            const roomId = $(this).val();
            const isBooked = roomAvailability[date]?.[timeSlot]?.includes(parseInt(roomId));
            
            // Disable or Enable the Room Option
            if (isBooked) {
                $(this).prop('disabled', true).css('background-color', 'red').text($(this).text() + ' (@lang("Booked"))');
            } else {
                $(this).prop('disabled', false).css('background-color', 'green').text($(this).text().replace(' (@lang("Booked"))', ''));
            }
        });

        // If all rooms are booked, alert the user
        if (speakingRoomDropdown.find('option:not(:disabled)').length === 0) {
            alert("@lang('No rooms available for this time slot. Please choose another time slot.')");
        }
    }

    // Test Venue Change
    testVenueDropdown.on('change', function () {
        toggleVenueFields();
    });

    // LRW Time Slot Change
    lrwTimeSlotDropdown.on('change', function () {
        if ($('#speaking_same_day').is(':checked') && testVenueDropdown.val() === 'At the Venue') {
            populateSpeakingSlots($(this).val());
        }
    });

    // Speaking Day Selection Change
    $('input[name="speaking_day"]').on('change', function () {
        const selectedDay = $(this).val();
        const isOnlinePlatform = testVenueDropdown.val() === 'Online Platform';
        
        if (!isOnlinePlatform) {
            if (selectedDay === 'same_day') {
                lrwTimeSlotDropdown.trigger('change');
                speakingTimeSlotDropdown.prop('disabled', false);
                speakingRoomDropdown.prop('disabled', false);
                speakingAnotherDayInput.val(0);
            } else {
                speakingTimeSlotDropdown.empty().append('<option value="0">@lang("Another Day")</option>').prop('disabled', true);
                speakingRoomDropdown.prop('disabled', true).val('');  // Reset Speaking Room to blank
                speakingAnotherDayInput.val(1);
            }
        }
    });

    // Speaking Time Slot Change
    speakingTimeSlotDropdown.on('change', function () {
        const selectedDate = $('#mock_test_date_id').val();
        const selectedTimeSlot = $(this).val();
        if (selectedDate && selectedTimeSlot && testVenueDropdown.val() === 'At the Venue') {
            updateRoomAvailability(selectedDate, selectedTimeSlot);
        }
    });

    // Initial setup
    toggleVenueFields();
    
    // Initial Check (if editing)
    if ($('#speaking_another_day').is(':checked')) {
        $('input[name="speaking_day"]:checked').trigger('change');
    }
    if (speakingTimeSlotDropdown.val() && testVenueDropdown.val() === 'At the Venue') {
        speakingTimeSlotDropdown.trigger('change');
    }
});
</script>
@stop
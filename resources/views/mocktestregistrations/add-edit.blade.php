@extends('layouts.app')

@section('page-title', isset($mockTestRegistration) ? __('Edit Mock Test Registration') : __('Add Mock Test Registration'))
@section('page-heading', isset($mockTestRegistration) ? __('Edit Mock Test Registration') : __('Add Mock Test Registration'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('mock_test_registrations.index') }}">@lang('Mock Test Registrations')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ isset($mockTestRegistration) ? __('Edit') : __('Add') }}
    </li>
@stop

@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ isset($mockTestRegistration) ? route('mock_test_registrations.update', $mockTestRegistration) : route('mock_test_registrations.store') }}" 
              method="POST" 
              id="mock-test-registration-form">
            @csrf
            @if (isset($mockTestRegistration))
                @method('PUT')
            @endif

            <!-- Mock Test Date -->
            <div class="form-group">
                <label for="mock_test_date_id">@lang('Mock Test Date')</label>
                <select name="mock_test_date_id" id="mock_test_date_id" class="form-control">
                    <option value="">@lang('Select Mock Test Date')</option>
                    @foreach ($dates as $date)
                        <option value="{{ $date->id }}" 
                            {{ isset($mockTestRegistration) && $mockTestRegistration->mock_test_date_id == $date->id ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($date->mocktest_date)->format('d-m-Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Name -->
            <div class="form-group">
                <label for="name">@lang('Name')</label>
                <input type="text" name="name" id="name" class="form-control" 
                       value="{{ old('name', $mockTestRegistration->name ?? '') }}">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">@lang('Email')</label>
                <input type="email" name="email" id="email" class="form-control" 
                       value="{{ old('email', $mockTestRegistration->email ?? '') }}">
            </div>

            <!-- Mobile -->
            <div class="form-group">
                <label for="mobile">@lang('Mobile')</label>
                <input type="text" name="mobile" id="mobile" class="form-control" 
                       value="{{ old('mobile', $mockTestRegistration->mobile ?? '') }}">
            </div>

            <!-- Exam Status -->
            <select name="exam_status_id" id="exam_status_id" class="form-control">
                <option value="">@lang('Select Candidate Status')</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->id }}" 
                        {{ old('exam_status_id', $mockTestRegistration->exam_status_id ?? '') == $status->id ? 'selected' : '' }}>
                        {{ $status->mock_status }}
                    </option>
                @endforeach
            </select>


            <!-- Number of Mock Tests -->
            <div class="form-group">
                <label for="no_of_mock_test">@lang('No. of Mock Tests')</label>
                <input type="number" name="no_of_mock_test" id="no_of_mock_test" class="form-control" 
                       value="{{ old('no_of_mock_test', $mockTestRegistration->no_of_mock_test ?? '') }}">
            </div>

            <!-- Mock Test Number -->
            <div class="form-group">
                <label for="mock_test_no">@lang('Current Mock Test No')</label>
                <input type="number" name="mock_test_no" id="mock_test_no" class="form-control" 
                       value="{{ old('mock_test_no', $mockTestRegistration->mock_test_no ?? '') }}">
            </div>

            <!-- LRW Time Slot -->
            <div class="form-group">
                <label for="lrw_time_slot">@lang('LRW Time Slot')</label>
                <select name="lrw_time_slot" id="lrw_time_slot" class="form-control">
                    <option value="">@lang('Select LRW Time Slot')</option>
                    @foreach ($lrwTimeSlots as $slot)
                        <option value="{{ $slot->time_range }}" 
                            {{ isset($mockTestRegistration) && $mockTestRegistration->lrw_time_slot == $slot->time_range ? 'selected' : '' }}>
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
                    <label class="form-check-label" for="speaking_same_day">@lang('Same Day')</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="speaking_day" id="speaking_another_day" value="another_day" class="form-check-input" 
                        {{ old('speaking_time_slot_id_another_day', $mockTestRegistration->speaking_time_slot_id_another_day ?? 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="speaking_another_day">@lang('Another Day')</label>
                </div>
                <select name="speaking_time_slot_id" id="speaking_time_slot" class="form-control mt-2" 
                    {{ old('speaking_time_slot_id_another_day', $mockTestRegistration->speaking_time_slot_id_another_day ?? 0) == 1 ? 'disabled' : '' }}>
                    <option value="">@lang('Select Speaking Time Slot')</option>
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
                            {{ isset($mockTestRegistration) && $mockTestRegistration->speaking_room_id == $room->id ? 'selected' : '' }}>
                            {{ $room->mocktest_room }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">@lang('Status')</label>
                <select name="status" id="status" class="form-control">
                    <option value="On" {{ isset($mockTestRegistration) && $mockTestRegistration->status == 'On' ? 'selected' : '' }}>@lang('On')</option>
                    <option value="Off" {{ isset($mockTestRegistration) && $mockTestRegistration->status == 'Off' ? 'selected' : '' }}>@lang('Off')</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                {{ isset($mockTestRegistration) ? __('Update') : __('Create') }}
            </button>
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

    <script>
    $(document).ready(function () {
        const speakingTimeSlotDropdown = $('#speaking_time_slot');
        const speakingRoomDropdown = $('#speaking_room_id');
        const speakingAnotherDayInput = $('#speaking_time_slot_id_another_day');
        const roomAvailability = @json($roomAvailability); // Pass availability data from the controller

        // Populate Speaking Slots based on LRW time slot
        function populateSpeakingSlots(lrwTimeSlot) {
            let speakingSlots = [];

            if (lrwTimeSlot === '10:30AM-02:30PM') {
                speakingSlots = @json($speakingTimeSlots->where('slot_key', 'Speaking Slot Afternoon')->pluck('id', 'time_range'));
            } else if (lrwTimeSlot === '3:30PM-6:30PM') {
                speakingSlots = @json($speakingTimeSlots->where('slot_key', 'Speaking Slot Morning')->pluck('id', 'time_range'));
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

        // LRW Time Slot Change
        $('#lrw_time_slot').on('change', function () {
            if ($('#speaking_same_day').is(':checked')) {
                populateSpeakingSlots($(this).val());
            }
        });

        // Speaking Day Selection Change
        $('input[name="speaking_day"]').on('change', function () {
            const selectedDay = $(this).val();
            if (selectedDay === 'same_day') {
                $('#lrw_time_slot').trigger('change');
                speakingTimeSlotDropdown.prop('disabled', false);
                speakingRoomDropdown.prop('disabled', false);
                speakingAnotherDayInput.val(0);
            } else {
                speakingTimeSlotDropdown.empty().append('<option value="0">@lang("Another Day")</option>').prop('disabled', true);
                speakingRoomDropdown.prop('disabled', true);
                speakingAnotherDayInput.val(1);
            }
        });

        // Speaking Time Slot Change
        speakingTimeSlotDropdown.on('change', function () {
            const selectedDate = $('#mock_test_date_id').val();
            const selectedTimeSlot = $(this).val();
            if (selectedDate && selectedTimeSlot) {
                updateRoomAvailability(selectedDate, selectedTimeSlot);
            }
        });

        // Initial Check (if editing)
        if ($('#speaking_another_day').is(':checked')) {
            $('input[name="speaking_day"]:checked').trigger('change');
        }
        if (speakingTimeSlotDropdown.val()) {
            speakingTimeSlotDropdown.trigger('change');
        }
    });
</script>

@stop

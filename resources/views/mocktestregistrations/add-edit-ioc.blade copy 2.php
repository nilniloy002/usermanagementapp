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

<div class="card"> 
    <div class="card-body"> 
        <form action="{{ isset($mockTestRegistration) ? route('mock_test_registrations.updateioc', $mockTestRegistration) : route('mock_test_registrations.storeioc') }}" method="POST" id="mock-test-registration-form"> 
            @csrf 
            @if (isset($mockTestRegistration)) 
                @method('PUT') 
                <input type="hidden" id="current_registration_id" value="{{ $mockTestRegistration->id }}">
            @endif

            <!-- Hidden field to store exam pattern -->
            <input type="hidden" name="exam_pattern" id="exam_pattern" value="{{ $examPattern ?? 'IoP' }}">

            <!-- Student Search Section -->
            <div class="card border-info mb-4">
                <div class="card-header bg-info text-white py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-search mr-2"></i>Search Existing Student
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="studentSearch" class="font-weight-bold">
                            Search by Application Number or Student ID
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="studentSearch" 
                                   placeholder="Type application number or student ID...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="searchStudentBtn">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                        <div id="searchResults" class="mt-2" style="display: none;">
                            <div class="list-group" id="studentResultsList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Information Section -->
            <div class="card border-primary mb-4">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0">
                        <i class="fas fa-user-graduate mr-2"></i>Student Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">@lang('Name')</label>
                                <input type="text" name="name" id="name" class="form-control" 
                                       value="{{ old('name', $mockTestRegistration->name ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">@lang('Email') <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" 
                                       value="{{ old('email', $mockTestRegistration->email ?? '') }}" required>
                                <!-- Duplicate warning will appear here -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobile">@lang('Mobile')</label>
                                <input type="text" name="mobile" id="mobile" class="form-control" 
                                       value="{{ old('mobile', $mockTestRegistration->mobile ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_id_display">@lang('Student ID')</label>
                                <input type="text" class="form-control" id="student_id_display" 
                                       readonly placeholder="Will be auto-filled for existing students">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mock Test Date -->
            <div class="form-group">
                <label for="mock_test_date_id">@lang('IoC Mock Test Date') <span class="text-danger">*</span></label>
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
                    <label class="form-check-label" for="speaking_same_day">@lang('Same Day')</label>
                </div>
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

<script>
$(document).ready(function () {
    // Get CSRF token
    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr('content');
    }

    // Variables to track selected student
    let selectedStudentEmail = null;

    // Student search functionality
    $('#searchStudentBtn').click(function() {
        const query = $('#studentSearch').val().trim();
        if (query.length < 2) {
            alert('Please enter at least 2 characters to search');
            return;
        }

        $.ajax({
            url: '{{ route("student-admissions.search-student") }}',
            method: 'GET',
            data: { query: query },
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            success: function(response) {
                const resultsList = $('#studentResultsList');
                resultsList.empty();
                
                if (response.length > 0) {
                    response.forEach(function(student) {
                        const studentId = student.id || '';
                        const studentName = student.name || '';
                        const studentEmail = student.email || '';
                        const studentMobile = student.mobile || '';
                        const studentStudentId = student.student_id || '';
                        const applicationNumber = student.application_number || '';
                        
                        const item = `
                            <a href="#" class="list-group-item list-group-item-action student-item" 
                               data-id="${studentId}" 
                               data-name="${studentName}"
                               data-email="${studentEmail}"
                               data-mobile="${studentMobile}"
                               data-student-id="${studentStudentId}"
                               data-application-number="${applicationNumber}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">${studentName}</h6>
                                    <small class="text-success">${studentStudentId || 'No ID'}</small>
                                </div>
                                <p class="mb-1">
                                    <small>Mobile: ${studentMobile}</small> | 
                                    <small>Email: ${studentEmail || 'N/A'}</small>
                                </p>
                                <small>App No: ${applicationNumber}</small>
                            </a>
                        `;
                        resultsList.append(item);
                    });
                    $('#searchResults').show();
                } else {
                    resultsList.append('<div class="list-group-item text-center">No students found</div>');
                    $('#searchResults').show();
                }
            },
            error: function() {
                alert('Error searching students. Please try again.');
            }
        });
    });

    // Select student from search results
    $(document).on('click', '.student-item', function(e) {
        e.preventDefault();
        const studentName = $(this).data('name');
        const studentEmail = $(this).data('email');
        const studentMobile = $(this).data('mobile');
        const studentId = $(this).data('student-id');
        const applicationNumber = $(this).data('application-number');
        
        // Store selected student email for duplicate check
        selectedStudentEmail = studentEmail;
        
        // Fill form fields
        $('#name').val(studentName);
        $('#email').val(studentEmail);
        $('#mobile').val(studentMobile);
        $('#student_id_display').val(studentId);
        $('#invoice_no').val(studentId || applicationNumber);
        
        // Close search results
        $('#searchResults').hide();
        $('#studentSearch').val('');
        
        // Check for duplicate registration if date is already selected
        const selectedDate = $('#mock_test_date_id').val();
        if (selectedDate && studentEmail) {
            checkDuplicateRegistration(selectedDate, studentEmail);
        }
        
        // Show success notification
        alert(`Student "${studentName}" selected successfully!`);
    });

    // Function to check duplicate registration
    function checkDuplicateRegistration(dateId, email) {
        // If email is empty, skip check
        if (!email) {
            clearDuplicateWarning();
            return;
        }

        // Prepare data for AJAX request
        const requestData = {
            mock_test_date_id: dateId,
            email: email
        };

        // If editing, include the current registration ID to exclude it from check
        const currentRegistrationId = $('#current_registration_id').val();
        if (currentRegistrationId) {
            requestData.registration_id = currentRegistrationId;
        }

        $.ajax({
            url: '{{ route("mock_test_registrations.check-duplicate") }}',
            method: 'GET',
            data: requestData,
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.duplicate) {
                    showDuplicateWarning(`This email (${email}) is already registered for the selected Mock Test Date.`);
                } else {
                    clearDuplicateWarning();
                }
            },
            error: function() {
                console.log('Error checking duplicate registration');
                clearDuplicateWarning();
            }
        });
    }

    // Function to show duplicate warning
    function showDuplicateWarning(message) {
        // Remove any existing warning
        $('#duplicate-warning').remove();
        
        // Create warning message
        const warningHtml = `
            <div id="duplicate-warning" class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        // Insert after email field
        $('#email').after(warningHtml);
        
        // Highlight the email field
        $('#email').addClass('is-invalid');
        
        // Update email form group
        const emailGroup = $('#email').closest('.form-group');
        emailGroup.find('.invalid-feedback').remove();
        emailGroup.append(`
            <div class="invalid-feedback d-block">
                <i class="fas fa-exclamation-circle mr-1"></i>
                This email is already registered for the selected Mock Test Date.
            </div>
        `);
    }

    // Function to clear duplicate warning
    function clearDuplicateWarning() {
        $('#duplicate-warning').remove();
        $('#email').removeClass('is-invalid');
        $('#email').closest('.form-group').find('.invalid-feedback').remove();
    }

    // Check duplicate registration when date changes
    $('#mock_test_date_id').on('change', function() {
        const selectedDate = $(this).val();
        const studentEmail = $('#email').val();
        
        if (selectedDate && studentEmail) {
            checkDuplicateRegistration(selectedDate, studentEmail);
        } else {
            clearDuplicateWarning();
        }
    });

    // Check duplicate registration when email is manually entered
    $('#email').on('blur', function() {
        const selectedDate = $('#mock_test_date_id').val();
        const studentEmail = $(this).val();
        
        if (selectedDate && studentEmail) {
            checkDuplicateRegistration(selectedDate, studentEmail);
        } else {
            clearDuplicateWarning();
        }
    });

    // Clear warning when email field is focused
    $('#email').on('focus', function() {
        $(this).removeClass('is-invalid');
        $(this).closest('.form-group').find('.invalid-feedback').remove();
    });

    // Check duplicate registration when form is about to be submitted
    $('#mock-test-registration-form').on('submit', function(e) {
        const selectedDate = $('#mock_test_date_id').val();
        const studentEmail = $('#email').val();
        
        if (selectedDate && studentEmail) {
            // Prevent immediate submission
            e.preventDefault();
            
            // Prepare data for AJAX request
            const requestData = {
                mock_test_date_id: selectedDate,
                email: studentEmail
            };

            // If editing, include the current registration ID to exclude it from check
            const currentRegistrationId = $('#current_registration_id').val();
            if (currentRegistrationId) {
                requestData.registration_id = currentRegistrationId;
            }
            
            // Check for duplicates
            $.ajax({
                url: '{{ route("mock_test_registrations.check-duplicate") }}',
                method: 'GET',
                data: requestData,
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.duplicate) {
                        // Show final warning and ask for confirmation
                        if (!confirm(`WARNING: This email (${studentEmail}) is already registered for the selected Mock Test Date.\n\nDo you want to proceed anyway?`)) {
                            // User chose not to proceed
                            $('#email').focus();
                            return;
                        }
                    }
                    
                    // If no duplicate or user confirmed, submit the form
                    $('#mock-test-registration-form').off('submit').submit();
                },
                error: function() {
                    // If error checking, proceed with submission
                    $('#mock-test-registration-form').off('submit').submit();
                }
            });
        }
    });

    const testVenueDropdown = $('#test_venue');
    const lrwTimeSlotDropdown = $('#lrw_time_slot');
    const speakingTimeSlotDropdown = $('#speaking_time_slot');
    const speakingRoomDropdown = $('#speaking_room_id');
    const speakingAnotherDayInput = $('#speaking_time_slot_id_another_day');
    const roomAvailability = @json($roomAvailability);

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
                $(this).prop('disabled', true).css('background-color', '#f8d7da').text($(this).text() + ' (@lang("Booked"))');
            } else {
                $(this).prop('disabled', false).css('background-color', '').text($(this).text().replace(' (@lang("Booked"))', ''));
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

<style>
.student-item:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}
#searchResults {
    max-height: 300px;
    overflow-y: auto;
}
.is-invalid {
    border-color: #dc3545 !important;
}
</style>
@stop
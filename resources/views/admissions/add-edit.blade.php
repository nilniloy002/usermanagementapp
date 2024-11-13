@extends('layouts.app')

@section('page-title', __('Admissions'))
@section('page-heading', $edit ? __('Edit Admission') : __('Create New Admission'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('admissions.index') }}">@lang('Admissions')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    <!-- @if ($edit)
        {!! Form::open(['route' => ['admissions.update', $admission], 'method' => 'PUT', 'id' => 'admission-form']) !!}
    @else
        {!! Form::open(['route' => 'admissions.store', 'id' => 'admission-form']) !!}
    @endif -->

    @if ($edit)
    {!! Form::open(['route' => ['admissions.update', $admission], 'method' => 'PUT', 'id' => 'admission-form', 'enctype' => 'multipart/form-data']) !!}
    @else
        {!! Form::open(['route' => 'admissions.store', 'id' => 'admission-form', 'enctype' => 'multipart/form-data']) !!}
    @endif


    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Admission Details')
                    </h5>
                    <p class="text-muted">
                        @lang('General admission information.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="admission_date">@lang('Admission Date')</label>
                        <input type="date"
                            class="form-control input-solid"
                            id="admission_date"
                            name="admission_date"
                            value="{{ $edit ? $admission->admission_date : now()->format('Y-m-d') }}">

                    </div>
                    <div class="form-group">
                        <label for="photo">@lang('Photo')</label>
                        <input type="file" class="form-control-file" id="photo" name="photo">
                        @if($edit && $admission->photo)
                            <div class="mt-2">
                                <img src="{{ asset($admission->photo) }}" alt="Student Photo" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="student_name">@lang('Student Name')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="student_name"
                               name="student_name"
                               placeholder="@lang('Student Name')"
                               value="{{ $edit ? $admission->student_name : old('student_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">@lang('Phone Number')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="phone_number"
                               name="phone_number"
                               placeholder="@lang('Phone Number')"
                               value="{{ $edit ? $admission->phone_number : old('phone_number') }}">
                    </div>
                    <div class="form-group">
                        <label for="guardian_phone_number">@lang('Guardian Phone Number')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="guardian_phone_number"
                               name="guardian_phone_number"
                               placeholder="@lang('Guardian Phone Number')"
                               value="{{ $edit ? $admission->guardian_phone_number : old('guardian_phone_number') }}">
                    </div>
                    <div class="form-group">
                        <label for="course_id">@lang('Course Name')</label>
                        {!! Form::select('course_id', $courses->pluck('course_name', 'id'), $edit ? $admission->course_id : old('course_id'), ['class' => 'form-control', 'id' => 'course_id']) !!}
                    </div>
                    <div class="form-group">
                        <label for="batch_code">@lang('Batch Code')</label>
                        {!! Form::select('batch_code', $batches->pluck('batch_code', 'batch_code'), $edit ? $admission->batch_code : old('batch_code'), ['class' => 'form-control', 'id' => 'batch_code']) !!}
                    </div>
                    <div class="form-group">
                        <label for="course_fee">@lang('Course Fee')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="course_fee"
                               name="course_fee"
                               readonly
                               value="{{ $edit ? $admission->course->course_fee : 0 }}">
                    </div>
                    <div class="form-group">
                        <label for="admission_fee">@lang('Admission Fee')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="admission_fee"
                               name="admission_fee"
                               readonly
                               value="{{ $edit ? $admission->course->admission_fee : 0 }}">
                    </div>
                    <div class="form-group">
                        <label for="discount_amount">@lang('Discount Amount')</label>
                        <input type="text"
                            class="form-control input-solid"
                            id="discount_amount"
                            name="discount_amount"
                            value="{{ $edit && $admission->payments->isNotEmpty() ? $admission->payments->first()->discount_amount : old('discount_amount') }}">
                    </div>

                    <div class="form-group">
                        <label for="paid_amount">@lang('Paid Amount')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="paid_amount"
                               name="paid_amount"
                               value="{{ $edit && $admission->payments->isNotEmpty() ? $admission->payments->first()->paid_amount : old('paid_amount') }}">
                    </div>
                    <div class="form-group">
                        <label for="payment_method">@lang('Payment Method')</label>
                        {!! Form::select('payment_method', ['cash' => 'Cash', 'bKash' => 'bKash', 'Nagad' => 'Nagad', 'Bank' => 'Bank'], $edit ? $admission->payment_method : old('payment_method'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="payment_process">@lang('Payment Process')</label>
                        {!! Form::select('payment_process', ['Partial Paid' => 'Partial Paid', 'Full Paid' => 'Full Paid', '1st Installment' => '1st Installment', '2nd Installment' => '2nd Installment', '3rd Installment' => '3rd Installment', '4th Installment' => '4th Installment', 'Admission Fee' => 'Admission Fee'], $edit ? $admission->payment_process : old('payment_process'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="due_amount">@lang('Due Amount')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="due_amount"
                               name="due_amount"
                               readonly
                               value="{{ $edit ? $admission->course->admission_fee - $admission->paid_amount : 0 }}">
                    </div>
                    <div class="form-group">
                        <label for="next_due_date">@lang('Next Due Date')</label>
                        <input type="date"
                               class="form-control input-solid"
                               id="next_due_date"
                               name="next_due_date"
                               value="{{ $edit ? $admission->next_due_date : now()->format('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="remarks">@lang('Remarks')</label>
                        <textarea class="form-control input-solid" id="remarks" name="remarks" rows="4">{{ $edit ? $admission->remarks : old('remarks') }}</textarea>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Admission' : 'Create Admission') }}
    </button>

    {!! Form::close() !!}

@endsection

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Admission\UpdateAdmissionRequest', '#admission-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Admission\CreateAdmissionRequest', '#admission-form') !!}
    @endif

    <script>
    // Initialize values when the page loads
    updateCourseDetails();

    // Update course_fee, admission_fee, and due_amount based on the selected course
    $('#course_id').on('change', function () {
        updateCourseDetails();
    });

    // Update due_amount based on paid_amount and admission_fee
    $('#paid_amount').on('input', function () {
        updateDueAmount();
    });

    function updateCourseDetails() {
    console.log('Updating course details...');
    var selectedCourseId = $('#course_id').val();

    // Check if selectedCourseId is not empty
    if (selectedCourseId) {
        $.ajax({
            url: '/api/courses/' + selectedCourseId,
            type: 'GET',
            success: function (data) {
                console.log('Success! Data:', data);
                $('#course_fee').val(data.course_fee);
                $('#admission_fee').val(data.admission_fee);
                updateDueAmount();
            },
            error: function (error) {
                console.error('Error fetching course details:', error);
            }
        });
    }
}

    function updateDueAmount() {
    var paidAmount = parseFloat($('#paid_amount').val()) || 0;
    var courseFee = parseFloat($('#course_fee').val()) || 0;
    var admissionFee = parseFloat($('#admission_fee').val()) || 0;
    var discount = parseFloat($('#discount_amount').val()) || 0;

    var dueAmount = (courseFee + admissionFee) - (paidAmount + discount);
    $('#due_amount').val(dueAmount.toFixed(2));
}

    // Add this within the script tag
    $('#photo').on('change', function (e) {
        var fileInput = e.target;
        var files = fileInput.files;

        if (files && files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#photo-preview').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(files[0]);
        }
    });

// Initialize values when the page loads
updateCourseDetails();

// Update course_fee, admission_fee, and due_amount based on the selected course
$('#course_id').on('change', function () {
    updateCourseDetails();
});

// Update due_amount based on paid_amount and admission_fee
$('#paid_amount').on('input', function () {
    updateDueAmount();
});

// Update batch codes based on the selected course_id
$('#course_id').on('change', function () {
            updateBatchCodes();
        });

        // Initialize batch codes when the page loads
        updateBatchCodes();

        function updateBatchCodes() {
            var selectedCourseId = $('#course_id').val();

            // Check if selectedCourseId is not empty
            if (selectedCourseId) {
                $.ajax({
                    url: '/api/batches/course/' + selectedCourseId, // Updated API endpoint
                    type: 'GET',
                    success: function (data) {
                        // Assuming your batch codes are returned in the 'batch_codes' field
                        var batchCodes = data.batch_codes;

                        // Update the batch_code select options
                        $('#batch_code').empty();
                        $.each(batchCodes, function (index, value) {
                            $('#batch_code').append($('<option>').text(value).val(value));
                        });

                        // Optionally, you can select the first batch code automatically
                        // $('#batch_code').val(batchCodes[0]);
                    },
                    error: function () {
                        console.error('Error fetching batch codes.');
                    }
                });
            }
        }
</script>

@endsection



@extends('layouts.app')

@section('page-title', __('Batches'))
@section('page-heading', $edit ? __('Edit Batch') : __('Create New Batch'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('batches.index') }}">@lang('Batches')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    @if ($edit)
        {!! Form::open(['route' => ['batches.update', $batch], 'method' => 'PUT', 'id' => 'batch-form']) !!}
    @else
        {!! Form::open(['route' => 'batches.store', 'id' => 'batch-form']) !!}
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Batch Details')
                    </h5>
                    <p class="text-muted">
                        @lang('General batch information.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="batch_code">@lang('Batch Code')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="batch_code"
                               name="batch_code"
                               placeholder="@lang('Batch Code')"
                               value="{{ $edit ? $batch->batch_code : old('batch_code') }}" required>
                        <div id="batch-code-feedback" class="invalid-feedback" style="display: none;">
                            This batch code already exists. Please use a different code.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="course_id">@lang('Course')</label>
                        {!! Form::select('course_id', $courses->pluck('course_name', 'id'), $edit ? $batch->course_id : old('course_id'), ['class' => 'form-control', 'id' => 'course_id']) !!}
                    </div>

                     <!-- Add Total Seat Field -->
                    <div class="form-group">
                        <label for="total_seat">@lang('Total Seats')</label>
                        <input type="number"
                               class="form-control input-solid"
                               id="total_seat"
                               name="total_seat"
                               placeholder="@lang('Enter total number of seats')"
                               min="1"
                               max="1000"
                               value="{{ $edit ? $batch->total_seat : old('total_seat', 30) }}" 
                               required>
                        <small class="form-text text-muted">
                            @lang('Maximum number of students that can enroll in this batch.')
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        {!! Form::select('status', ['On' => 'On', 'Off' => 'Off'], $edit ? $batch->status : 'Off', ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" id="submit-btn">
        {{ __($edit ? 'Update Batch' : 'Create Batch') }}
    </button>

    {!! Form::close() !!}
@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Batch\UpdateBatchRequest', '#batch-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Batch\CreateBatchRequest', '#batch-form') !!}
    @endif
</script>
    <script>
        // Real-time batch code validation
        document.addEventListener('DOMContentLoaded', function() {
            const batchCodeInput = document.getElementById('batch_code');
            const feedbackElement = document.getElementById('batch-code-feedback');
            const submitBtn = document.getElementById('submit-btn');
            let validationTimeout;

            batchCodeInput.addEventListener('input', function() {
                clearTimeout(validationTimeout);
                
                // Remove previous validation states
                batchCodeInput.classList.remove('is-invalid', 'is-valid');
                feedbackElement.style.display = 'none';
                submitBtn.disabled = false;

                const batchCode = this.value.trim();
                
                if (batchCode.length > 0) {
                    validationTimeout = setTimeout(() => {
                        checkBatchCode(batchCode);
                    }, 500);
                }
            });

            function checkBatchCode(batchCode) {
                fetch('{{ route("batches.check-code") }}?batch_code=' + encodeURIComponent(batchCode))
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            batchCodeInput.classList.add('is-invalid');
                            feedbackElement.style.display = 'block';
                            submitBtn.disabled = true;
                        } else {
                            batchCodeInput.classList.add('is-valid');
                            feedbackElement.style.display = 'none';
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error checking batch code:', error);
                    });
            }
        });
    </script>

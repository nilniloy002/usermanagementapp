@extends('layouts.app')

@section('page-title', __('Mock Test Time Slots'))
@section('page-heading', $edit ? __('Edit Mock Test Time Slot') : __('Create New Mock Test Time Slot'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('mock_test_time_slots.index') }}">@lang('Mock Test Time Slots')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    @if ($edit)
        {!! Form::open(['route' => ['mock_test_time_slots.update', $mockTestTimeSlot], 'method' => 'PUT', 'id' => 'mock-test-time-slot-form']) !!}
    @else
        {!! Form::open(['route' => 'mock_test_time_slots.store', 'id' => 'mock-test-time-slot-form']) !!}
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Mock Test Time Slot Details')
                    </h5>
                    <p class="text-muted">
                        @lang('General information about the mock test time slot.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="time_range">@lang('Time Range')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="time_range"
                               name="time_range"
                               placeholder="@lang('e.g., 10:30am-02:30pm')"
                               value="{{ $edit ? $mockTestTimeSlot->time_range : old('time_range') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="slot_key">@lang('Slots')</label>
                        {!! Form::select('slot_key', [
                            'LRW Slot' => 'LRW Slot',
                            'Speaking Slot Morning' => 'Speaking Slot Morning',
                            'Speaking Slot Afternoon' => 'Speaking Slot Afternoon',
                            'IoC LRW Slot' => 'IoC LRW Slot',
                            'IoC Speaking Slot Morning' => 'IoC Speaking Slot Morning',
                            'IoC Speaking Slot Afternoon' => 'IoC Speaking Slot Afternoon',
                            'PTE Slot' => 'PTE Slot',
                        ], $edit ? $mockTestTimeSlot->slot_key : old('slot_key'), ['class' => 'form-control', 'id' => 'slot_key', 'required']) !!}
                    </div>
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        {!! Form::select('status', ['On' => 'On', 'Off' => 'Off'], $edit ? $mockTestTimeSlot->status : 'On', ['class' => 'form-control', 'id' => 'status', 'required']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Mock Test Time Slot' : 'Create Mock Test Time Slot') }}
    </button>

    {!! Form::close() !!}

@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestTimeSlot\UpdateMockTestTimeSlotRequest', '#mock-test-time-slot-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestTimeSlot\CreateMockTestTimeSlotRequest', '#mock-test-time-slot-form') !!}
    @endif
@stop


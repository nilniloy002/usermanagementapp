@extends('layouts.app')

@section('page-title', $edit ? __('Edit Mock Test Date') : __('Add Mock Test Date'))
@section('page-heading', $edit ? __('Edit Mock Test Date') : __('Add Mock Test Date'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('mock_test_dates.index') }}">@lang('Mock Test Dates')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Add/Edit Mock Test Date')
    </li>
@stop

@section('content')

    <form action="{{ $edit ? route('mock_test_dates.update', $mockTestDate) : route('mock_test_dates.store') }}" method="POST" id="mock-test-date-form">
        @csrf
        @if ($edit)
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="mocktest_date">@lang('Mock Test Date')</label>
                    <input type="date" name="mocktest_date" id="mocktest_date" class="form-control" value="{{ old('mocktest_date', $mockTestDate->mocktest_date ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="status">@lang('Status')</label>
                    <select name="status" id="status" class="form-control">
                        <option value="On" {{ old('status', $mockTestDate->status ?? '') == 'On' ? 'selected' : '' }}>On</option>
                        <option value="Off" {{ old('status', $mockTestDate->status ?? '') == 'Off' ? 'selected' : '' }}>Off</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">@lang('Save')</button>
            </div>
        </div>
    </form>

@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestDate\UpdateMockTestDateRequest', '#mock-test-date-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestDate\CreateMockTestDateRequest', '#mock-test-date-form') !!}
    @endif
@stop

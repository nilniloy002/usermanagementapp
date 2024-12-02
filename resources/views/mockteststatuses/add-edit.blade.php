@extends('layouts.app')

@section('page-title', isset($mockTestStatus) ? __('Edit Mock Test Status') : __('Add Mock Test Status'))
@section('page-heading', isset($mockTestStatus) ? __('Edit Mock Test Status') : __('Add Mock Test Status'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('mock_test_statuses.index') }}">@lang('Mock Test Statuses')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Add/Edit Mock Test Status')
    </li>
@stop

@section('content')

    <form action="{{ isset($mockTestStatus) ? route('mock_test_statuses.update', $mockTestStatus) : route('mock_test_statuses.store') }}" method="POST" id="mock-test-status-form">
        @csrf
        @isset($mockTestStatus)
            @method('PUT')
        @endisset

        <div class="card">
            <div class="card-body">
                <!-- Display validation errors if they exist -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="mock_status">@lang('Mock Status')</label>
                    <input type="text" name="mock_status" id="mock_status" class="form-control" value="{{ old('mock_status', $mockTestStatus->mock_status ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="status">@lang('Status')</label>
                    <select name="status" id="status" class="form-control">
                        <option value="On" {{ old('status', $mockTestStatus->status ?? '') == 'On' ? 'selected' : '' }}>On</option>
                        <option value="Off" {{ old('status', $mockTestStatus->status ?? '') == 'Off' ? 'selected' : '' }}>Off</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">@lang('Save')</button>
            </div>
        </div>
    </form>

@stop

@section('scripts')
    @if (isset($mockTestStatus)) <!-- If it's the edit form -->
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestStatus\UpdateMockTestStatusRequest', '#mock-test-status-form') !!}
    @else <!-- If it's the create form -->
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestStatus\CreateMockTestStatusRequest', '#mock-test-status-form') !!}
    @endif
@stop

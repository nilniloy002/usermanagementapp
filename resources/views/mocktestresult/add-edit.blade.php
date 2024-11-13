@extends('layouts.app')

@section('page-title', __('Mock Test Results'))
@section('page-heading', $edit ? __('Edit Mock Test Result') : __('Create New Mock Test Result'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('mock_test_results.index') }}">@lang('Mock Test Results')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    @if ($edit)
        {!! Form::open(['route' => ['mock_test_results.update', $mockTestResult], 'method' => 'PUT', 'id' => 'mock-test-result-form']) !!}
    @else
        {!! Form::open(['route' => 'mock_test_results.store', 'id' => 'mock-test-result-form']) !!}
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Mock Test Result Details')
                    </h5>
                    <p class="text-muted">
                        @lang('General information of the mock test result.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="mocktest_date">@lang('Mock Test Date')</label>
                        <input type="date" class="form-control" id="mocktest_date" name="mocktest_date" value="{{ $edit ? $mockTestResult->mocktest_date : old('mocktest_date') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="@lang('Name')" value="{{ $edit ? $mockTestResult->name : old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile">@lang('Mobile')</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="@lang('Mobile')" value="{{ $edit ? $mockTestResult->mobile : old('mobile') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="lstn_cor_ans">@lang('Listening Correct Answer (out of 40)')</label>
                        <input type="text" class="form-control" id="lstn_cor_ans" name="lstn_cor_ans" placeholder="@lang('Listening Correct Answer')" value="{{ $edit ? $mockTestResult->lstn_cor_ans : old('lstn_cor_ans') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="lstn_score">@lang('Listening Score')</label>
                        <input type="text" step="0.5" class="form-control" id="lstn_score" name="lstn_score" placeholder="@lang('Listening Score')" value="{{ $edit ? $mockTestResult->lstn_score : old('lstn_score') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="speak_score">@lang('Speaking Score')</label>
                        <input type="text" step="0.5" class="form-control" id="speak_score" name="speak_score" placeholder="@lang('Speaking Score')" value="{{ $edit ? $mockTestResult->speak_score : old('speak_score') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="read_cor_ans">@lang('Reading Correct Answer (out of 40)')</label>
                        <input type="text" class="form-control" id="read_cor_ans" name="read_cor_ans" placeholder="@lang('Reading Correct Answer')" value="{{ $edit ? $mockTestResult->read_cor_ans : old('read_cor_ans') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="read_score">@lang('Reading Score')</label>
                        <input type="text" step="0.5" class="form-control" id="read_score" name="read_score" placeholder="@lang('Reading Score')" value="{{ $edit ? $mockTestResult->read_score : old('read_score') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="wrt_task1">@lang('Writing Task 1')</label>
                        <input type="text" step="0.5" class="form-control" id="wrt_task1" name="wrt_task1" placeholder="@lang('Writing Task 1')" value="{{ $edit ? $mockTestResult->wrt_task1 : old('wrt_task1') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="wrt_task2">@lang('Writing Task 2')</label>
                        <input type="text" step="0.5" class="form-control" id="wrt_task2" name="wrt_task2" placeholder="@lang('Writing Task 2')" value="{{ $edit ? $mockTestResult->wrt_task2 : old('wrt_task2') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="wrt_score">@lang('Writing Score')</label>
                        <input type="text" step="0.5" class="form-control" id="wrt_score" name="wrt_score" placeholder="@lang('Writing Score')" value="{{ $edit ? $mockTestResult->wrt_score : old('wrt_score') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="overall_score">@lang('Overall Score')</label>
                        <input type="text" step="0.5" class="form-control" id="overall_score" name="overall_score" placeholder="@lang('Overall Score')" value="{{ $edit ? $mockTestResult->overall_score : old('overall_score') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        {!! Form::select('status', ['On' => 'On', 'Off' => 'Off'], $edit ? $mockTestResult->status : 'On', ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Result' : 'Create Result') }}
    </button>

    {!! Form::close() !!}
@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestResult\UpdateMockTestResultRequest', '#mock-test-result-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestResult\CreateMockTestResultRequest', '#mock-test-result-form') !!}
    @endif
@stop

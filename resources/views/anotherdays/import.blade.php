@extends('layouts.app')

@section('page-title', __('Import Mock Test Results'))
@section('page-heading', __('Import Mock Test Results from Excel'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('mock_test_results.index') }}">@lang('Mock Test Results')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Import')
    </li>
@stop

@section('content')

    @include('partials.messages')

    {!! Form::open(['route' => 'mock_test_results.import', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="file">@lang('Upload Excel File')</label>
                <input type="file" class="form-control" id="file" name="file" required>
                <small class="form-text text-muted">
                    @lang('The Excel file should contain columns such as name, mobile, mocktest_date, lstn_cor_ans, lstn_score, etc.')
                </small>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">@lang('Import Data')</button>

    {!! Form::close() !!}
    
@stop

@extends('layouts.app')

@section('page-title', __('Import Speaking Another Day List'))
@section('page-heading', __('Import Speaking Another Day List from Excel'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('another_days.index') }}">@lang('Speaking Another Day List')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Import')
    </li>
@stop

@section('content')

    @include('partials.messages')

    {!! Form::open(['route' => 'another_days.import', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    
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

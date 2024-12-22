@extends('layouts.app')

@section('page-title', $edit ? __('Edit Another Day Booking') : __('Create New Another Day Booking'))
@section('page-heading', $edit ? __('Edit Another Day Booking') : __('Create New Another Day Booking'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('another_days.index') }}">@lang('Another Day Bookings')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    @if ($edit)
        {!! Form::open(['route' => ['another_days.update', $anotherDay->id], 'method' => 'PUT', 'id' => 'another-day-form']) !!}
    @else
        {!! Form::open(['route' => 'another_days.store', 'id' => 'another-day-form']) !!}
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Another Day Booking Details')
                    </h5>
                    <p class="text-muted">
                        @lang('General booking information for Another Day.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="speaking_date">@lang('Speaking Date')</label>
                        <input type="date"
                               class="form-control input-solid"
                               id="speaking_date"
                               name="speaking_date"
                               value="{{ $edit ? $anotherDay->speaking_date : old('speaking_date') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="candidate_email">@lang('Candidate Email')</label>
                        <input type="email"
                               class="form-control input-solid"
                               id="candidate_email"
                               name="candidate_email"
                               placeholder="@lang('Candidate Email')"
                               value="{{ $edit ? $anotherDay->candidate_email : old('candidate_email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="speaking_time">@lang('Speaking Time')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="speaking_time"
                               name="speaking_time"
                               placeholder="@lang('Speaking Time')"
                               value="{{ $edit ? $anotherDay->speaking_time : old('speaking_time') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="zoom_link">@lang('Zoom Link')</label>
                        <textarea class="form-control input-solid" id="zoom_link" name="zoom_link" rows="3" required>{{ $edit ? $anotherDay->zoom_link : old('zoom_link') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="trainers_email">@lang('Trainer\'s Email')</label>
                        <input type="email"
                               class="form-control input-solid"
                               id="trainers_email"
                               name="trainers_email"
                               placeholder="@lang('Trainer\'s Email')"
                               value="{{ $edit ? $anotherDay->trainers_email : old('trainers_email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        {!! Form::select('status', ['On' => 'On', 'Off' => 'Off'], $edit ? $anotherDay->status : 'Off', ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Booking' : 'Create Booking') }}
    </button>

    {!! Form::close() !!}

@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\AnotherDay\UpdateAnotherDayRequest', '#another-day-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\AnotherDay\CreateAnotherDayRequest', '#another-day-form') !!}
    @endif
@stop

@extends('layouts.app')

@section('page-title', __('Candidate Result'))
@section('page-heading', __('Check Your Mock Test Result'))

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">@lang('Enter Your Details')</h5>

            {!! Form::open(['route' => 'candidate.result.search', 'method' => 'POST']) !!}
                <div class="form-group">
                    <label for="mocktest_date">@lang('Mock Test Date')</label>
                    <input type="text" class="form-control" id="mocktest_date" name="mocktest_date" placeholder="dd/mm/yyyy" required>
                </div>

                <div class="form-group">
                    <label for="mobile">@lang('Mobile')</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="@lang('Mobile')" required>
                </div>

                <button type="submit" class="btn btn-primary">@lang('Check Result')</button>
            {!! Form::close() !!}
        </div>
    </div>

@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            // Datepicker for mock test date input (optional: configure as per preference)
            $('#mocktest_date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            });
        });
    </script>
@stop

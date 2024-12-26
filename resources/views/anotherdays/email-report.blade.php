@extends('layouts.app')

@section('page-title', __('Email Report'))
@section('page-heading', __('Email Report'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Email Report')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="table-responsive" id="email-report-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>@lang('Recipient')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Subject')</th>
                            <th>@lang('Opened At')</th>
                            <th>@lang('Created At')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($emailLogs->count())
                            @foreach ($emailLogs as $log)
                                <tr>
                                    <td>{{ $log->recipient }}</td>
                                    <td>{{ $log->status }}</td>
                                    <td>{{ $log->subject }}</td>
                                    <td>{{ $log->opened_at ?? 'Not Opened' }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5"><em>@lang('No email logs found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@stop

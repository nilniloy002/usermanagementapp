@extends('layouts.app')

@section('page-title', __('Mock Test Registrations'))
@section('page-heading', __('Mock Test Registrations'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Mock Test Registrations')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <div class="row mb-3 pb-3 border-bottom-light">
            <div class="col-lg-12">
                <div class="float-right">
                    <a href="{{ route('mock_test_registrations.create') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('Add Mock Test Registration')
                    </a>
                </div>
            </div>
        </div>

        <div class="table-responsive" id="mock-test-registrations-table-wrapper">
            <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Mobile')</th>
                        <!-- <th>@lang('Email')</th> -->
                        <th>@lang('Exam Date')</th>
                        <th>@lang('Exam Status')</th>
                        <th>@lang('Exam Details')</th>
                        <th>@lang('No. of Mock Test')</th>
                        <th>@lang('Current Mock Test')</th>
                        <th class="text-center">@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mockTestRegistrations as $registration)
                        <tr>
                            <td>{{ $registration->name }}</td>
                            <td>{{ $registration->mobile }}</td>
                            <!-- <td>{{ $registration->email }}</td> -->
                            <td>{{ \Carbon\Carbon::parse($registration->mockTestDate->mocktest_date)->format('d-m-Y') }}</td>
                            <td>{{ $registration->examStatus->mock_status }}</td>
                            <td>
                                <strong>@lang('LRW Time'):</strong> {{ $registration->lrw_time_slot }}<br>
                                <strong>@lang('Speaking Time'):</strong> 
                                @if ($registration->speaking_time_slot_id_another_day)
                                    @lang('Another Day')
                                @else
                                    {{ $registration->speakingTimeSlot?->time_range ?? '-' }}
                                @endif
                                <br>
                                <strong>@lang('Room'):</strong> 
                                @if ($registration->speaking_time_slot_id_another_day)
                                    -
                                @else
                                    {{ $registration->speakingRoom?->mocktest_room ?? '-' }}
                                @endif
                            </td>
                            <td>{{ $registration->no_of_mock_test }}</td>
                            <td>{{ $registration->mock_test_no }}</td>
                            <td class="text-center">
                                <a href="{{ route('mock_test_registrations.edit', $registration) }}" class="btn btn-icon" title="@lang('Edit Registration')">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('mock_test_registrations.destroy', $registration) }}" class="btn btn-icon" title="@lang('Delete Registration')" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure?')" data-confirm-delete="@lang('Yes, delete it!')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9"><em>@lang('No mock test registrations found.')</em></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@stop

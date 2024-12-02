@extends('layouts.app')

@section('page-title', __('Mock Test Dates'))
@section('page-heading', __('Mock Test Dates'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Mock Test Dates')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('mock_test_dates.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Mock Test Date')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="mock-test-dates-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th class="min-width-150">@lang('Mock Test Date')</th>
                            <th class="min-width-100">@lang('Status')</th>
                            <th class="text-center">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mockTestDates as $date)
                            <tr>
                            <td>{{ \Carbon\Carbon::parse($date->mocktest_date)->format('d-m-Y') }}</td>
                                <td>{{ $date->status }}</td>
                                <td class="text-center">
                                    <a href="{{ route('mock_test_dates.edit', $date) }}" class="btn btn-icon" title="@lang('Edit Mock Test Date')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('mock_test_dates.destroy', $date) }}" class="btn btn-icon" title="@lang('Delete Mock Test Date')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this date?')" data-confirm-delete="@lang('Yes, delete it!')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"><em>@lang('No mock test dates found.')</em></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $mockTestDates->links() }}
            </div>
        </div>
    </div>

@stop

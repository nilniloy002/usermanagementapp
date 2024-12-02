@extends('layouts.app')

@section('page-title', __('Mock Test Time Slots'))
@section('page-heading', __('Mock Test Time Slots'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Mock Test Time Slots')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-6">
                    <!-- Search Form -->
                    <form action="{{ route('mock_test_time_slots.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="@lang('Search by Slot Key')" 
                                   value="{{ old('search', $search) }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">@lang('Search')</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="float-right">
                        <a href="{{ route('mock_test_time_slots.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Mock Test Time Slot')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="mock-test-time-slots-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th class="min-width-150">@lang('Time Range')</th>
                            <th class="min-width-150">@lang('Slots')</th>
                            <th class="min-width-100">@lang('Status')</th>
                            <th class="text-center">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($mockTestTimeSlots->count())
                            @foreach ($mockTestTimeSlots as $slot)
                                <tr>
                                    <td>{{ $slot->time_range }}</td>
                                    <td>{{ $slot->slot_key }}</td>
                                    <td>{{ $slot->status }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('mock_test_time_slots.edit', $slot) }}" class="btn btn-icon" title="@lang('Edit Mock Test Time Slot')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('mock_test_time_slots.destroy', $slot) }}" class="btn btn-icon" title="@lang('Delete Mock Test Time Slot')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this time slot?')" data-confirm-delete="@lang('Yes, delete it!')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4"><em>@lang('No mock test time slots found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="mt-3">
                {{ $mockTestTimeSlots->appends(['search' => $search])->links() }}
            </div>
        </div>
    </div>

@stop

@extends('layouts.app')

@section('page-title', __('Another Day Bookings'))
@section('page-heading', __('Another Day Bookings'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Another Day Bookings')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-8">
                    <form method="GET" action="{{ route('another_days.index') }}" class="form-inline">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search by email or trainer's email">
                        <button type="submit" class="btn btn-primary">@lang('Search')</button>
                    </form>
                </div>
                <div class="col-lg-4 text-right">
                    <a href="{{ route('another_days.create') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('Add New Another Day Booking')
                    </a>
                </div>
            </div>

            <div class="table-responsive" id="another-days-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>@lang('ID')</th>
                            <th>@lang('Speaking Date')</th>
                            <th>@lang('Candidate Email')</th>
                            <th>@lang('Speaking Time')</th>
                            <th>@lang('Zoom Link')</th>
                            <th>@lang('Trainer\'s Email')</th>
                            <th>@lang('Status')</th>
                            <th class="text-center">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($anotherDays->count())
                            @foreach ($anotherDays as $anotherDay)
                                <tr>
                                    <td>{{ $anotherDay->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($anotherDay->speaking_date)->format('d-m-Y') }}</td>
                                    <td>{{ $anotherDay->candidate_email }}</td>
                                    <td>{{ $anotherDay->speaking_time }}</td>
                                    <td>{{ $anotherDay->zoom_link }}</td>
                                    <td>{{ $anotherDay->trainers_email }}</td>
                                    <td>{{ $anotherDay->status }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('another_days.edit', $anotherDay->id) }}" class="btn btn-icon" title="@lang('Edit Booking')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Only Admin and Super Admin roles can see the delete button -->
                                        @if(auth()->user()->role_id != 2) 
                                            <a href="{{ route('another_days.destroy', $anotherDay->id) }}" class="btn btn-icon" title="@lang('Delete Booking')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this booking?')" data-confirm-delete="@lang('Yes, delete it!')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8"><em>@lang('No Another Day bookings found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $anotherDays->appends(['search' => request('search')])->links() }}
    </div>

@stop

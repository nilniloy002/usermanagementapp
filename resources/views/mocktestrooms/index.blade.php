@extends('layouts.app')

@section('page-title', __('Mock Test Rooms'))
@section('page-heading', __('Mock Test Rooms'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Mock Test Rooms')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('mock_test_rooms.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Mock Test Room')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="mock-test-rooms-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th class="min-width-150">@lang('Room Name')</th>
                            <th class="min-width-100">@lang('Status')</th>
                            <th class="text-center">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($mockTestRooms->count())
                            @foreach ($mockTestRooms as $room)
                                <tr>
                                    <td>{{ $room->mocktest_room }}</td>
                                    <td>{{ $room->status }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('mock_test_rooms.edit', $room) }}" class="btn btn-icon" title="@lang('Edit Mock Test Room')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                         <!-- Only Admin and Super Admin roles can see the delete button -->
                         @if (in_array(auth()->user()->role_id, [1, 3])) 
                                        <a href="{{ route('mock_test_rooms.destroy', $room) }}" class="btn btn-icon" title="@lang('Delete Mock Test Room')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this room?')" data-confirm-delete="@lang('Yes, delete it!')">
                                            <i class="fas fa-trash"></i>
                                        </a>

                            @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3"><em>@lang('No mock test rooms found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $mockTestRooms->links() }}
            </div>
        </div>
    </div>

@stop

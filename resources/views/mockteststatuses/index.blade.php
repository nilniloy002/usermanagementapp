@extends('layouts.app')

@section('page-title', __('Mock Test Statuses'))
@section('page-heading', __('Mock Test Statuses'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Mock Test Statuses')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('mock_test_statuses.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Mock Test Status')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="mock-test-statuses-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th class="min-width-150">@lang('Mock Test Status')</th>
                            <th class="min-width-150">@lang('Status')</th>
                            <th class="text-center">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($mockTestStatuses->count())
                            @foreach ($mockTestStatuses as $status)
                                <tr>
                                    <td>{{ $status->mock_status }}</td>
                                    <td>{{ $status->status }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('mock_test_statuses.edit', $status) }}" class="btn btn-icon" title="@lang('Edit Mock Test Status')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                         <!-- Only Admin and Super Admin roles can see the delete button -->
                         @if (in_array(auth()->user()->role_id, [1, 3]))
                                        <form action="{{ route('mock_test_statuses.destroy', $status) }}" method="POST" class="d-inline" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this mock test status?')" data-confirm-delete="@lang('Yes, delete it!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3"><em>@lang('No mock test statuses found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{ $mockTestStatuses->links() }}
            </div>
        </div>
    </div>

@stop

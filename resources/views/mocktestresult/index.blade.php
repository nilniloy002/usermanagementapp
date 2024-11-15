@extends('layouts.app')

@section('page-title', __('Mock Test Results'))
@section('page-heading', __('Mock Test Results'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Mock Test Results')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-8">
                    <form method="GET" action="{{ route('mock_test_results.index') }}" class="form-inline">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search by name, mobile, or status">
                        <button type="submit" class="btn btn-primary">@lang('Search')</button>
                    </form>
                </div>
                <div class="col-lg-4 text-right">
                    <a href="{{ route('mock_test_results.create') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('Add Mock Test Result')
                    </a>
                </div>
            </div>

            <div class="table-responsive" id="mock-test-results-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Overall Score')</th>
                            <th>@lang('Status')</th>
                            <th class="text-center">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($results->count())
                            @foreach ($results as $result)
                                <tr>
                                    <td>{{ $result->name }}</td>
                                    <td>{{ $result->mobile }}</td>
                                    <td>{{ \Carbon\Carbon::parse($result->mocktest_date)->format('d-m-Y') }}</td>
                                    <td>{{ $result->overall_score }}</td>
                                    <td>{{ $result->status }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('mock_test_results.edit', $result) }}" class="btn btn-icon" title="@lang('Edit Result')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Only Admin and Super Admin roles can see the delete button -->
                                        @if(auth()->user()->role_id != 2) 
                                            <a href="{{ route('mock_test_results.destroy', $result) }}" class="btn btn-icon" title="@lang('Delete Result')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this result?')" data-confirm-delete="@lang('Yes, delete it!')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6"><em>@lang('No mock test results found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $results->appends(['search' => request('search')])->links() }}
    </div>

@stop

@extends('layouts.app')

@section('page-title', __('Batches'))
@section('page-heading', __('Batches'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Batches')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('batches.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Batch')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="batches-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th class="min-width-100">@lang('Batch Code')</th>
                            <th class="min-width-100">@lang('Course')</th>
                            <th class="min-width-80">@lang('Total Seats')</th>
                            <th class="min-width-80">@lang('Available')</th>
                            <th class="min-width-80">@lang('Status')</th>
                            <th class="text-center min-width-150">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($batches))
                            @foreach ($batches as $batch)
                                <tr>
                                    <td>{{ $batch->batch_code }}</td>
                                    <td>{{ $batch->course->course_name }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $batch->total_seat }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $batch->has_available_seats ? 'success' : 'danger' }}">
                                            {{ $batch->available_seats }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $batch->status == 'On' ? 'success' : 'secondary' }}">
                                            {{ $batch->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('batches.edit', $batch) }}" class="btn btn-icon" title="@lang('Edit Batch')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('batches.destroy', $batch) }}" class="btn btn-icon" title="@lang('Delete Batch')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this batch?')" data-confirm-delete="@lang('Yes, delete it!')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6"><em>@lang('No batches found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop
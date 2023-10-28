@extends('layouts.app')

@section('page-title', __('Departments'))
@section('page-heading', __('Departments'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Departments')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('departments.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Department')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="departments-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                        <th class="min-width-100">@lang('Department Name')</th>
                        <th class="text-center">@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($departments))
                        @foreach ($departments as $department)
                            <tr>
                                <td>{{ $department->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-icon"
                                       title="@lang('Edit Department')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('departments.destroy', $department) }}" class="btn btn-icon"
                                       title="@lang('Delete Department')"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       data-method="DELETE"
                                       data-confirm-title="@lang('Please Confirm')"
                                       data-confirm-text="@lang('Are you sure that you want to delete this department?')"
                                       data-confirm-delete="@lang('Yes, delete it!')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2"><em>@lang('No departments found.')</em></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

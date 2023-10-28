@extends('layouts.app')

@section('page-title', __('Employees'))
@section('page-heading', __('Employees'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Employees')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Employee')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="employees-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                        <th class="min-width-100">@lang('Name')</th>
                        <th class="min-width-150">@lang('Email')</th>
                        <th class="min-width-150">@lang('Department')</th>
                        <th class="text-center">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($employees))
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->department->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-icon"
                                       title="@lang('Edit Employee')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('employees.destroy', $employee) }}" class "btn btn-icon"
                                    title="@lang('Delete Employee')"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    data-method="DELETE"
                                    data-confirm-title="@lang('Please Confirm')"
                                    data-confirm-text="@lang('Are you sure that you want to delete this employee?')"
                                    data-confirm-delete="@lang('Yes, delete it!')">
                                    <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4"><em>@lang('No employees found.')</em></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop

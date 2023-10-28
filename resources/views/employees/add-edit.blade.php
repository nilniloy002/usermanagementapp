@extends('layouts.app')

@section('page-title', __('Employees'))
@section('page-heading', $edit ? __('Edit Employee') : __('Create New Employee'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('employees.index') }}">@lang('Employees')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    @if ($edit)
        {!! Form::model($employee, ['route' => ['employees.update', $employee], 'method' => 'PUT', 'id' => 'employee-form']) !!}
    @else
        {!! Form::open(['route' => 'employees.store', 'id' => 'employee-form']) !!}
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Employee Details')
                    </h5>
                    <p class="text-muted">
                        @lang('General employee information.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="name"
                               name="name"
                               placeholder="@lang('Employee Name')"
                               value="{{ $edit ? $employee->name : old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('Email')</label>
                        <input type="email"
                               class="form-control input-solid"
                               id="email"
                               name="email"
                               placeholder="@lang('Email Address')"
                               value="{{ $edit ? $employee->email : old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="department_id">@lang('Department')</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="">@lang('Select Department')</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                    {{ $edit && $employee->department_id == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Employee' : 'Create Employee') }}
    </button>

    @stop

    @section('scripts')
        @if ($edit)
            {!! JsValidator::formRequest('Vanguard\Http\Requests\Employees\UpdateEmployeeRequest', '#employee-form') !!}
        @else
            {!! JsValidator::formRequest('Vanguard\Http\Requests\Employees\CreateEmployeeRequest', '#employee-form') !!}
        @endif
        </script>

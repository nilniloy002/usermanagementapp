@extends('layouts.app')

@section('page-title', __('Departments'))
@section('page-heading', $edit ? __('Edit Department') : __('Create New Department'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('departments.index') }}">@lang('Departments')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    @if ($edit)
        {!! Form::open(['route' => ['departments.update', $department], 'method' => 'PUT', 'id' => 'department-form']) !!}
    @else
        {!! Form::open(['route' => 'departments.store', 'id' => 'department-form']) !!}
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Department Details')
                    </h5>
                    <p class="text-muted">
                        @lang('General department information.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="name"
                               name="name"
                               placeholder="@lang('Department Name')"
                               value="{{ $edit ? $department->name : old('name') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Department' : 'Create Department') }}
    </button>

    @stop

    @section('scripts')
        @if ($edit)
            {!! JsValidator::formRequest('Vanguard\Http\Requests\Department\UpdateDepartmentRequest', '#department-form') !!}
        @else
            {!! JsValidator::formRequest('Vanguard\Http\Requests\Department\CreateDepartmentRequest', '#department-form') !!}
        @endif
        </script>

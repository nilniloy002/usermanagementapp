@extends('layouts.app')

@section('page-title', __('Courses'))
@section('page-heading', $edit ? __('Edit Course') : __('Create New Course'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('courses.index') }}">@lang('Courses')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    @if ($edit)
        {!! Form::open(['route' => ['courses.update', $course], 'method' => 'PUT', 'id' => 'course-form']) !!}
    @else
        {!! Form::open(['route' => 'courses.store', 'id' => 'course-form']) !!}
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Course Details')
                    </h5>
                    <p class="text-muted">
                        @lang('General course information.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="course_name">@lang('Course Name')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="course_name"
                               name="course_name"
                               placeholder="@lang('Course Name')"
                               value="{{ $edit ? $course->course_name : old('course_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="course_fee">@lang('Course Fee')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="course_fee"
                               name="course_fee"
                               placeholder="@lang('Course Fee')"
                               value="{{ $edit ? $course->course_fee : old('course_fee') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="admission_fee">@lang('Admission Fee')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="admission_fee"
                               name="admission_fee"
                               placeholder="@lang('Admission Fee')"
                               value="{{ $edit ? $course->admission_fee : old('admission_fee') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        {!! Form::select('status', ['On' => 'On', 'Off' => 'Off'], $edit ? $course->status : 'Off', ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Course' : 'Create Course') }}
    </button>

    {!! Form::close() !!}
@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Course\UpdateCourseRequest', '#course-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Course\CreateCourseRequest', '#course-form') !!}
    @endif
</script>

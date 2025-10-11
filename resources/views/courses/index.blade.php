<!-- index.blade.php -->

@extends('layouts.app')

@section('page-title', __('Courses'))
@section('page-heading', __('Courses'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Courses')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('courses.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Course')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="courses-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th class="min-width-100">@lang('Course Name')</th>
                            <th class="min-width-100">@lang('Course Fee')</th>
                            <th class="min-width-100">@lang('Status')</th>
                            <th class="text-center">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($courses))
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->course_name }}</td>
                                    <td>{{ $course->course_fee }}</td>
                                    <td>{{ $course->status }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-icon" title="@lang('Edit Course')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('courses.destroy', $course) }}" class="btn btn-icon" title="@lang('Delete Course')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this course?')" data-confirm-delete="@lang('Yes, delete it!')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4"><em>@lang('No courses found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop

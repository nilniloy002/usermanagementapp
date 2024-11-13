@extends('layouts.app')

@section('page-title', __('Batches'))
@section('page-heading', $edit ? __('Edit Batch') : __('Create New Batch'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('batches.index') }}">@lang('Batches')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    @if ($edit)
        {!! Form::open(['route' => ['batches.update', $batch], 'method' => 'PUT', 'id' => 'batch-form']) !!}
    @else
        {!! Form::open(['route' => 'batches.store', 'id' => 'batch-form']) !!}
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Batch Details')
                    </h5>
                    <p class="text-muted">
                        @lang('General batch information.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="batch_code">@lang('Batch Code')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="batch_code"
                               name="batch_code"
                               placeholder="@lang('Batch Code')"
                               value="{{ $edit ? $batch->batch_code : old('batch_code') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="course_id">@lang('Course')</label>
                        {!! Form::select('course_id', $courses->pluck('course_name', 'id'), $edit ? $batch->course_id : old('course_id'), ['class' => 'form-control', 'id' => 'course_id']) !!}
                    </div>
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        {!! Form::select('status', ['On' => 'On', 'Off' => 'Off'], $edit ? $batch->status : 'Off', ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Batch' : 'Create Batch') }}
    </button>

    {!! Form::close() !!}
@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Batch\UpdateBatchRequest', '#batch-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Batch\CreateBatchRequest', '#batch-form') !!}
    @endif
</script>

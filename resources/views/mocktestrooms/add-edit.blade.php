@extends('layouts.app')

@section('page-title', $edit ? __('Edit Mock Test Room') : __('Add Mock Test Room'))
@section('page-heading', $edit ? __('Edit Mock Test Room') : __('Add Mock Test Room'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('mock_test_rooms.index') }}">@lang('Mock Test Rooms')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ $edit ? __('Edit') : __('Add') }}
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <form 
                action="{{ $edit ? route('mock_test_rooms.update', $mockTestRoom) : route('mock_test_rooms.store') }}" 
                method="POST" 
                id="mock-test-room-form">
                @csrf
                @if ($edit)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="mocktest_room">@lang('Room Name')</label>
                    <input 
                        type="text" 
                        name="mocktest_room" 
                        id="mocktest_room" 
                        class="form-control" 
                        value="{{ old('mocktest_room', $edit ? $mockTestRoom->mocktest_room : '') }}" 
                        required>
                </div>

                <div class="form-group">
                    <label for="status">@lang('Status')</label>
                    <select name="status" id="status" class="form-control">
                        <option value="On" {{ old('status', $edit ? $mockTestRoom->status : '') === 'On' ? 'selected' : '' }}>
                            @lang('On')
                        </option>
                        <option value="Off" {{ old('status', $edit ? $mockTestRoom->status : '') === 'Off' ? 'selected' : '' }}>
                            @lang('Off')
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ $edit ? __('Update Mock Test Room') : __('Create Mock Test Room') }}
                </button>
            </form>
        </div>
    </div>

@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestRoom\UpdateMockTestRoomRequest', '#mock-test-room-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\MockTestRoom\CreateMockTestRoomRequest', '#mock-test-room-form') !!}
    @endif
@stop


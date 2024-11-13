<!-- resources/views/due-collection.blade.php -->

@extends('layouts.app')

@section('page-title', __('Due Collection'))
@section('page-heading', __('Due Collection'))

@section('breadcrumbs')
    <!-- Include your breadcrumbs as per your layout -->
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">@lang('Due Collection for Bill ID: ') {{ $billId }}</h5>

            {!! Form::open(['route' => ['admissions.collect-due', $billId], 'method' => 'post', 'id' => 'due-collection-form']) !!}

            <div class="form-group">
                <label for="last_due_amount">@lang('Last Due Amount')</label>
                <input type="text" class="form-control" id="last_due_amount" name="last_due_amount" value="{{ $lastDueAmount }}" readonly>
            </div>

            <div class="form-group">
                <label for="paid_amount">@lang('Paid Amount')</label>
                <input type="text" class="form-control" id="paid_amount" name="paid_amount" required>
            </div>

            <div class="form-group">
                <label for="payment_method">@lang('Payment Method')</label>
                {!! Form::select('payment_method', ['cash' => 'Cash', 'bKash' => 'bKash', 'Nagad' => 'Nagad', 'Bank' => 'Bank'], null, ['class' => 'form-control', 'required']) !!}
            </div>

            <div class="form-group">
                <label for="payment_process">@lang('Payment Process')</label>
                {!! Form::select('payment_process', ['Partial Paid' => 'Partial Paid', 'Full Paid' => 'Full Paid', '1st Installment' => '1st Installment', '2nd Installment' => '2nd Installment', '3rd Installment' => '3rd Installment', '4th Installment' => '4th Installment', 'Admission Fee' => 'Admission Fee'], null, ['class' => 'form-control', 'required']) !!}
            </div>

            <div class="form-group">
                <label for="next_due_date">@lang('Next Due Date')</label>
                <input type="date" class="form-control" id="next_due_date" name="next_due_date" required>
            </div>

            <div class="form-group">
                <label for="remarks">@lang('Remarks')</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">@lang('Submit')</button>

            {!! Form::close() !!}
        </div>
    </div>

@endsection

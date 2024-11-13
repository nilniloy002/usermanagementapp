<!-- show.blade.php -->

@extends('layouts.app')

@section('page-title', __('Admission Details'))
@section('page-heading', __('Admission Details'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('admissions.index') }}">@lang('Admissions')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __('Details') }}
    </li>
@stop

@section('content')
    @php
        $totalFee = $admission->course->course_fee + $admission->course->admission_fee;
        $totalPaid = $admission->payments->sum('paid_amount');
        $totalDiscount = $admission->payments->sum('discount_amount');
        $totalDue = $totalFee - ($totalPaid + $totalDiscount);
    @endphp

    <style>
        span.money-receipt {
            font-family: Roboto;
            font-weight: 500;
            color: #6c6c6c;
        }
    </style>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">@lang('Admission Details for')- <strong>{{  $admission->student_name }}</strong></h5>
            <div class="row">
                <div class="col-md-3">
                    @if($admission->photo)
                        <img src="{{ asset($admission->photo) }}" alt="Photo" class="img-thumbnail" width="200" height="200">
                    @else
                        No Photo
                    @endif
                </div>
                <div class="col-md-9">
                    <p><strong>@lang('Student ID'):</strong> {{ $admission->bill_id }}</p>
                    <p><strong>@lang('Name'):</strong> {{  $admission->student_name }}</p>
                    <p><strong>@lang('Admission Date'):</strong> {{ date("d-m-Y", strtotime($admission->admission_date)) }}</p>
                    <p><strong>@lang('Mobile'):</strong> {{ $admission->phone_number }}</p>
                    <p><strong>@lang('Guardian Mobile'):</strong> {{ $admission->guardian_phone_number }}</p>
                    <p><strong>@lang('Admission for'):</strong> {{ $admission->course->course_name }} Course | <strong>@lang('Batch'):</strong> {{ $admission->batch_code }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h5 class="mt-3 mb-3">@lang('Payment Details')</h5>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th >@lang('Purpose')</th>
                            <th >@lang('Amount')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><strong>@lang('Total Fee')</strong> <br>
                                <small>@lang('Course fee'):{{ $admission->course->course_fee}} Tk. <br>@lang('Admission Fee'): {{ $admission->course->admission_fee }} Tk.</small>
                            </td>
                            <td>{{ $admission->course->course_fee + $admission->course->admission_fee }} Tk.</td>
                        </tr>

                        @foreach($admission->payments as $payment)
                            <tr>
                                <td><strong>{{ $payment->payment_process }}</strong><br>
                                    <small> @lang('Date'): {{ date("d-m-Y", strtotime($payment->payment_date))}}<br>
                                        @lang('Payment Method'): {{  $payment->payment_method }}</small>

                                </td>
                                <td>- {{ $payment->paid_amount }} Tk.</td>
                            </tr>
                        @endforeach

                        <!-- <tr>
                            <td><strong>@lang('Total Paid')</strong>
                            </td>
                            <td>{{$admission->payments->sum('paid_amount')}} Tk.</td>
                        </tr> -->
                        <tr>
                            <td><strong>@lang('Discount')</strong>
                            </td>
                            <td>- {{$admission->payments->sum('discount_amount')}} Tk.</td>
                        </tr>

                        <tr>
                            <td><strong>@lang('Total Due')</strong>
                            </td>
                            <td><strong>{{$totalFee - ($totalPaid + $totalDiscount)}} Tk.</strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center">

            <a href="{{ route('admissions.print', $admission) }}" target="_blank" class="money-receipt btn btn-icon" title="@lang('Print Money Receipt')" data-toggle="tooltip" data-placement="top">
            <i class="fas fa-print"> <span class="money-receipt" >Print Money Receipt</span></i>
        </a>
            </div>




        </div>
    </div>
@endsection

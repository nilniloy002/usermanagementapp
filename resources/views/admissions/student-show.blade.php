@extends('layouts.app')

@section('page-title', __('Application Details'))
@section('page-heading', __('Application Details'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('student-admissions.index') }}">@lang('Student Admissions')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Application Details')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>@lang('Personal Information')</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>@lang('Application Number')</th>
                            <td>{{ $application->application_number }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Full Name')</th>
                            <td>{{ $application->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Date of Birth')</th>
                            <td>{{ $application->dob->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Gender')</th>
                            <td>{{ ucfirst($application->gender) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Mobile')</th>
                            <td>{{ $application->mobile }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Emergency Mobile')</th>
                            <td>{{ $application->emergency_mobile }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Email')</th>
                            <td>{{ $application->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Address')</th>
                            <td>{{ $application->address }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>@lang('Course & Payment Information')</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>@lang('Course')</th>
                            <td>{{ $application->course_name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Course Fee')</th>
                            <td class="text-success">৳{{ number_format($application->course_fee, 2) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Payment Method')</th>
                            <td>{{ $application->payment_method_name }}</td>
                        </tr>
                        @if($application->transaction_id)
                        <tr>
                            <th>@lang('Transaction ID')</th>
                            <td>{{ $application->transaction_id }}</td>
                        </tr>
                        @endif
                        @if($application->serial_number)
                        <tr>
                            <th>@lang('Serial Number')</th>
                            <td>{{ $application->serial_number }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>@lang('Status')</th>
                            <td>
                                <span class="badge badge-{{ 
                                    $application->status == 'approved' ? 'success' : 
                                    ($application->status == 'rejected' ? 'danger' : 'warning') 
                                }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Applied Date')</th>
                            <td>{{ $application->created_at->format('d-m-Y h:i A') }}</td>
                        </tr>
                    </table>
                    
                    @if($application->photo_data)
                    <div class="text-center mt-3">
                        <h6>@lang('Student Photo')</h6>
                        <img src="{{ Storage::disk('public')->url($application->photo_data) }}" 
                             alt="Student Photo" 
                             class="img-thumbnail" 
                             style="max-width: 200px;">
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <a href="{{ route('student-admissions.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left mr-2"></i>@lang('Back to List')
                    </a>
                    
                    <!-- Status Update Buttons -->
                    <!-- <div class="btn-group ml-2">
                        <form action="{{ route('student-admissions.update-status', $application) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" name="status" value="approved" class="btn btn-success">
                                <i class="fas fa-check mr-2"></i>Approve
                            </button>
                            <button type="submit" name="status" value="rejected" class="btn btn-danger">
                                <i class="fas fa-times mr-2"></i>Reject
                            </button>
                            <button type="submit" name="status" value="pending" class="btn btn-warning">
                                <i class="fas fa-clock mr-2"></i>Set Pending
                            </button>
                        </form>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
@stop
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
                    
                    <!-- @if($application->photo_data)
                    <div class="text-center mt-3">
                        <h6>@lang('Student Photo')</h6>
                        <img src="{{ Storage::disk('public')->url($application->photo_data) }}" 
                             alt="Student Photo" 
                             class="img-thumbnail" 
                             style="max-width: 200px;">
                    </div>
                    @endif -->

                                   @if($application->photo_data)
                                        @php
                                            $filename = basename($application->photo_data);
                                            $filePath = public_path('student_photos/' . $filename);
                                            $imageUrl = asset('student_photos/' . $filename);
                                            $fileExists = file_exists($filePath);
                                        @endphp
                                        
                                        @if($fileExists)
                                            <img src="{{ $imageUrl }}" 
                                                 alt="Student Photo" 
                                                class="img-thumbnail" 
                                                style="max-width: 200px;">
                                        @else
                                            <div class="no-photo bg-light rounded d-flex align-items-center justify-content-center" 
                                                style="width: 50px; height: 50px; border: 1px solid #dee2e6;">
                                                <i class="fas fa-user text-muted"></i>
                                                <small class="text-muted ml-1">File missing</small>
                                            </div>
                                        @endif
                                    @else
                                        <div class="no-photo bg-light rounded d-flex align-items-center justify-content-center" 
                                            style="width: 50px; height: 50px; border: 1px solid #dee2e6;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <a href="{{ route('student-admissions.pending-student-index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left mr-2"></i>@lang('Back to List')
                    </a>
                
                </div>
            </div>
        </div>
    </div>
@stop
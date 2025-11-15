@extends('layouts.app')

@section('page-title', __('Student Admissions'))
@section('page-heading', __('Student Admissions'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Student Admissions')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <!-- Remove create button since admissions come from frontend form -->
                        <!-- <a href="{{ route('admissions.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Admission')
                        </a> -->
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="student-admissions-table-wrapper">
                <table class="table table-striped table-borderless" id="student-admissions-table">
                    <thead>
                    <tr>
                        <th class="min-width-100">@lang('Application No.')</th>
                        <th class="min-width-100">@lang('Photo')</th>
                        <th class="min-width-100">@lang('Student Details')</th>
                        <th class="min-width-100">@lang('Course')</th>
                        <th class="min-width-100">@lang('Course Fee')</th>
                        <th class="min-width-100">@lang('Payment Method')</th>
                        <th class="min-width-100">@lang('Status')</th>
                        <th class="min-width-150">@lang('Applied Date')</th>
                        <th class="text-center min-width-150">@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($applications))
                        @foreach ($applications as $application)
                            <tr>
                                <td>
                                    <strong>{{ $application->application_number }}</strong>
                                </td>
                                <td>
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
                                                width="150" 
                                                height="150"
                                                style="object-fit: cover; border-radius: 2px;">
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
                                </td>
                                <td>
                                    <strong>{{ $application->name }}</strong><br>
                                    <small class="text-muted">
                                        Mobile: {{ $application->mobile }}<br>
                                        Email: {{ $application->email }}<br>
                                        Guardian: {{ $application->emergency_mobile }}
                                    </small>
                                </td>
                                <td>
                                    {{ $application->course_name }}<br>
                                    <small class="text-muted">
                                        Gender: {{ ucfirst($application->gender) }}<br>
                                        DOB: {{ $application->dob->format('d-m-Y') }}
                                    </small>
                                </td>
                                <td>
                                    <strong class="text-success">৳{{ number_format($application->course_fee, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $application->payment_method_name }}</span>
                                    @if($application->transaction_id)
                                        <br><small>Txn: {{ $application->transaction_id }}</small>
                                    @endif
                                    @if($application->serial_number)
                                        <br><small>Ref: {{ $application->serial_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $application->status == 'approved' ? 'success' : 
                                        ($application->status == 'rejected' ? 'danger' : 'warning') 
                                    }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $application->created_at->format('d-m-Y h:i A') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('student-admissions.show', $application) }}" 
                                       class="btn btn-icon" 
                                       title="@lang('View Application')" 
                                       data-toggle="tooltip" 
                                       data-placement="top">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Status Update Dropdown -->
                                    <div class="btn-group">
                                        <button type="button" 
                                                class="btn btn-icon dropdown-toggle" 
                                                data-toggle="dropdown" 
                                                aria-haspopup="true" 
                                                aria-expanded="false"
                                                title="@lang('Update Status')">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <form action="{{ route('student-admissions.update-status', $application) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        name="status" 
                                                        value="approved" 
                                                        class="dropdown-item text-success">
                                                    <i class="fas fa-check mr-2"></i>Approve
                                                </button>
                                                <button type="submit" 
                                                        name="status" 
                                                        value="rejected" 
                                                        class="dropdown-item text-danger">
                                                    <i class="fas fa-times mr-2"></i>Reject
                                                </button>
                                                <button type="submit" 
                                                        name="status" 
                                                        value="pending" 
                                                        class="dropdown-item text-warning">
                                                    <i class="fas fa-clock mr-2"></i>Pending
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <a href="{{ route('student-admissions.destroy', $application) }}" 
                                       class="btn btn-icon" 
                                       title="@lang('Delete Application')"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       data-method="DELETE"
                                       data-confirm-title="@lang('Please Confirm')"
                                       data-confirm-text="@lang('Are you sure that you want to delete this application?')"
                                       data-confirm-delete="@lang('Yes, delete it!')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center">
                                <em>@lang('No student applications found.')</em>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (count($applications))
                <div class="row">
                    <div class="col-md-12">
                        {{ $applications->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')
<script>
    $(document).ready(function () {
        $('#student-admissions-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        stripHtml: false
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        stripHtml: false
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        stripHtml: false
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        stripHtml: false
                    }
                }
            ],
            autoPrint: true,  // Automatically trigger the print dialog
            "pageLength": 25,
            "order": [[7, "desc"]] // Sort by created_at descending
        });
    });
</script>
@endsection
@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endsection

@section('page-title', __('IoC Mock Test Registrations'))
@section('page-heading', __('Ioc Mock Test Registrations'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('IoC Mock Test Registrations')
    </li>
@stop

@section('content')

@include('partials.messages')

<!-- Filters Section -->
<!-- Filters Section -->
<div class="card">
    <div class="card-body">
        <form action="{{ route('mock_test_registrations.indexioc') }}" method="GET" class="mb-3">
            <div class="row">
                <!-- Exam Date Filter -->
                <div class="col-md-3">
                    <label for="exam_date">@lang('Exam Date')</label>
                    <select name="exam_date" id="exam_date" class="form-control">
                        <option value="">@lang('All Exam Dates')</option>
                        @foreach ($examDates as $date)
                            <option value="{{ $date }}" {{ request('exam_date') == $date ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- <div class="col-md-3">
                    <label for="exam_pattern">@lang('Exam Pattern')</label>
                    <select name="exam_pattern" id="exam_pattern" class="form-control">
                        <option value="">@lang('All Exam Patterns')</option>
                        <option value="IoP" {{ request('exam_pattern') == 'IoP' ? 'selected' : '' }}>IoP</option>
                        <option value="IoC" {{ request('exam_pattern') == 'IoC' ? 'selected' : '' }}>IoC</option>
                    </select>
                </div> -->

                <!-- LRW Time Filter -->
                <div class="col-md-3">
                    <label for="lrw_time">@lang('LRW Time')</label>
                    <select name="lrw_time" id="lrw_time" class="form-control">
                        <option value="">@lang('All LRW Times')</option>
                        @foreach ($lrwTimes as $time)
                            <option value="{{ $time }}" {{ request('lrw_time') == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Booking Category Filter -->
                <div class="col-md-3">
                    <label for="booking_category">@lang('Booking Category')</label>
                    <select name="booking_category" id="booking_category" class="form-control">
                        <option value="">@lang('All Booking Categories')</option>
                        <option value="Student" {{ request('booking_category') == 'Student' ? 'selected' : '' }}>@lang('Student')</option>
                        <option value="Outsider" {{ request('booking_category') == 'Outsider' ? 'selected' : '' }}>@lang('Outsider')</option>
                    </select>
                </div>

                <!-- Exam Type Filter -->
                <div class="col-md-3">
                    <label for="exam_type">@lang('Exam Type')</label>
                    <select name="exam_type" id="exam_type" class="form-control">
                        <option value="">@lang('All Exam Types')</option>
                        <option value="General" {{ request('exam_type') == 'General' ? 'selected' : '' }}>@lang('General')</option>
                        <option value="Academic" {{ request('exam_type') == 'Academic' ? 'selected' : '' }}>@lang('Academic')</option>
                    </select>
                </div>

                <!-- Filter and Reset Buttons -->
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="fas fa-filter mr-2"></i> @lang('Filter')
                    </button>
                    <a href="{{ route('mock_test_registrations.indexioc') }}" class="btn btn-secondary btn-rounded">
                        @lang('Reset')
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Filters Summary -->
@if(request('exam_date') || request('lrw_time') || request('booking_category') || request('exam_type'))
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <div>
            @lang('Filters Applied'):
            @if(request('exam_date'))
                <strong>@lang('Exam Date'):</strong> {{ \Carbon\Carbon::parse(request('exam_date'))->format('d-m-Y') }}
            @endif
            @if(request('lrw_time'))
                <strong>@lang('LRW Time'):</strong> {{ request('lrw_time') }}
            @endif
            @if(request('booking_category'))
                <strong>@lang('Booking Category'):</strong> {{ request('booking_category') }}
            @endif
            @if(request('exam_type'))
                <strong>@lang('Exam Type'):</strong> {{ request('exam_type') }}
            @endif
        </div>
        <!-- Enhanced Total Registrations Display -->
        <div>
            <h5 class="font-weight-bold">
                <span class="badge badge-pill badge-info py-2 px-3">
                    @lang('Total Registrations'): {{ $totalRegistrations }}
                </span>
            </h5>
        </div>
    </div>
@else
    <div class="alert alert-primary">
        <strong>@lang('Total Registrations'):</strong> {{ $totalRegistrations }}
    </div>
@endif

<!-- Mock Test Registrations Table -->
<div class="card">
    <div class="card-body">
        <div class="row mb-3 pb-3 border-bottom-light">
            <div class="col-lg-12">
                <div class="float-right">
                    <a href="{{ route('mock_test_registrations.createioc') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('Add IoC Mock Test Registration')
                    </a>
                </div>
            </div>
        </div>

        <!-- DataTables Integration -->
        <div class="table-responsive" id="mock-test-registrations-table-wrapper">
        <table id="registrations-table" class="table table-striped table-borderless display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>@lang('C.D')</th>
                <th>@lang('Invoice')</th>
                <th>@lang('Name')</th>
                <th>@lang('Mob.')</th>
                <th>@lang('Email.')</th>
                <th>@lang('Exam Date')</th>
                <th>@lang('Purchase Status')</th>
                <th>@lang('Exam Details')</th>
                <th>@lang('No. of MT')</th>
                <th>@lang('Current MT')</th>
                <th class="text-center">@lang('Actions')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mockTestRegistrations as $registration)
                <tr>
                    <td>{{ $registration->created_at }}</td>
                    <td>{{ $registration->invoice_no }}</td>
                    <td>{{ $registration->name }}</td>
                    <td>{{ $registration->mobile }}</td>
                    <td>{{ $registration->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($registration->mockTestDate->mocktest_date)->format('d-m-Y') }}</td>
                    <td>{{ $registration->examStatus->mock_status }}</td>
                    <td>
                        <strong>@lang('LRW Time'):</strong> {{ $registration->lrw_time_slot }}<br>
                        
                        <strong>@lang('Speaking Time'):</strong> 
                        @if ($registration->speaking_time_slot_id_another_day)
                            @lang('Another Day')
                        @else
                            {{ $registration->speakingTimeSlot?->time_range ?? '-' }}
                        @endif
                        <br>
                        
                        <strong>@lang('Room'):</strong> 
                        @if ($registration->speaking_time_slot_id_another_day)
                            -
                        @else
                            {{ $registration->speakingRoom?->mocktest_room ?? '-' }}
                        @endif
                    </td>
                    <td>{{ $registration->no_of_mock_test }}</td>
                    <td>{{ $registration->mock_test_no }}</td>
                    <td class="text-center">
                        <a href="{{ route('mock_test_registrations.editioc', $registration) }}" class="btn btn-icon" title="@lang('Edit Registration')">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- Only Admin and Super Admin roles can see the delete button -->
                         @if (in_array(auth()->user()->role_id, [1, 3])) 
                        <a href="{{ route('mock_test_registrations.destroy', $registration) }}" class="btn btn-icon" title="@lang('Delete Registration')" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure?')" data-confirm-delete="@lang('Yes, delete it!')">
                    <i class="fas fa-trash"></i>
                        </a>
                        @endif
                        <a href="{{ route('mock_test_registrations.token', $registration) }}" class="btn btn-icon" title="@lang('Generate Token')">
                            <i class="fas fa-print"></i>
                        </a>

                        <a href="{{ route('mock_test_registrations.email', $registration) }}" class="btn btn-icon" title="@lang('Send Email')">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">@lang('No records found for the selected filters.')</td>
                </tr>
            @endforelse
        </tbody>
</table>

</div>

    </div>
</div>

@stop

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function () {
    // Initialize Tooltips
    //$('[data-toggle="tooltip"]').tooltip();

    $('#registrations-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'csvHtml5', title: 'Mock Test Registrations' },
            { extend: 'excelHtml5', title: 'Mock Test Registrations' },
            { extend: 'pdfHtml5', title: 'Mock Test Registrations', orientation: 'landscape', pageSize: 'A4' },
            { extend: 'print', title: 'Mock Test Registrations' }
        ],
        responsive: true,
        pageLength: 20,
        order: [[0, 'desc']],
        language: {
            emptyTable: "@lang('No records found for the selected filters.')", // Custom empty table message
        },
    });
});
</script>
@endsection


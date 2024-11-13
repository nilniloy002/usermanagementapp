@extends('layouts.app')

@section('page-title', __('Admissions'))
@section('page-heading', __('Admissions'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Admissions')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('admissions.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Admission')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="admissions-table-wrapper">
                <table class="table table-striped table-borderless" id="admissions-table">
                    <thead>
                    <tr>
                        <th class="min-width-100">@lang('Photo')</th>
                        <th class="min-width-100">@lang('Student ID')</th>
                        <th class="min-width-100">@lang('Admission Date')</th>
                        <th class="min-width-100">@lang('Student Details')</th>
                        <th class="min-width-100">@lang('Course')</th>
                        <th class="min-width-100">@lang('Batch Code')</th> <!-- Add this line -->
                        <!-- <th class="min-width-100">@lang('Amount Details')</th> -->
                        <th class="text-center">@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($admissions))
                        @foreach ($admissions as $admission)
                            <tr>
                                <td>
                                    @if($admission->photo)
                                        <img src="{{ asset($admission->photo) }}" alt="Photo" class="img-thumbnail" width="50" height="50">
                                    @else
                                        No Photo
                                    @endif
                                </td>
                                <!-- <td>{{ $admission->bill_id }}</td> -->
                                <td><a href="{{ route('admissions.show', $admission) }}">
                                {{ $admission->bill_id }}
                                    </a></td>
                                <td>{{ date("d-m-Y", strtotime($admission->admission_date))}}</td>
                               
                                <td>{{ $admission->student_name }}<br>
                                Mobile:{{ $admission->phone_number }}<br>
                                Guardian Mobile:{{ $admission->guardian_phone_number }}<br>
                                </td>
                                <td>{{ $admission->course->course_name}}</td>
                                <td>{{ $admission->batch_code }}</td> <!-- Add this line -->
                                <!-- <td><b>Total Fee:</b> {{ $admission->course->course_fee + $admission->course->admission_fee}}
                                @foreach($admission->payments as $payment)
                                </br><b>Discount:</b> {{ $payment->discount_amount }} 
                                @endforeach
                                @foreach($admission->payments as $payment)
                                </br><b style="color:green;">Paid:</b> {{ $payment->paid_amount }}
                                @endforeach -->

                                <!-- @foreach($admission->payments as $payment)
                                </br><b style="color:red;">Due:</b> {{  ($admission->course->course_fee + $admission->course->admission_fee)-($payment->paid_amount+$payment->discount_amount)}}
                                @endforeach -->

                                <!-- </td> -->
                                
                                <td class="text-center">
                                    <a href="{{ route('admissions.show', $admission) }}" class="btn btn-icon" title="@lang('View Details')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admissions.edit', $admission) }}" class="btn btn-icon"
                                       title="@lang('Edit Admission')" data-toggle="tooltip" data-placement="top">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admissions.destroy', $admission) }}" class="btn btn-icon"
                                       title="@lang('Delete Admission')"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       data-method="DELETE"
                                       data-confirm-title="@lang('Please Confirm')"
                                       data-confirm-text="@lang('Are you sure that you want to delete this admission?')"
                                       data-confirm-delete="@lang('Yes, delete it!')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2"><em>@lang('No Admission record found.')</em></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @section('scripts')
    <script>
       $(document).ready(function () {
    $('#admissions-table').DataTable({
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
        autoPrint: true  // Automatically trigger the print dialog
    });
});

    </script>
@endsection
@stop

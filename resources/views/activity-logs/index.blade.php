@extends('layouts.app')

@section('page-title', __('Activity Logs'))
@section('page-heading', __('Activity Logs'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Activity Logs')
    </li>
@stop

@section('content')
    @include('partials.messages')

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-filter mr-2"></i>Filter Activity Logs
                <button class="btn btn-sm btn-link float-right" type="button" data-toggle="collapse" data-target="#filterCollapse">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </h6>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form action="{{ route('activity-logs.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <!-- Log Name Filter -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="log_name" class="form-label">Log Type</label>
                                <select class="form-control select2" id="log_name" name="log_name">
                                    <option value="">All Types</option>
                                    <option value="student_activity" {{ request('log_name') == 'student_activity' ? 'selected' : '' }}>Student Activity</option>
                                    <option value="auth" {{ request('log_name') == 'auth' ? 'selected' : '' }}>Authentication</option>
                                    <option value="system" {{ request('log_name') == 'system' ? 'selected' : '' }}>System</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- User Filter -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="user_id" class="form-label">User</label>
                                <select class="form-control select2" id="user_id" name="user_id">
                                    <option value="">All Users</option>
                                    @foreach($users ?? [] as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Search -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search" class="form-label">Search</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Search description..." 
                                           value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="col-md-2">
                            <div class="form-group d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary" title="Apply Filters">
                                        <i class="fas fa-filter mr-1"></i> Apply
                                    </button>
                                    <a href="{{ route('activity-logs.index') }}" 
                                       class="btn btn-secondary" title="Reset Filters">
                                        <i class="fas fa-redo mr-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <hr class="my-2">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" 
                                       value="{{ request('date_from') }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" 
                                       value="{{ request('date_to') }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group d-flex align-items-end">
                                <button type="button" class="btn btn-outline-info" id="clearDates">
                                    <i class="fas fa-calendar-times mr-1"></i> Clear Dates
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-history mr-2 text-primary"></i>Activity Logs
                        <span class="badge bg-primary">{{ $logs->total() }}</span>
                    </h5>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-borderless" id="activity-logs-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->created_at->format('d-m-Y') }}</strong><br>
                                    <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @if($log->causer)
                                        <strong>{{ $log->causer->first_name ?? 'System' }}</strong><br>
                                        <small class="text-muted">{{ $log->causer->email ?? '' }}</small>
                                    @else
                                        <span class="badge badge-secondary">System</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ 
                                        str_contains($log->description, 'deleted') ? 'danger' : 
                                        (str_contains($log->description, 'downloaded') ? 'info' : 
                                        (str_contains($log->description, 'viewed') ? 'success' : 'secondary')) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->log_name)) }}
                                    </span>
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    {{ $log->ip_address ?? 'N/A' }}<br>
                                    <small class="text-muted">{{ $log->user_agent ? $log->user_agent : 'N/A' }}</small>
                                </td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info" 
                                            data-toggle="modal" 
                                            data-target="#logDetailsModal{{ $log->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>

                                    <!-- Modal for log details -->
                                    <div class="modal fade" id="logDetailsModal{{ $log->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-info-circle mr-2"></i>Log Details
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow: auto;">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-history fa-3x mb-3"></i>
                                        <h5>No activity logs found</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($logs->hasPages())
                <div class="row mt-3">
                    <div class="col-md-12">
                        {{ $logs->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Clear search button
        $('#clearSearch').click(function() {
            $('#search').val('');
            $('#filterForm').submit();
        });

        // Clear date range button
        $('#clearDates').click(function() {
            $('#date_from').val('');
            $('#date_to').val('');
            $('#filterForm').submit();
        });

        // Date validation
        $('#date_from, #date_to').on('change', function() {
            const dateFrom = $('#date_from').val();
            const dateTo = $('#date_to').val();
            
            if (dateFrom && dateTo && dateFrom > dateTo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Date Range Error',
                    text: 'From Date cannot be greater than To Date',
                    confirmButtonText: 'OK'
                });
                $(this).val('');
            }
        });

        // Auto-refresh every 30 seconds (optional)
        setInterval(function() {
            $('#filterForm').submit();
        }, 30000);
    });
</script>

<style>
.modal pre {
    white-space: pre-wrap;
    word-wrap: break-word;
    font-size: 11px;
}
.badge {
    font-size: 0.75em;
}
.table td {
    vertical-align: middle;
}
</style>
@endsection
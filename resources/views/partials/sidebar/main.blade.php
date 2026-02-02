<nav class="col-md-2 sidebar">
   <div class="sidebar-sticky">
      <ul class="nav flex-column">
         @foreach (\Vanguard\Plugins\Vanguard::availablePlugins() as $plugin)
         @include('partials.sidebar.items', ['item' => $plugin->sidebar()])
         @endforeach
         <!-- Start Mock Registration section -->
         @if (in_array(auth()->user()->role_id, [1, 3, 4])) {{-- Admin, SuperAdmin, OperationTeam --}}
         <!-- <li class="nav-item"><a class="nav-link" href="{{ route('departments.index') }}"><i class="fa fa-building"></i><span>Departments</span></a></li> -->
         <!-- <li class="nav-item"><a class="nav-link" href="{{ route('employees.index') }}"><i class="fa fa-building"></i><span>Employees</span></a></li> -->
      
        <li class="nav-item">
            <a class="nav-link collapsed" href="#admission-dropdown" data-toggle="collapse" aria-expanded="false">
                <i class="fa fa-university"></i>
                <span>Admissions</span>
            </a>
            <ul class="list-unstyled sub-menu collapse" id="admission-dropdown" style="">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student-admissions.pending-student-index') }}">
                        <i class="fas fa-user-clock"></i>
                        <span>Pending Admission</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student-admissions.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Admission List</span>
                    </a>
                </li>
                <!-- Payment Section -->
                <li class="nav-item">
                    <hr class="my-1">
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student-admissions.payment-invoice-form') }}">
                        <i class="fa fa-file text-success"></i>
                        <span class="text-success">Create Payment Invoice</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student-admissions.payment-invoices') }}">
                        <i class="fas fa-list-alt text-info"></i>
                        <span class="text-info">Payment Invoices</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('*daily-revenue*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('student-admissions.daily-revenue') }}">
                    <i class="fas fa-money-bill-wave nav-icon"></i>
                    <p>@lang('Daily Revenue')</p>
                </a>
            </li>
            </ul>
        </li>
         @if (in_array(auth()->user()->role_id, [1, 3])) {{-- Admin, SuperAdmin --}}
         <li class="nav-item">
            <a class="nav-link collapsed" href="#reports-dropdown" data-toggle="collapse" aria-expanded="false">
               <i class="fas fa-file"></i>
                <span>Reports</span>
            </a>
            <ul class="list-unstyled sub-menu collapse" id="reports-dropdown" style="">
             

                <li class="nav-item {{ request()->is('*daily-revenue*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('student-admissions.daily-revenue') }}">
                    <i class="fas fa-money-bill-wave nav-icon"></i>
                    <span>@lang('Daily Revenue')</span>
                </a>
            </li>
            </ul>
        </li>

         <li class="nav-item">
            <a class="nav-link collapsed" href="#course-dropdown" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-cogs"></i>
            <span>Courses</span>
            </a>
            <ul class="list-unstyled sub-menu collapse" id="course-dropdown" style="">
               <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}"><i class="fa fa-building"></i><span>All Courses</span></a></li>
               <li class="nav-item"><a class="nav-link" href="{{ route('courses.create') }}"><i class="fa fa-building"></i><span>Add Course</span></a></li>
            </ul>
         </li>
         <li class="nav-item">
            <a class="nav-link collapsed" href="#batch-dropdown" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-cogs"></i>
            <span>Batches</span>
            </a>
            <ul class="list-unstyled sub-menu collapse" id="batch-dropdown" style="">
               <li class="nav-item"><a class="nav-link" href="{{ route('batches.index') }}"><i class="fa fa-building"></i><span>All Batches</span></a></li>
               <li class="nav-item"><a class="nav-link" href="{{ route('batches.create') }}"><i class="fa fa-building"></i><span>Add Batch</span></a></li>
            </ul>
         </li> 
         <li class="nav-item">
         <a class="nav-link collapsed" href="#mockreg-dropdown" data-toggle="collapse" aria-expanded="false">
         <i class="fa fa-file"></i>
         <span>Mock Registration</span>
         </a>
         <ul class="list-unstyled sub-menu collapse" id="mockreg-dropdown" style="">
            <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_registrations.index') }}"><i class="fa fa-plus"></i><span>Book IoP Mock Test</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_registrations.indexioc') }}"><i class="fa fa-plus"></i><span>Book IoC Mock Test</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_dates.index') }}"><i class="fa fa-calendar"></i><span>Mock Dates</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_time_slots.index') }}"><i class="fa fa-clock"></i><span>Time Slot</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_rooms.index') }}"><i class="fa fa-building"></i><span>Speaking Rooms</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_statuses.index') }}"><i class="fa fa-comment"></i><span>Mock Status</span></a></li>
         </ul>
         </li>
         @endif
         <!-- End Mock Registration section -->
         <!-- Start Mock Test section -->
         @if (in_array(auth()->user()->role_id, [1, 3, 2])) {{-- Admin, SuperAdmin, IELTS Team --}}
         <li class="nav-item">
            <a class="nav-link collapsed" href="#mocktest-dropdown" data-toggle="collapse" aria-expanded="false">
            <i class="fa fa-university"></i>
            <span>Mock Test</span>
            </a>
            <ul class="list-unstyled sub-menu collapse" id="mocktest-dropdown" style="">
               <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_results.index') }}"><i class="fas fa-users"></i><span>Result List</span></a></li>
               <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_results.import_form') }}"><i class="fa fa-plus"></i><span>Import Result</span></a></li>
               <li class="nav-item"><a class="nav-link" href="{{ route('another_days.index') }}"><i class="fas fa-users"></i><span>Speaking List (AD)</span></a></li>
               <li class="nav-item"><a class="nav-link" href="{{ route('another_days.import_form') }}"><i class="fa fa-plus"></i><span>Import Speaking(AD)</span></a></li>
               <li class="nav-item"><a class="nav-link" href="{{ route('another_days.email.report') }}"><i class="fa fa-envelope"></i><span>Track Email</span></a></li>
            </ul>
         </li>
         @endif
         <!-- End Mock Test section -->
      </ul>
   </div>
</nav>
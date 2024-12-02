<nav class="col-md-2 sidebar">
    <!-- <div class="user-box text-center pt-5 pb-3">
        <div class="user-img">
            <img src="{{ auth()->user()->present()->avatar }}"
                 width="90"
                 height="90"
                 alt="user-img"
                 class="rounded-circle img-thumbnail img-responsive">
        </div>
        <h5 class="my-3">
            <a href="{{ route('profile') }}">{{ auth()->user()->present()->nameOrEmail }}</a>
        </h5>

        <ul class="list-inline mb-2">
            <li class="list-inline-item">
                <a href="{{ route('profile') }}" title="@lang('My Profile')">
                    <i class="fas fa-cog"></i>
                </a>
            </li>

            <li class="list-inline-item">
                <a href="{{ route('auth.logout') }}" class="text-custom" title="@lang('Logout')">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>

        </ul>
    </div> -->

    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            @foreach (\Vanguard\Plugins\Vanguard::availablePlugins() as $plugin)
                @include('partials.sidebar.items', ['item' => $plugin->sidebar()])
            @endforeach
                <!-- <li class="nav-item"><a class="nav-link" href="{{ route('departments.index') }}"><i class="fa fa-building"></i><span>Departments</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('employees.index') }}"><i class="fa fa-building"></i><span>Employees</span></a></li> -->
                <!-- <li class="nav-item">
    <a class="nav-link collapsed" href="#admission-dropdown" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-university"></i>

        <span>Admissions</span>
    </a>

        <ul class="list-unstyled sub-menu collapse" id="admission-dropdown" style="">
        <li class="nav-item"><a class="nav-link" href="{{ route('admissions.index') }}"><i class="fas fa-users"></i><span>Admission List</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admissions.create') }}"><i class="fa fa-plus"></i><span>New Admission</span></a></li>
        </ul>
    </li> -->

    <!-- <li class="nav-item">
    <a class="nav-link collapsed" href="#course-dropdown" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-cogs"></i>

        <span>Courses</span>
    </a>

    <ul class="list-unstyled sub-menu collapse" id="course-dropdown" style="">
        <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}"><i class="fa fa-building"></i><span>All Courses</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('courses.create') }}"><i class="fa fa-building"></i><span>Add Course</span></a></li>
     </ul>

      
    </li> -->

    <!-- <li class="nav-item">
    <a class="nav-link collapsed" href="#batch-dropdown" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-cogs"></i>

        <span>Batches</span>
    </a>

    <ul class="list-unstyled sub-menu collapse" id="batch-dropdown" style="">
        <li class="nav-item"><a class="nav-link" href="{{ route('batches.index') }}"><i class="fa fa-building"></i><span>All Batches</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('batches.create') }}"><i class="fa fa-building"></i><span>Add Batch</span></a></li>
     </ul>

      
    </li> -->

    <li class="nav-item">
    <a class="nav-link collapsed" href="#mockreg-dropdown" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-file"></i>

        <span>Mock Registration</span>
    </a>

        <ul class="list-unstyled sub-menu collapse" id="mockreg-dropdown" style="">
        <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_registrations.index') }}"><i class="fa fa-plus"></i><span>Book Mock Test</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_dates.index') }}"><i class="fa fa-calendar"></i><span>Mock Dates</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_time_slots.index') }}"><i class="fa fa-clock"></i><span>Time Slot</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_rooms.index') }}"><i class="fa fa-building"></i><span>Speaking Rooms</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_statuses.index') }}"><i class="fa fa-comment"></i><span>Mock Status</span></a></li>
       
    </li>
        </ul>

    <li class="nav-item">
    <a class="nav-link collapsed" href="#mocktest-dropdown" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-university"></i>

        <span>Mock Test</span>
    </a>

        <ul class="list-unstyled sub-menu collapse" id="mocktest-dropdown" style="">
        <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_results.index') }}"><i class="fas fa-users"></i><span>Result List</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('mock_test_results.import_form') }}"><i class="fa fa-plus"></i><span>Import Result</span></a></li>
        </ul>
    </li>
        </ul>
    </div>
</nav>





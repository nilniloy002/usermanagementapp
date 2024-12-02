<nav class="col-md-2 sidebar">

    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            @foreach (\Vanguard\Plugins\Vanguard::availablePlugins() as $plugin)
                @include('partials.sidebar.items', ['item' => $plugin->sidebar()])
            @endforeach
             
            <!-- Start Mock Registration section -->
            @if (in_array(auth()->user()->role_id, [1, 3, 4])) {{-- Admin, SuperAdmin, OperationTeam --}}
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
                </ul>
            </li>
            @endif
            <!-- End Mock Test section -->
        </ul>
    </div>
</nav>

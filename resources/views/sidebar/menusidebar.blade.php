<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                {{-- Dashboard (Semua user) --}}
                <li class="{{ set_active(['home']) }}">
                    <a href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
                </li>
                <li class="list-divider"></li>

                {{-- Booking Room (Semua user) --}}
                <li class="submenu">
                    <a href="#"><i class="fas fa-suitcase"></i> <span> Booking Room </span> <span
                            class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['form/allbooking']) }}" href="{{ route('form/allbooking') }}">
                                Bookings List </a></li>
                        <li><a class="{{ set_active(['form/booking/calendar']) }}" href="{{ route('form/booking/calendar') }}">
                                Book Now </a></li>
                    </ul>
                </li>

                @if (auth()->user()->isSuperAdmin())
                    {{-- Rooms (Admin Only) --}}
                    <li class="submenu">
                        <a href="#"><i class="fas fa-key"></i> <span> Rooms </span> <span
                                class="menu-arrow"></span></a>
                        <ul class="submenu_class" style="display: none;">
                            <li><a class="{{ set_active(['form/allrooms/page']) }}"
                                    href="{{ route('form/allrooms/page') }}">All Rooms </a></li>
                            <li><a class="{{ set_active(['form/addroom/page']) }}"
                                    href="{{ route('form/addroom/page') }}"> Add Room </a></li>
                        </ul>
                    </li>

                    {{-- User Management (Admin Only) --}}
                    <li class="submenu">
                        <a href="#">
                            <i class="fa fa-user-plus"></i>
                            <span> User Management </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="submenu_class" style="display: none;">
                            <li><a class="{{ set_active(['users/add/new']) }}" href="{{ route('users/add/new') }}">Add
                                    User</a></li>
                            <li><a class="{{ set_active(['users/list/page']) }}"
                                    href="{{ route('users/list/page') }}">All User</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>

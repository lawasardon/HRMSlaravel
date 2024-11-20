<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>
                @hasrole('admin|hr')
                    <li class="submenu {{ request()->routeIs('home') ? 'active' : '' }}">
                        <a href="#" @click.prevent="goToDashboard">
                            <i class="feather-grid"></i> <span> Dashboard</span> <span class="menu-arrow"></span>
                        </a>
                        {{-- <a href="/"><i class="feather-grid"></i> <span> Dashboard</span> <span
                                class="menu-arrow"></span></a> --}}
                        {{-- <ul>
                            <li><a href="index.html">Admin Dashboard</a></li>
                            <li><a href="teacher-dashboard.html">Teacher Dashboard</a></li>
                            <li><a href="student-dashboard.html">Student Dashboard</a></li>

                            <li><a href="teacher-dashboard.html">Teacher Dashboard</a></li>

                            <li><a href="student-dashboard.html">Student Dashboard</a></li>
                        </ul> --}}
                    </li>
                @endhasrole
                @hasrole('admin|hr')
                    <li
                        class="submenu
                        {{ request()->routeIs(
                            'show.aqua.employee.list',
                            'show.laminin.employee.list',
                            'laminin.add.employee',
                            'aqua.add.employee',
                        )
                            ? 'active'
                            : '' }}">
                        <a href="#"><i class="fas fa-building"></i> <span> Departments</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="{{ route('show.aqua.employee.list') }}"
                                    class="{{ request()->routeIs('show.aqua.employee.list') ? 'active' : '' }}">
                                    Aqua Department
                                </a>
                            </li>
                            <li><a href="{{ route('show.laminin.employee.list') }}"
                                    class="{{ request()->routeIs('show.laminin.employee.list') ? 'active' : '' }}">Laminin
                                    Department</a></li>
                            @if (request()->is('laminin/add/employee', 'aqua/add/employee'))
                                <li><a href="#"
                                        class="{{ request()->is('laminin/add/employee', 'aqua/add/employee') ? 'active' : '' }}">Add
                                        Employee</a>
                                </li>
                            @else
                            @endif
                        </ul>
                    </li>
                @endhasrole
                {{-- @hasrole('admin|hr')
                    <li class="submenu">
                        <a href="#"><i class="fas fa-users"></i> <span> Employees</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="students.html">Student List</a></li>
                            <li><a href="student-details.html">Student View</a></li>
                            <li><a href="add-student.html">Student Add</a></li>
                            <li><a href="edit-student.html">Student Edit</a></li>
                        </ul>
                    </li>
                @endhasrole --}}
                @hasrole('hr')
                    <li
                        class="submenu {{ request()->routeIs('attendance.list.all.employee', 'attendance.list.aqua', 'attendance.list.laminin') ? 'active' : '' }}">
                        <a href="#"><i class="far fa-calendar-check"></i> <span> Attendance</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('attendance.list.all.employee') }}"
                                    class="{{ request()->routeIs('attendance.list.all.employee') ? 'active' : '' }}">List</a>
                            </li>
                            <li><a href="{{ route('attendance.list.aqua') }}"
                                    class="{{ request()->routeIs('attendance.list.aqua') ? 'active' : '' }}">Aqua</a></li>
                            <li><a href="{{ route('attendance.list.laminin') }}"
                                    class="{{ request()->routeIs('attendance.list.laminin') ? 'active' : '' }}">Laminin</a>
                            </li>
                            @if (request()->is('attendance/show/upload/page'))
                                <li><a href="#"
                                        class="{{ request()->is('attendance/show/upload/page') ? 'active' : '' }}">Upload
                                        Attendance</a>
                                </li>
                            @else
                            @endif
                        </ul>
                    </li>
                @endhasrole
                @hasrole('employee')
                    <li class="submenu {{ request()->routeIs('my.attendance', '') ? 'active' : '' }}">
                        <a href="#"><i class="far fa-calendar-check"></i> <span> Attendance</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('my.attendance') }}"
                                    class="{{ request()->routeIs('my.attendance') ? 'active' : '' }}">My Attendance</a>
                            </li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('admin|hr')
                    <li class="submenu {{ request()->routeIs('aqua.leave.list', 'laminin.leave.list') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-user-slash"></i> <span> Leave</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('aqua.leave.list') }}"
                                    class="{{ request()->routeIs('aqua.leave.list') ? 'active' : '' }}">Aqua
                                    Leave List</a></li>
                            <li><a href="{{ route('laminin.leave.list') }}"
                                    class="{{ request()->routeIs('laminin.leave.list') ? 'active' : '' }}">Laminin Leave
                                    List</a></li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('employee')
                    <li
                        class="submenu {{ request()->routeIs('employee.leave.list', 'employee.leave.create') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-user-slash"></i> <span> Leave</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('employee.leave.list') }}"
                                    class="{{ request()->routeIs('employee.leave.list') ? 'active' : '' }}">Leave Form</a>
                            </li>
                            @if (request()->is('leave/create'))
                                <li><a href="#" class="{{ request()->is('leave/create') ? 'active' : '' }}">File
                                        Leave</a>
                                </li>
                            @else
                            @endif
                        </ul>
                    </li>
                    <li class="submenu {{ request()->routeIs('create.loan', 'my.loans', '') ? 'active' : '' }}">
                        <a href="#"><i class="far fa-newspaper"></i> <span> Loan</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('create.loan') }}"
                                    class="{{ request()->routeIs('create.loan') ? 'active' : '' }}">Create a Loan</a>
                            </li>
                            <li><a href="{{ route('my.loans') }}"
                                    class="{{ request()->routeIs('my.loans') ? 'active' : '' }}">My Loans</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu {{ request()->routeIs('view.payslip', '') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-money-check-alt"></i> <span> Payslip</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('view.payslip') }}"
                                    class="{{ request()->routeIs('view.payslip') ? 'active' : '' }}">My Payslip</a>
                            </li>
                        </ul>
                    </li>
                @endhasrole
                {{-- @hasrole('admin')
                    <li class="submenu">
                        <a href="#"><i class="fas fa-book-reader"></i> <span> Projects</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="subjects.html">Subject List</a></li>
                            <li><a href="add-subject.html">Subject Add</a></li>
                            <li><a href="edit-subject.html">Subject Edit</a></li>
                        </ul>
                    </li>
                @endhasrole --}}
                @hasrole('hr')
                    <li
                        class="submenu {{ request()->routeIs('show.aqua.payroll', 'show.laminin.payroll', 'show.all.employee.rates') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-file-invoice-dollar"></i> <span> Payroll</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('show.all.employee.rates') }}"
                                    class="{{ request()->routeIs('show.all.employee.rates') ? 'active' : '' }}">Employee
                                    Rates</a></li>
                            <li><a href="{{ route('show.aqua.payroll') }}"
                                    class="{{ request()->routeIs('show.aqua.payroll') ? 'active' : '' }}">Aqua</a></li>
                            <li><a href="{{ route('show.laminin.payroll') }}"
                                    class="{{ request()->routeIs('show.laminin.payroll') ? 'active' : '' }}">Laminin</a>
                            </li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('admin|hr|employee')
                    {{-- <li class="submenu {{ request()->routeIs('create.loan', 'my.loans', '') ? 'active' : '' }}">
                        <a href="#"><i class="far fa-newspaper"></i> <span> Loan</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('create.loan') }}"
                                    class="{{ request()->routeIs('create.loan') ? 'active' : '' }}">Create a Loan</a>
                            </li>
                            <li><a href="{{ route('my.loans') }}"
                                    class="{{ request()->routeIs('my.loans') ? 'active' : '' }}">My Loans</a>
                            </li>
                        </ul>
                    </li> --}}
                @endhasrole
                @hasrole('hr')
                    <li
                        class="submenu {{ request()->routeIs('show.aqua.loans', 'show.laminin.loans', '') ? 'active' : '' }}">
                        <a href="#"><i class="far fa-newspaper"></i> <span> Loan</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('show.aqua.loans') }}"
                                    class="{{ request()->routeIs('show.aqua.loans') ? 'active' : '' }}">Acqua</a>
                            </li>
                            <li><a href="{{ route('show.laminin.loans') }}"
                                    class="{{ request()->routeIs('show.laminin.loans') ? 'active' : '' }}">Laminin</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu {{ request()->routeIs('show.create.news', 'all.news', '') ? 'active' : '' }}">
                        <a href="#"><i class="far fa-newspaper"></i> <span> News</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('all.news') }}"
                                    class="{{ request()->routeIs('all.news') ? 'active' : '' }}">All News</a>
                            </li>
                            <li><a href="{{ route('show.create.news') }}"
                                    class="{{ request()->routeIs('show.create.news') ? 'active' : '' }}">Create a Post</a>
                            </li>
                        </ul>
                    </li>
                @endhasrole
                {{-- @hasrole('admin|hr')
                    <li class="submenu">
                        <a href="#"><i class="fas fa-users"></i> <span> Assets</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="students.html">Student List</a></li>
                            <li><a href="student-details.html">Student View</a></li>
                            <li><a href="add-student.html">Student Add</a></li>
                            <li><a href="edit-student.html">Student Edit</a></li>
                        </ul>
                    </li>
                @endhasrole
                <li class="submenu">
                    <a href="#"><i class="fas fa-users"></i> <span> Notice</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="students.html">Student List</a></li>
                        <li><a href="student-details.html">Student View</a></li>
                        <li><a href="add-student.html">Student Add</a></li>
                        <li><a href="edit-student.html">Student Edit</a></li>
                    </ul>
                </li> --}}
                <li>
                    <a href="settings.html"><i class="fas fa-cog"></i> <span>Settings</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    new Vue({
        el: '#sidebar',
        methods: {
            goToDashboard() {
                window.location.href = '/';
            }
        }
    });
</script>

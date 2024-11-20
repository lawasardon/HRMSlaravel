@extends('layouts.main')
@section('content')
    <div class="content container-fluid" id="allEmployeeList">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">All Employee</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="students.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">All Employees</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="student-group-form">
            <div class="row justify-content-end">
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <input type="text" v-model="searchQuery" class="form-control" placeholder="Search by Name ...">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="search-student-btn">
                        <button type="button" class="btn btn-primary" @click="searchEmployee">Search</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">

                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">All Employee</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('attendance.show.upload.page') }}" class="btn btn-primary"><i
                                            class="fas fa-plus"></i> Upload Attendance</a>
                                    <a href="{{ route('attendance.downloadable.template') }}"
                                        class="btn btn-outline-primary me-2"><i class="fas fa-download"></i>
                                        Download Attendace Template</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table
                                class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Date</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Status</th>
                                        {{-- <th class="text-end">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Show "No results found" message if employeeList is empty -->
                                    <tr v-if="employeeList.length === 0">
                                        <td colspan="7" class="text-center">@{{ noResultsMessage || 'No results found.' }}</td>
                                    </tr>
                                    <!-- Show employees if available -->
                                    <tr v-for="data in employeeList" :key="data.id">
                                        <td>@{{ data.id_number }}</td>
                                        <td> @{{ data.name }} </td>
                                        <td>@{{ departmentName(data) }}</td>
                                        <td>@{{ formatDate(data.date) }}</td>
                                        <td>@{{ data.time_in }}</td>
                                        <td>@{{ formatTimeTo12Hour(data.time_out) }}</td>
                                        <td>
                                            <span :class="getAttendanceStatus(data.status)">
                                                @{{ data.status }}
                                            </span>
                                        </td>
                                        {{-- <td class="text-end">
                                            <div class="actions">
                                                <a href="javascript:;" class="btn btn-sm bg-success-light me-2">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="edit-student.html" class="btn btn-sm bg-danger-light">
                                                    <i class="feather-edit"></i>
                                                </a>
                                            </div>
                                        </td> --}}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        new Vue({
            el: '#allEmployeeList',
            data: {
                employeeList: [],
                searchQuery: '', // Store the search query
                noResultsMessage: '', // Store the no results message
            },
            mounted() {
                this.allEmployeeOfAqua();
            },
            methods: {
                allEmployeeOfAqua() {
                    axios.get("{{ route('attendance.list.all.employee.data') }}")
                        .then(response => {
                            this.employeeList = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching employee', error.response ? error.response.data :
                                error);
                        });
                },
                searchEmployee() {
                    this.noResultsMessage = ''; // Reset the no results message before search

                    axios.post("{{ route('attendance.search.all.employee') }}", {
                            searchQuery: this.searchQuery
                        })
                        .then(response => {
                            if (response.data.length === 0) {
                                this.noResultsMessage = 'No results found.'; // Set the no results message
                                this.employeeList = []; // Clear the employee list
                            } else {
                                this.employeeList = response.data; // Populate with the search results
                            }
                        })
                        .catch(error => {
                            console.error('Error during search', error.response ? error.response.data : error);
                        });
                },
                getAttendanceStatus(status) {
                    switch (status) {
                        case 'Not Late':
                            return 'badge badge-success';
                        case 'Late':
                            return 'badge badge-danger';
                        default:
                            return '';
                    }
                },
                formatDate(dateString) {
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString('en-US', options);
                },

                formatTimeTo12Hour(timeString) {
                    const timeParts = timeString.split(':');
                    const date = new Date();
                    date.setHours(timeParts[0]);
                    date.setMinutes(timeParts[1]);

                    const options = {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    };
                    return date.toLocaleTimeString('en-US', options);
                },

                departmentName(data) {
                    switch (data.department) {
                        case 'aqua':
                            return 'Acqua';
                        default:
                            return data.department;
                    }
                }
            }
        });
    </script>
@endpush

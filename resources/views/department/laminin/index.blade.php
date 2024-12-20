@extends('layouts.main')
@section('content')
    <div class="content container-fluid" id="lamininEmployeeList">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Laminin Department</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="students.html">Laminin Department</a></li>
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
                                    <h3 class="page-title">Employees</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    {{-- <a href="students.html" class="btn btn-outline-gray me-2 active"><i
                                            class="feather-list"></i></a>
                                    <a href="students-grid.html" class="btn btn-outline-gray me-2"><i
                                            class="feather-grid"></i></a>
                                    <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i>
                                        Download</a> --}}
                                    @if (Auth::user()->hasRole('hr'))
                                        <a href="{{ route('laminin.add.employee') }}" class="btn btn-primary"><i
                                                class="fas fa-plus"></i> Add Employee</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table
                                class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        {{-- <th>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" value="something">
                                            </div>
                                        </th> --}}
                                        <th>ID</th>
                                        <th>Department</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Birthday</th>
                                        <th>Religion</th>
                                        {{-- <th class="text-end">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Show "No results found" message if employeeList is empty -->
                                    <tr v-if="employeeList.length === 0">
                                        <td colspan="8" class="text-center">@{{ noResultsMessage || 'No results found.' }}</td>
                                    </tr>
                                    <!-- Show employees if available -->
                                    <tr v-for="data in employeeList" :key="data.id">
                                        {{-- <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" value="something">
                                            </div>
                                        </td> --}}
                                        <td>@{{ data.id_number }}</td>
                                        <td>@{{ data.department.name }}
                                        </td>
                                        <td>@{{ data.name }}</td>
                                        <td>@{{ data.email }}</td>
                                        <td>@{{ data.address }}</td>
                                        <td>@{{ data.phone }}</td>
                                        <td>@{{ data.gender }}</td>
                                        <td>@{{ data.birthday }}</td>
                                        <td>@{{ data.religion }}</td>
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
            el: '#lamininEmployeeList',
            data: {
                employeeList: [],
                searchQuery: '', // Store the search query
                noResultsMessage: '', // Store the no results message
            },
            mounted() {
                this.allEmployeeOfLaminin();
            },
            methods: {
                allEmployeeOfLaminin() {
                    axios.get("{{ route('laminin.employee.list.data') }}")
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

                    axios.post("{{ route('department.laminin.search') }}", {
                            searchQuery: this.searchQuery
                        })
                        .then(response => {
                            console.log(response.data); // Log response to check if the correct data is returned
                            if (response.data.length === 0) {
                                this.noResultsMessage = 'No results found.';
                                this.employeeList = [];
                            } else {
                                this.employeeList = response.data;
                            }
                        })
                        .catch(error => {
                            console.error('Error during search', error.response ? error.response.data : error);
                        });
                }

            }
        });
    </script>
@endpush

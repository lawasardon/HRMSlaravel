@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="lamininLoans">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Laminin Loans</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laminin Loans</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Laminin Loans</h3>
                                </div>
                                @hasrole('employee')
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('employee.leave.create') }}" class="btn btn-primary"><i
                                                class="fas fa-plus"></i> Add New</a>
                                    </div>
                                @endhasrole
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table
                                class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>ID Number</th>
                                        <th>Date Filed</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Interest</th>
                                        <th>Total</th>
                                        <th>Terms of Loan</th>
                                        <th>Reason of Loan</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in loanList" :key="data.id">
                                        <td>@{{ data.id_number }}</td>
                                        <td>@{{ formatDate(data.date_filed) }}</td>
                                        <td>@{{ data.employee.name }}</td>
                                        <td>@{{ data.amount }}</td>
                                        <td>@{{ data.interest }}</td>
                                        <td>@{{ data.total }}</td>
                                        <td>@{{ data.terms_of_loan }}</td>
                                        <td>@{{ data.reason_of_loan }}</td>
                                        <td>
                                            <span :class="getStatusClass(data.status, data.remarks)">
                                                @{{ getStatusText(data.status, data.remarks) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span :class="getRemarksClass(data.status, data.remarks)">
                                                @{{ getRemarksText(data.status, data.remarks) }}
                                            </span>
                                        </td>
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
            el: '#lamininLoans',
            data: {
                loanList: [],
            },
            mounted() {
                this.lamininLoansList();
            },
            methods: {
                lamininLoansList() {
                    axios.get("{{ route('show.laminin.loans.data') }}")
                        .then(response => {
                            this.loanList = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching loan list', error.response ? error.response.data :
                                error);
                        });
                },

                getStatusClass(status, remarks) {
                    if (status === 'pending' && remarks === 'pending') {
                        return 'badge badge-warning';
                    } else if (status === 'approved' && remarks === 'pending') {
                        return 'badge badge-success';
                    } else if (status === 'approved' && remarks === 'paid') {
                        return 'badge badge-success';
                    } else {
                        return 'badge badge-danger';
                    }
                },

                getStatusText(status, remarks) {
                    if (status === 'pending' && remarks === 'pending') {
                        return 'Pending';
                    } else if (status === 'approved' && remarks === 'pending') {
                        return 'Approved';
                    } else if (status === 'approved' && remarks === 'paid') {
                        return 'Approved';
                    } else {
                        return 'Rejected';
                    }
                },

                getRemarksText(status, remarks) {

                    if (status === 'pending' && remarks === 'pending') {
                        return 'Pending';
                    } else if (status === 'approved' && remarks === 'pending') {
                        return 'Not Fully Paid';
                    } else if (status === 'approved' && remarks === 'paid') {
                        return 'Paid';
                    } else {
                        return 'Rejected';
                    }
                },

                getRemarksClass(status, remarks) {
                    if (status === 'pending' && remarks === 'pending') {
                        return 'badge badge-warning';
                    } else if (status === 'approved' && remarks === 'pending') {
                        return 'badge badge-warning';
                    } else if (status === 'approved' && remarks === 'paid') {
                        return 'badge badge-success';
                    } else {
                        return 'badge badge-danger';
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
            }
        });
    </script>
@endpush

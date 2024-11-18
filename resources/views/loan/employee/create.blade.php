@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="storeLoan">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Create Loan</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="departments.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Create Loan</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="submitLoan">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Loan Form</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Date Filed <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="date_filed" required readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="name" required readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" v-model="amount" required
                                            @input="calculateInterestAndTotal">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Interest <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" v-model="interest" required readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Total <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" v-model="total" required readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Terms of Loan <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="terms_of_loan" required>
                                            <option value="" disabled>Select Terms of Loan</option>
                                            <option value="1">1 Month</option>
                                            <option value="2">2 Months</option>
                                            <option value="3">3 Months</option>
                                            <option value="4">4 Months</option>
                                            <option value="5">5 Months</option>
                                            <option value="6">6 Months</option>
                                            <option value="7">7 Months</option>
                                            <option value="8">8 Months</option>
                                            <option value="9">9 Months</option>
                                            <option value="10">10 Months</option>
                                            <option value="11">11 Months</option>
                                            <option value="12">12 Months</option>
                                            <option value="9">9 Months</option>
                                            <option value="12">12 Months</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Deduction per Salary <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" v-model="deduction_per_salary" required
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Reason of Loan <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="reason_of_loan" required>
                                            <option value="" disabled>Select Reason of Loan</option>
                                            <option value="emergency loan">Emergency loan</option>
                                            <option value="medical loan">Medical loan</option>
                                            <option value="personal loan">Personal Loan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        new Vue({
            el: '#storeLoan',
            data: {
                date_filed: new Date().toISOString().split('T')[0],
                name: '{{ auth()->user()->name }}', // Assuming 'name' is in the authenticated user object.
                amount: '',
                interest: 0,
                total: 0,
                reason_of_loan: '',
                terms_of_loan: '',
                deduction_per_salary: '',
            },

            watch: {
                // Add watchers to recalculate deduction when either total or terms change
                total: function() {
                    this.calculateDeductionPerSalary();
                },
                terms_of_loan: function() {
                    this.calculateDeductionPerSalary();
                }
            },

            methods: {
                calculateInterestAndTotal() {
                    if (this.amount > 0) {
                        this.interest = (this.amount * 0.06).toFixed(2);
                        this.total = (parseFloat(this.amount) + parseFloat(this.interest)).toFixed(2);
                    } else {
                        this.interest = 0;
                        this.total = 0;
                    }
                    this.calculateDeductionPerSalary(); // Also recalculate deduction when amount changes
                },

                calculateDeductionPerSalary() {
                    if (this.total > 0 && this.terms_of_loan) {
                        this.deduction_per_salary = (this.total / this.terms_of_loan).toFixed(2);
                    } else {
                        this.deduction_per_salary = '';
                    }
                },

                submitLoan() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    axios.post("{{ route('store.loan') }}", {
                            date_filed: this.date_filed,
                            amount: this.amount,
                            interest: this.interest,
                            reason_of_loan: this.reason_of_loan,
                            terms_of_loan: this.terms_of_loan,
                            deduction_per_salary: this.deduction_per_salary,
                            total: this.total,
                        })
                        .then(response => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Loan Submitted Successfully',
                                text: response.data.message,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then(() => {
                                window.location.href =
                                    `/my/loans`;
                            });
                        })
                        .catch(error => {
                            console.error('Error submitting loan', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'An error occurred',
                                text: error.response?.data?.message ||
                                    'An error occurred while processing the loan.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            });
                        });
                },
            }
        });
    </script>
@endpush

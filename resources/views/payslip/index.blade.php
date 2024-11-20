@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="payslipPage">
        <div class="page-header">
            <h3 class="page-title">Payslip</h3>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <table class="table table-striped"> <!-- Corrected class name -->
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="payslip in payslips" :key="payslip.id">
                                    <td>@{{ payslip.duration }}</td> <!-- Removed space between payslip. and period -->
                                    <td>
                                        <button @click="viewPayslip(payslip)" class="btn btn-primary">
                                            View Payslip
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <x-modal submitMethod="printPayslip" modalId="viewPayslipModal" title="Payslip Details" submitId="downloadPayslip"
            submitText="Download PDF">
            <div class="form-group" v-if="selectedPayslip">
                <label class="col-form-label">Period:</label>
                <input type="text" class="form-control" :value="selectedPayslip.duration" disabled>

                <label class="col-form-label">Salary:</label>
                <input type="text" class="form-control" :value="selectedPayslip.salary" disabled>

                <label class="col-form-label">Deductions:</label>
                <input type="text" class="form-control" :value="selectedPayslip.total_deduction" disabled>
            </div>
        </x-modal>

    </div>
@endsection

@push('js')
    <script>
        new Vue({
            el: '#payslipPage',
            data() {
                return {
                    payslips: [],
                    selectedPayslip: null
                }
            },
            mounted() {
                this.fetchPayslips()
            },
            methods: {
                fetchPayslips() {
                    axios.get("{{ route('payslip.data') }}")
                        .then(response => {
                            this.payslips = response.data
                        })
                        .catch(error => {
                            console.error('Error fetching payslips:', error)
                        })
                },
                viewPayslip(payslip) {
                    this.selectedPayslip = payslip;
                    $('#viewPayslipModal').modal('show');
                },
                closeModal() {
                    this.selectedPayslip = null;
                    $('#viewPayslipModal').modal('hide');
                },
                printPayslip() {
                    axios.get("{{ route('download.payslip') }}", {
                            params: {
                                duration: this.selectedPayslip.duration
                            },
                            responseType: 'blob'
                        })
                        .then(response => {
                            const url = window.URL.createObjectURL(new Blob([response.data]))
                            const link = document.createElement('a')
                            link.href = url
                            link.setAttribute('download', `payslip_${this.selectedPayslip.duration}.pdf`)
                            document.body.appendChild(link)
                            link.click()
                        })
                        .catch(error => {
                            console.error('Error downloading payslip:', error)
                        })
                }
            }
        });
    </script>
@endpush

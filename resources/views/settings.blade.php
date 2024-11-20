@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="profileDetailsForm">

        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Profile</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-auto profile-image">
                            <a href="#">
                                <!-- Dynamically set the image URL based on the fetched data -->
                                <img class="rounded-circle" alt="User Image"
                                    :src="getImageUrl(accountDetails.profile_picture)" width="50">
                            </a>
                        </div>
                        <div class="col ms-md-n2 profile-user-info">
                            <!-- Dynamically set user name and email -->
                            <h4 class="user-name mb-0">@{{ accountDetails.name }}</h4>
                            <h6 class="text-muted">@{{ accountDetails.email }}</h6>
                            <div class="user-Location"><i class="fas fa-map-marker-alt"></i> @{{ accountDetails.location }}
                            </div>
                            <div class="about-text">@{{ accountDetails.about }}</div>
                        </div>
                        <div class="col-auto profile-btn">
                            <a href="" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                </div>

                <div class="profile-menu">
                    <ul class="nav nav-tabs nav-tabs-solid">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Password</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content profile-tab-cont">

                    <div class="tab-pane fade show active" id="per_details_tab">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between">
                                            <span>Personal Details</span>
                                        </h5>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Name</p>
                                            <p class="col-sm-9">@{{ accountDetails.name }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Date of Birth</p>
                                            <p class="col-sm-9">@{{ accountDetails.birthday }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Email ID</p>
                                            <p class="col-sm-9">@{{ accountDetails.email }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Mobile</p>
                                            <p class="col-sm-9">@{{ accountDetails.phone }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0">Address</p>
                                            <p class="col-sm-9">@{{ accountDetails.address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="password_tab" class="tab-pane fade">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Change Password</h5>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form @submit.prevent="updatePassword">
                                            <div class="form-group">
                                                <label>Old Password</label>
                                                <input type="password" class="form-control" v-model="oldPassword">
                                            </div>
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input type="password" class="form-control" v-model="newPassword">
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <input type="password" class="form-control" v-model="confirmPassword">
                                            </div>
                                            <button class="btn btn-primary" type="submit">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
            el: '#profileDetailsForm',
            data: {
                accountDetails: {
                    name: '',
                    email: '',
                    profile_picture: '',
                    birthday: 'Not specified',
                    phone: 'Not specified',
                    address: 'Not specified',
                    location: 'Not specified',
                    about: 'No about text'
                },
                // Separate data for password fields
                oldPassword: '',
                newPassword: '',
                confirmPassword: ''
            },
            mounted() {
                this.profileDetails();
            },
            methods: {
                profileDetails() {
                    axios.get("{{ route('profile.settings.data') }}")
                        .then(response => {
                            // Merge fetched data with default values
                            this.accountDetails = {
                                ...this.accountDetails,
                                ...response.data,
                                birthday: response.data.birthday || 'Not specified',
                                phone: response.data.phone || 'Not specified',
                                address: response.data.address || 'Not specified'
                            };
                        })
                        .catch(error => {
                            console.error('Error fetching details', error.response ? error.response.data :
                                error);
                        });
                },

                getImageUrl(imageName) {
                    return imageName ? `/storage/user_images/${imageName}` :
                        '{{ asset('/admin/assets/img/no_image.jpg') }}';
                },

                updatePassword() {
                    // Show processing alert
                    Swal.fire({
                        title: 'Updating Password',
                        text: 'Please wait...',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    axios.post("{{ route('update.password') }}", {
                            old_password: this.oldPassword,
                            new_password: this.newPassword,
                            new_password_confirmation: this.confirmPassword
                        })
                        .then(response => {
                            if (response.data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });

                                // Clear password fields
                                this.oldPassword = '';
                                this.newPassword = '';
                                this.confirmPassword = '';
                            }
                        })
                        .catch(error => {
                            // Handle validation errors
                            if (error.response && error.response.data) {
                                const errorData = error.response.data;

                                // Check if there are specific field errors
                                if (errorData.errors) {
                                    let errorMessages = [];

                                    // Collect all error messages
                                    Object.values(errorData.errors).forEach(messages => {
                                        errorMessages = errorMessages.concat(messages);
                                    });

                                    Swal.fire({
                                        title: 'Validation Error',
                                        html: errorMessages.map(msg => `<p>${msg}</p>`).join(''),
                                        icon: 'error',
                                        confirmButtonText: 'Try Again'
                                    });
                                } else {
                                    // Fallback to generic error message
                                    Swal.fire({
                                        title: 'Error!',
                                        text: errorData.message || 'Something went wrong',
                                        icon: 'error',
                                        confirmButtonText: 'Try Again'
                                    });
                                }
                            }
                        });
                },

            }
        });
    </script>
@endpush

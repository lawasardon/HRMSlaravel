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
                            {{-- <a href="#">
                                <!-- Dynamically set the image URL based on the fetched data -->
                                <img class="rounded-circle" alt="User Image"
                                    :src="getImageUrl(accountDetails.profile_picture)" width="50">
                            </a> --}}

                            <div class="profile-container">
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg"
                                            @change="handleImageUpload" style="display: none;" />

                                        <!-- Edit Button (when not editing) -->
                                        <i v-if="!isEditingImage" class="fas fa-pencil-alt edit-icon"
                                            @click="triggerFileUpload"></i>

                                        <!-- Save and Cancel Buttons (when editing) -->
                                        <div v-else class="d-flex">
                                            <i class="fas fa-check save-icon me-2" @click="saveProfileImage"></i>
                                            <i class="fas fa-times cancel-icon" @click="cancelImageEdit"></i>
                                        </div>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview"
                                            :style="{
                                                'background-image': `url(${getImageUrl(accountDetails.profile_picture)})`
                                            }">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col ms-md-n2 profile-user-info">
                            <!-- Dynamically set user name and email -->
                            <h4 class="user-name mb-0">@{{ accountDetails.name }}</h4>
                            <h6 class="text-muted">@{{ accountDetails.email }}</h6>
                            <div class="user-Location"><i class="fas fa-map-marker-alt"></i> @{{ accountDetails.location }}
                            </div>
                            <div class="about-text">@{{ accountDetails.about }}</div>
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
                confirmPassword: '',
                isEditingImage: false,
                selectedFile: null
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


                toggleImageEdit() {
                    this.isEditingImage = !this.isEditingImage;
                    if (!this.isEditingImage) {
                        // If cancelling edit, reset file selection
                        this.selectedFile = null;
                        $('#imageUpload').val('');
                    }
                },

                triggerFileUpload() {
                    this.isEditingImage = true;
                    // Programmatically trigger file input click
                    this.$nextTick(() => {
                        document.getElementById('imageUpload').click();
                    });
                },

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.selectedFile = file;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            $('#imagePreview').css('background-image', `url(${e.target.result})`);
                        };
                        reader.readAsDataURL(file);
                    }
                },

                saveProfileImage() {
                    if (!this.selectedFile) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please select an image first',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('profile_picture', this.selectedFile);

                    Swal.fire({
                        title: 'Uploading Image',
                        text: 'Please wait...',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    axios.post("{{ route('update.profile.picture') }}", formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            if (response.data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Profile image updated successfully',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });

                                // Update account details with new image
                                this.accountDetails.profile_picture = response.data.image_name;
                                this.isEditingImage = false;
                                this.selectedFile = null;
                            }
                            this.profileDetails();

                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: error.response?.data?.message || 'Failed to upload image',
                                icon: 'error',
                                confirmButtonText: 'Try Again'
                            });
                        });
                },

                cancelImageEdit() {
                    this.isEditingImage = false;
                    this.selectedFile = null;
                    $('#imageUpload').val('');
                    // Revert to original image
                    $('#imagePreview').css('background-image',
                        `url(${this.getImageUrl(this.accountDetails.profile_picture)})`);
                },

            }
        });



        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });
    </script>
@endpush


@push('css')
    <style>
        .avatar-upload {
            position: relative;
            max-width: 205px;
            margin: 50px auto;
        }

        .avatar-upload .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }

        .avatar-upload .avatar-edit input {
            display: none;
        }

        .avatar-upload .avatar-edit .edit-btn,
        .avatar-upload .avatar-edit .save-btn {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            transition: all .2s ease-in-out;
            text-align: center;
            line-height: 34px;
            color: #757575;
        }

        .avatar-upload .avatar-edit .edit-btn:hover,
        .avatar-upload .avatar-edit .save-btn:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .avatar-upload .avatar-edit .edit-btn::after {
            content: "\f040";
            font-family: 'FontAwesome';
        }

        .avatar-upload .avatar-edit .save-btn::after {
            content: "\f00c";
            font-family: 'FontAwesome';
        }

        .avatar-upload .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload .avatar-preview>div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .avatar-upload .avatar-edit .edit-icon,
        .avatar-upload .avatar-edit .save-icon,
        .avatar-upload .avatar-edit .cancel-icon {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            transition: all .2s ease-in-out;
            text-align: center;
            line-height: 34px;
            color: #757575;
        }

        .avatar-upload .avatar-edit .edit-icon:hover,
        .avatar-upload .avatar-edit .save-icon:hover,
        .avatar-upload .avatar-edit .cancel-icon:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }
    </style>
@endpush

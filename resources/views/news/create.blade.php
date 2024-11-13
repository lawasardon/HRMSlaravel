@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="storeNews">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Add News</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add News</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="saveNews" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Add News</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Description <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="description" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Image <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" @change="handleFileUpload" accept="image/*" required>
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
            el: '#storeNews',
            data: {
                description: '',
                image: '',
            },
            methods: {
                saveNews() {
                    // Create a new FormData object to handle file uploads
                    const formData = new FormData();
                    formData.append('description', this.description);
                    formData.append('image', this.image); // Append the file to FormData

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

                    // Make the request to store the news
                    axios.post("{{ route('store.news') }}", formData)
                        .then(response => {
                            Swal.fire({
                                icon: 'success',
                                title: 'News added successfully',
                                text: response.data.message,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then(() => {
                                // Optional: Redirect after successful submission
                                window.location.href = '/';
                            });
                        })
                        .catch(error => {
                            console.error('Error adding news', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'An error occurred',
                                text: error.response?.data?.message ||
                                    'An error occurred while adding the news.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            });
                        });
                },

                handleFileUpload(event) {
                    // This method will handle the file input manually
                    this.image = event.target.files[0]; // Set the image file
                },
            }



        });
    </script>
@endpush

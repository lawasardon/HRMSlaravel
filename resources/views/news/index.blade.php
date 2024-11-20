@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="newsPostedForm">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">News Posted</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">News Posted</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="student-group-form">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by ID ...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Name ...">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Phone ...">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="search-student-btn">
                        <button type="btn" class="btn btn-primary">Search</button>
                    </div>
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
                                    <h3 class="page-title">News Posted</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('show.create.news') }}" class="btn btn-primary"><i
                                            class="fas fa-plus"></i>
                                        Add New</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table
                                class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>Image</th>
                                        <th>Description</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in newsPosted" :id="data.id">

                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="student-details.html" class="avatar avatar-sm me-2"><img
                                                        class="avatar-img rounded-circle"
                                                        :src="`/storage/news_images/${data.image}`" alt="User Image"></a>
                                            </h2>
                                        </td>
                                        <td>@{{ data.description }}</td>
                                        <td>@{{ data.created_at }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <button @click="confirmDelete(data.id)"
                                                    class="custom-delete-btn btn btn-danger far fa-trash-alt">
                                                </button>
                                            </div>
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
            el: '#newsPostedForm',
            data: {
                newsPosted: [],
            },
            mounted() {
                this.myNewsPosted();
            },
            methods: {
                myNewsPosted() {
                    axios.get("{{ route('all.news.data') }}")
                        .then(response => {
                            this.newsPosted = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching news list', error.response ? error.response.data :
                                error);
                        });
                },

                confirmDelete(id) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true,
                        preConfirm: () => {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Deleting the news item...',
                                icon: 'info',
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            return axios.delete(`/delete/news/${id}`).then(response => {
                                return response.data.message;
                            }).catch(error => {
                                throw new Error('Error deleting news');
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.newsPosted = this.newsPosted.filter(news => news.id !== id);
                            Swal.fire(
                                'Deleted!',
                                result.value,
                                'success'
                            );
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire(
                                'Cancelled',
                                'Your news item is safe.',
                                'error'
                            );
                        }
                    }).catch(error => {
                        Swal.fire(
                            'Error!',
                            'There was an issue deleting the news.',
                            'error'
                        );
                    });
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

@push('css')
    <style>
        .custom-delete-btn {
            margin-left: 10px;
            font-size: 20px;
            color: red !important;
            border: 1px solid red;
            background-color: transparent;
        }

        .custom-delete-btn:hover {
            color: white !important;
            background-color: red !important;
        }
    </style>
@endpush

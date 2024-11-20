<div class="content container-fluid">

    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    @hasrole('employee')
                        <h3 class="page-title">
                            Welcome to
                            {{ Auth::user()->employee && Auth::user()->employee->department_id === 1 ? 'acqua' : (Auth::user()->employee && Auth::user()->employee->department_id === 2 ? 'laminin' : 'unknown') }}
                            {{ Auth::user()->name }}!
                        </h3>
                    @endhasrole
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @hasanyrole('admin|hr')
        <div class="row" id="countDataForm">
            <div class="col-xl-3 col-sm-6 col-12 d-flex" v-for="(count, department) in countData" :key="department">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>@{{ department }}</h6>
                                <h3>@{{ count }}</h3>
                            </div>
                            <div class="db-icon">
                                <img :src="getIcon(department)" alt="Dashboard Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    @endhasrole

    <div class="row">
        <div class="col-xl-6 d-flex" id="allBirthday">
            <div class="card flex-fill student-space comman-shadow">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title">Birthdays for this Month</h5>
                    <ul class="chart-list-out student-ellips">
                        {{-- <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                        </li> --}}
                    </ul>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table star-student table-hover table-center table-borderless table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th class="text-center">Birthday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="data in birthday" :key="data.id">
                                    <td class="text-nowrap">
                                        <div>@{{ data.id_number }}</div>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="profile.html">
                                            <img class="rounded-circle" :src="getImageUrl(data.user.profile_picture)"
                                                width="25" alt="Star Students">
                                            @{{ data.name }}
                                        </a>
                                    </td>
                                    <td class="text-center">@{{ formatDate(data.birthday) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-6 d-flex" id="allNews">

            <div class="card flex-fill comman-shadow">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title ">News </h5>
                    <ul class="chart-list-out student-ellips">
                        {{-- <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                        </li> --}}
                    </ul>
                </div>
                <div class="card-body">
                    <div class="activity-groups">
                        <div v-for="data in news" :key="data.id">
                            <div class="activity-awards">
                                <div class="award-boxs">
                                    {{-- <img src="{{ asset('admin/assets/img/icons/award-icon-01.svg') }}" alt="Award"> --}}
                                    <img :src="getProfileImageUrl(data.user.profile_picture)" width="25">
                                </div>
                                <div class="award-list-outs">
                                    <h4>@{{ data.user.name }}</h4>
                                    <h5>@{{ data.description }}</h5>
                                </div>
                                <div class="award-time-list">
                                    <span>@{{ data.created_at }}</span>
                                </div>
                            </div>
                            <div class="image-posted" align="center">
                                <img :src="getImageUrl(data.image)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- @include('components.socials') --}}
</div>

@push('js')
    <script>
        new Vue({
            el: '#allNews',
            data: {
                news: [],
            },
            mounted() {
                this.allNews();
            },
            methods: {
                allNews() {
                    axios.get("{{ route('get.news.data') }}")
                        .then(response => {
                            this.news = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching news', error.response ? error.response.data :
                                error);
                        });
                },

                getImageUrl(imageName) {
                    return `/storage/news_images/${imageName}`; // Assumes the 'user_images' folder is in 'public'
                },
                getProfileImageUrl(imageName) {
                    return `/storage/user_images/${imageName}`; // Assumes the 'user_images' folder is in 'public'
                }
            }
        });

        new Vue({
            el: '#allBirthday',
            data: {
                birthday: [],
            },
            mounted() {
                this.allBirthdayOnThisMonth();
            },
            methods: {
                allBirthdayOnThisMonth() {
                    axios.get("{{ route('birthday.data') }}")
                        .then(response => {
                            this.birthday = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching birthday', error.response ? error.response.data :
                                error);
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

                getImageUrl(imageName) {
                    // If the image exists, return the path to the image, otherwise use a default image.
                    if (imageName) {
                        return `/storage/user_images/${imageName}`;
                    } else {
                        return '{{ asset('/admin/assets/img/no_image.jpg') }}'; // Default image
                    }
                }
            }
        });

        new Vue({
            el: '#countDataForm',
            data: {
                countData: {
                    Aqua: 0,
                    Laminin: 0,
                    totalEmployee: 0,
                    totalDepartment: 0
                },
            },
            mounted() {
                this.countAllData();
            },
            methods: {
                countAllData() {
                    axios.get("{{ route('count.data') }}")
                        .then(response => {
                            this.countData = response
                                .data; // This will be the data structure with counts for each department
                        })
                        .catch(error => {
                            console.error('Error fetching data', error.response ? error.response.data : error);
                        });
                },
                getIcon(department) {
                    const icons = {
                        Aqua: 'admin/assets/img/icons/dash-icon-01.svg',
                        Laminin: 'admin/assets/img/icons/dash-icon-01.svg',
                        totalEmployee: 'admin/assets/img/icons/dash-icon-03.svg',
                        totalDepartment: 'admin/assets/img/icons/dash-icon-04.svg'
                    };
                    return icons[department] || 'admin/assets/img/icons/dash-icon-01.svg'; // Default icon
                }
            }
        });
    </script>
@endpush

@push('css')
    <style>
        .image-posted img {
            height: auto;
            width: 100%;
            border-radius: 10px;
            margin-bottom: 60px
        }

        .award-boxs img {
            margin-bottom: 20px;
        }

        .award-list-outs h5 {
            max-width: 509px;
        }
    </style>
@endpush

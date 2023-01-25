@extends('welcome')
@section('title')
    Company Listing
@endsection
@section('content')
    <div class="container" id="company">

        {{--Add Edit Modal Start--}}
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@{{ modal.id == undefined ? 'Add' : 'Edit' }} Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                @click="resetModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Company Name</label>
                            <input type="text" v-model="modal.name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Country</label>
                            <select v-model="modal.country" class="form-control">
                                <option :value="country.id" :key="idx" v-for="(country,idx) in resources.countries">
                                    @{{ country.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Users</label>
                            <select v-model="modal.users" multiple class="form-control">
                                <option :value="user.id" :key="idx" v-for="(user,idx) in resources.users">
                                    @{{ user.name }}
                                </option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="closeModal" data-bs-dismiss="modal"
                                @click="resetModal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" @click="saveCompany">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        {{--Add Edit Modal End--}}


        <h3>Companies</h3>
        <a href="javascript:void(0);" class="btn btn-primary" data-bs-target="#myModal" data-bs-toggle="modal">Add
            Company</a>
        <select class="float-end form-control w-25" v-model="search">
            <option value="">All</option>
            <option :value="country.name" :key="idx" v-for="(country,idx) in resources.countries">
                @{{ country.name }}
            </option>
        </select>
        <div class="row">
            <div class="col-md-12 shadow-lg p-3 mt-3" :key="countryIndex" v-for="(country,countryIndex) in countries">
                <h5>@{{ country.name }}</h5>
                <div class="row">
                    <template v-if="country.companies.length > 0">
                        <div class="col-md-4 mt-2" :key="companyIndex"
                             v-for="(company,companyIndex) in country.companies">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">@{{company.name}}</h5>
                                    <template v-if="company.users.length > 0">
                                        <ol>
                                            <li :key="userIndex" v-for="(user,userIndex) in company.users">
                                                @{{user.user.name}} <b>(@{{ user.join_date }})</b>
                                            </li>
                                        </ol>
                                    </template>
                                    <template v-else>
                                        No Users <br>
                                    </template>
                                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-target="#myModal"
                                       data-bs-toggle="modal"
                                       @click="editCompany(company)">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger"
                                       @click="deleteCompany(company)">Delete</a>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="text-danger">No Companies</div>
                    </template>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const countries = @json($countries);
        const users = @json($users);
        const fetchURL = "{{route('companies.fetch')}}"
        const storeURL = "{{route('companies.store')}}"
        const deleteURL = "{{route('companies.delete')}}"
        const {createApp} = Vue

        createApp({
            watch: {
                search() {
                    this.fetchCompanies()
                }
            },
            data() {
                return {
                    search: null,
                    countries: [],
                    modal: {
                        name: "",
                        country: null,
                        users: []
                    },
                    resources: {
                        users: users,
                        countries: countries
                    }
                }
            },
            methods: {
                deleteCompany(data) {
                    const $that = this
                    swal({
                        title: "Are you sure?",
                        text: "You want to delete this Company!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((willDelete) => {
                            if (willDelete) {

                                axios.delete(deleteURL + `/${data.id}`).then(function (response) {
                                    const res = response.data
                                    if (res.success) {
                                        swal(res.message, {
                                            icon: "success",
                                        });
                                        $that.fetchCompanies()
                                    }
                                }).catch(error => {

                                })
                            }
                        });
                },
                saveCompany() {
                    const $that = this
                    axios.post(storeURL, this.modal).then(function (response) {
                        const res = response.data
                        if (res.success) {
                            $('#myModal').modal('hide')
                            $that.fetchCompanies()
                        }
                    }).catch(error => {
                        swal(error.response.data.message, {
                            icon: "error",
                        });

                    })
                },
                resetModal() {
                    this.modal = {
                        name: "",
                        country: null,
                        users: []
                    }
                },
                editCompany(company) {
                    this.modal.id = company.id
                    this.modal.name = company.name
                    this.modal.country = company.country_id
                    if (company.users.length > 0) {
                        this.modal.users = company.users.map(user => {
                            return user.user_id
                        })
                    }
                },
                fetchCompanies() {
                    const that = this;
                    let URL = fetchURL;
                    if (this.search != null) {
                        URL += `?country=${this.search}`
                    }
                    axios.get(URL).then(function (response) {
                        const res = response.data
                        if (res.success) {
                            that.countries = res.data
                        }
                    }).catch(error => {

                    })
                }
            },
            mounted() {
                $("#myModal").on("hidden.bs.modal", () => {
                    this.resetModal()
                });
                this.fetchCompanies()
            }
        }).mount('#company')
    </script>
@endsection

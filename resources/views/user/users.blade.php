@extends('welcome')
@section('title')
    User Listing
@endsection
@section('content')
    <div class="container" id="company">

        {{--Add Edit User Start--}}
        <div class="modal" id="userModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="userForm">
                        <div class="modal-header">
                            <h5 class="modal-title">@{{ modal.id == undefined ? 'Add' : 'Edit' }} User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    @click="resetModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" v-model="modal.name" class="form-control" name="name" id="name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" v-model="modal.email" class="form-control" name="email" id="email">
                            </div>
                            <div class="mb-3" v-if="modal.id == undefined">
                                <label class="form-label">password</label>
                                <input type="password" v-model="modal.password" class="form-control" name="password"
                                       id="password">
                            </div>
                            <div class="mb-3" v-if="modal.id == undefined">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" v-model="modal.cpassword" class="form-control" name="cpassword"
                                       id="cpassword">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="closeModal" data-bs-dismiss="modal"
                                    @click="resetModal">
                                Close
                            </button>
                            <button type="button" class="btn btn-primary" @click="saveUser">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{--Add Edit User End--}}

        <h3>Users</h3>
        <a href="javascript:void(0);" class="btn btn-success" data-bs-target="#userModal" data-bs-toggle="modal">Add
            User</a>
        <div class="row">
            <table class="table table-bordered mt-4">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th style="width: 25%">Action</th>
                </tr>
                </thead>
                <tbody>
                <template v-if="loading">
                    <tr>
                        <td colspan="3">Loading...</td>
                    </tr>
                </template>
                <template v-else-if="users.length > 0">
                    <tr :key="user.id" v-for="user in users">
                        <td>@{{ user.name }}</td>
                        <td>@{{ user.email }}</td>
                        <td>
                            <a class="btn btn-primary ml-3" href="javascript:void(0)" data-bs-target="#userModal"
                               data-bs-toggle="modal" @click="editUser(user)">Edit</a>
                            <a class="btn btn-danger ml-3" href="javascript:void(0)"
                               @click="deleteUser(user)">Delete</a>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td colspan="3">No Users</td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const fetchURL = "{{route('users.fetch')}}"
        const storeURL = "{{route('users.store')}}"
        const deleteURL = "{{route('users.delete')}}"
        const {createApp} = Vue

        createApp({
            data() {
                return {
                    loading: false,
                    users: [],
                    modal: {
                        name: null,
                        email: null,
                        password: null,
                        cpassword: null
                    },
                }
            },
            methods: {
                deleteUser(data) {
                    swal({
                        title: "Are you sure?",
                        text: "You want to delete this user!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((willDelete) => {
                            if (willDelete) {
                                axios.delete(deleteURL + `/${data.id}`).then((response) => {
                                    const res = response.data
                                    console.log(res)
                                    if (res.success) {
                                        swal(res.message, {
                                            icon: "success",
                                        });
                                        this.fetchUsers()
                                    }
                                })
                            }
                        });
                },
                validData() {
                    let $valid = true
                    if (this.modal.name == "" || this.modal.email == "") {
                        $valid = false;
                    }
                    if (this.modal.id == undefined) {
                        if (this.modal.password == "" || this.modal.cpassword == "") {
                            $valid = false;
                        }
                        if (this.modal.password != this.modal.cpassword) {
                            $valid = false;
                        }
                    }
                    return $valid
                },
                saveUser() {
                    if (this.validData()) {
                        axios.post(storeURL, this.modal).then((response) => {
                            const res = response.data
                            if (res.success) {
                                swal(res.message, {
                                    icon: "success",
                                });
                                $('#userModal').modal('hide')
                                this.fetchUsers()
                            }
                        }).catch(error => {
                            swal(error.response.data.message, {
                                icon: "error",
                            });
                        })
                    } else {
                        swal('Please Fill Valid Details', {
                            icon: "error",
                        });
                    }
                },
                resetModal() {
                    this.modal = {
                        name: null,
                        email: null,
                        password: null,
                        cpassword: null
                    }
                },
                editUser(user) {
                    this.modal.id = user.id
                    this.modal.name = user.name
                    this.modal.email = user.email
                },
                fetchUsers() {
                    this.loading = true
                    axios.get(fetchURL).then((response) => {
                        const res = response.data
                        this.loading = false
                        if (res.success) {
                            this.users = res.data
                        }
                    }).catch(error => {
                        this.loading = false
                    })
                }
            },
            mounted() {
                $("#userModal").on("hidden.bs.modal", () => {
                    this.resetModal()
                });
                this.fetchUsers()
            }
        }).mount('#company')
    </script>
@endsection

@extends('welcome')
@section('title')
    PDF Listing
@endsection

@section('content')
    <div class="container" id="pdfListing">
        <h2>PDF</h2>

        <div class="row ">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <form id="pdfUploadForm">
                    <input type="file" class="form-control">
                    <button type="button" class="btn btn-success" @click="uploadPdf">Upload</button>
                </form>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Size</th>
                <th style="width: 20%">Action</th>
            </tr>
            </thead>
            <tbody>
            <template v-if="loading">
                <tr>
                    <td colspan="3">Loading...</td>
                </tr>
            </template>
            <template v-else-if="files.length > 0">
                <tr :key="file.id" v-for="file in files">
                    <td>@{{ file.name }}</td>
                    <td>@{{ file.size }}</td>
                    <td>
                        <a :href="file.path" target="_blank">View</a>
                        <a class="text-danger" href="javascript:void(0);" @click="deleteFile(file)">Delete</a>
                    </td>
                </tr>
            </template>
            <template v-else>
                <tr>
                    <td colspan="3">No Files</td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')
    <script>
        let fetchURL = "{{ route('pdf.fetch') }}"
        let uploadURL = "{{ route('pdf.upload') }}"
        const deleteURL = "{{ route('pdf.delete') }}"
        const {createApp} = Vue

        createApp({
            data() {
                return {
                    loading : false,
                    files: []
                }
            },
            methods: {
                deleteFile(file) {
                    console.log(`${deleteURL}/${file.id}`)
                    swal({
                        title: "Are you sure?",
                        text: "you want to delete this file!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((willDelete) => {
                            if (willDelete) {
                                axios.delete(`${deleteURL}/${file.id}`).then(response => {
                                    const res = response.data
                                    if (res.success) {
                                        swal({
                                            text: res.message,
                                            icon: "success",
                                        });
                                        this.fetchPdfs()
                                    }
                                })
                            }
                        })
                },
                uploadPdf() {
                    var files = $('input[type="file"]')[0].files;
                    if (files.length > 0) {
                        var formData = new FormData();
                        formData.append('file', files[0]);
                        axios.post(uploadURL, formData, {
                            headers: {
                                'content-type': 'multipart/form-data'
                            }
                        }).then(response => {
                            const res = response.data
                            if (res.success) {
                                swal({
                                    title: "Good!",
                                    text: res.message,
                                    icon: "success",
                                });
                                this.fetchPdfs()
                            }
                        }).catch(error => {
                            swal({
                                title: "Oops!",
                                text: error.response.data.message,
                                icon: "error",
                            });
                        })
                    }
                },
                fetchPdfs() {
                    this.loading = true
                    axios.get(fetchURL).then(response => {
                        this.loading = false
                        const res = response.data
                        if (res.success) {
                            this.files = res.data
                        }
                    }).catch(error => {
                        this.loading = false
                        swal('Something Went Wrong Try Again', {
                            icon: "error",
                        });
                    })
                }
            },
            mounted() {
                this.fetchPdfs()
            }
        }).mount('#pdfListing')
    </script>
@endsection

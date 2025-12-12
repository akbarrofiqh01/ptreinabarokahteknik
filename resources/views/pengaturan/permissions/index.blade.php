@extends('layouts.app')
@section('title', 'Permissions - Siakad')
@section('title-content', 'Permissions')
@section('content')
    <div class="row gy-4">
        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">New Permissions</h5>
                </div>
                <div class="card-body">

                    <form id="formPermissions">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Name Permissions</label>
                                <input type="text" class="form-control" id="valName"
                                    placeholder="Entry name permissions" autocomplete="off">
                            </div>

                            @can('create permissions')
                                <div class="col-12">
                                    <button type="submit" id="SubmitBtn" class="btn btn-primary-600 w-100">
                                        Tambah Data
                                    </button>
                                </div>
                                <div class="col-12">
                                    <button type="button" id="loadingSpinner" class="btn btn-danger-600 w-100 d-none" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Proses Tambah...
                                    </button>
                                </div>
                            @endcan

                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Permissions</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="example3" data-page-length='10'>
                            <thead>
                                <tr>
                                    <th class="border-bottom-0" width="5%">No</th>
                                    <th class="border-bottom-0">Permissions</th>
                                    <th class="border-bottom-0">Dibuat</th>
                                    <th class="border-bottom-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $show)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $show->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($show->created)->format('d M, Y') }}</td>
                                        <td>
                                            @can('edit permissions')
                                                <a data-href="{{ route('permissions.edit', ['permissionscode' => $show->code_permissions]) }}"
                                                    data-bs-title="Edit Permissions" data-bs-remote="false"
                                                    data-bs-toggle="modal" data-bs-target="#dinamicModal"
                                                    data-bs-backdrop="static" data-bs-keyboard="false" title="edit permissions"
                                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center cursor-pointer">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('delete permissions')
                                                <a href="javascript:void(0)"
                                                    class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    onclick="hapusConfirm('{{ $show->code_permissions }}')"
                                                    title="Hapus Permissions">
                                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="border-bottom-0" width="5%">No</th>
                                    <th class="border-bottom-0">Permissions</th>
                                    <th class="border-bottom-0">Dibuat</th>
                                    <th class="border-bottom-0">Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#example3').DataTable({
                responsive: true
            });
        });

        document.getElementById('formPermissions').addEventListener('submit', function(e) {
            e.preventDefault();

            const valName = document.getElementById('valName').value.trim();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (valName === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Nama permission tidak boleh kosong.'
                });
                return;
            }

            // Show loading
            document.getElementById('SubmitBtn').classList.add('d-none');
            document.getElementById('loadingSpinner').classList.remove('d-none');

            axios.post("{{ route('permissions.store') }}", {
                    name: valName
                }, {
                    headers: {
                        "X-CSRF-TOKEN": token
                    }
                })
                .then(function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                })
                .catch(function(error) {

                    let errorMessages = '';

                    // CSRF refresh
                    if (error.response?.data?.csrf_token) {
                        axios.defaults.headers.common['X-CSRF-TOKEN'] = error.response.data.csrf_token;
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', error.response
                            .data.csrf_token);
                    }

                    // 422 validation error
                    if (error.response?.status === 422) {
                        Object.values(error.response.data.errors).forEach(messages => {
                            messages.forEach(msg => errorMessages += `${msg}<br>`);
                        });
                    } else if (error.response?.data?.message) {
                        errorMessages = error.response.data.message;
                    } else {
                        errorMessages = "Terjadi kesalahan saat menambah data.";
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: errorMessages
                    });

                })
                .finally(function() {
                    // Always restore buttons
                    document.getElementById('SubmitBtn').classList.remove('d-none');
                    document.getElementById('loadingSpinner').classList.add('d-none');
                });
        });


        function hapusConfirm(userId) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ingin menghapus permissions ini !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.delete(`/pengaturan/permissions/deletePermissions/${userId}`, {
                            id: userId,
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': token
                            }
                        })
                        .then(function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(function(error) {
                            let errorMessage = 'Terjadi kesalahan saat menghapus data penduduk.';

                            if (error.response && error.response.data && error.response.data.message) {
                                errorMessage = error.response.data.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: errorMessage
                            });
                        });
                }
            });
        }
    </script>
@endsection

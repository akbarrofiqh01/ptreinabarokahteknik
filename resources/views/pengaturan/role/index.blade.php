@extends('layouts.app')
@section('title', 'Roles - PT Reina Barokah Teknik')
@section('title-content', 'Roles')
@section('content')
    <style>
        .permissions-cell {
            max-width: 350px;
            white-space: normal;
            word-break: break-word;
        }

        .permissions-wrapper {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            max-height: 260px;
            overflow-y: auto;
            background-color: #fafafa;
        }

        /* Scrollbar rapi */
        .permissions-wrapper::-webkit-scrollbar {
            width: 6px;
        }

        .permissions-wrapper::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }

        .permissions-wrapper::-webkit-scrollbar-track {
            background-color: transparent;
        }
    </style>
    <div class="row gy-4">
        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="card h-100 p-0">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">New Roles</h6>
                </div>
                <div class="card-body p-24">
                    <form id="formNewRoles">

                        <div class="row gy-3">

                            <div class="col-12">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Name Roles</label>
                                <input type="text" class="form-control radius-8" id="valName"
                                    placeholder="Entry name roles" autocomplete="off">
                            </div>
                            @if ($dataPermission->isNotEmpty())
                                <div class="col-12 mt-2">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Permissions
                                    </label>
                                    <div class="permissions-wrapper">
                                        <div class="d-flex flex-column gap-12">

                                            @foreach ($dataPermission as $permissions)
                                                <div class="form-check checked-primary d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="permission_{{ $permissions->code_permissions }}"
                                                        name="permissions[]" value="{{ $permissions->name }}">

                                                    <label class="form-check-label fw-medium text-secondary-light text-sm"
                                                        for="permission_{{ $permissions->code_permissions }}">
                                                        {{ $permissions->name }}
                                                    </label>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Submit Buttons -->
                            @can('create roles')
                                <div class="col-12 mt-24 d-flex flex-column gap-2">

                                    <button type="submit" id="SubmitBtn"
                                        class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8 w-100">
                                        Tambah Data
                                    </button>

                                    <button type="button" id="loadingSpinner" class="btn btn-danger-600 w-100 d-none">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
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
                    <h3 class="card-title">Role</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="examples3" class="table table-bordered border-bottom w-100">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0" width="5%">No</th>
                                    <th class="border-bottom-0">Roles</th>
                                    <th class="border-bottom-0">Permissions</th>
                                    <th class="border-bottom-0">Dibuat</th>
                                    <th class="border-bottom-0" width="25%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataRoles as $rowRoles)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucfirst($rowRoles->name) }}</td>
                                        <td class="permissions-cell">
                                            {{ $rowRoles->permissions->pluck('name')->implode(',') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($rowRoles->created)->format('d M, Y') }}</td>
                                        <td>
                                            @can('edit roles')
                                                <a data-href="{{ route('roles.edit', ['roleCode' => $rowRoles->code_role]) }}"
                                                    data-bs-title="Edit Role" data-bs-remote="false" data-bs-toggle="modal"
                                                    data-bs-target="#dinamicModal" data-bs-backdrop="static"
                                                    data-bs-keyboard="false" title="edit role"
                                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center cursor-pointer">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('delete roles')
                                                <a href="javascript:void(0)"
                                                    class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    onclick="hapusConfirm('{{ $rowRoles->code_role }}')" title="Hapus Role">
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
                                    <th class="border-bottom-0">Roles</th>
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
            $('#examples3').DataTable({
                responsive: true,
                autoWidth: false,
                scrollX: true,
                dom: "<'row mb-3'<'col-md-6'l><'col-md-6 text-end'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",
                columnDefs: [{
                    targets: 2,
                    className: 'permissions-cell'
                }]
            });
        });
        document.getElementById('formNewRoles').addEventListener('submit', function(e) {
            e.preventDefault();

            const valName = document.getElementById('valName').value;
            const selectedPermissions = Array.from(document.querySelectorAll('input[name="permissions[]"]:checked'))
                .map(checkbox => checkbox.value);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.getElementById('SubmitBtn').classList.add('d-none');
            document.getElementById('loadingSpinner').classList.remove('d-none');

            axios.post('{{ route('roles.store') }}', {
                    name: valName,
                    selectedPermissions: selectedPermissions,
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
                    let errorMessages = '';

                    if (error.response && error.response.data && error.response.data.csrf_token) {
                        axios.defaults.headers.common['X-CSRF-TOKEN'] = error.response.data.csrf_token;
                        const meta = document.querySelector('meta[name="csrf-token"]');
                        if (meta) {
                            meta.setAttribute('content', error.response.data.csrf_token);
                        }
                    }

                    if (error.response && error.response.status === 422 && error.response.data.errors) {
                        Object.values(error.response.data.errors).forEach(function(messages) {
                            messages.forEach(function(message) {
                                errorMessages += `${message}<br>`;
                            });
                        });
                    } else if (error.response && error.response.data.message) {
                        errorMessages = `${error.response.data.message}<br>`;
                    } else {
                        errorMessages = 'Terjadi kesalahan saat menambah data.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: errorMessages
                    });

                    document.getElementById('SubmitBtn').classList.remove('d-none');
                    document.getElementById('loadingSpinner').classList.add('d-none');
                });
        });

        function hapusConfirm(userId) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ingin menghapus roles ini !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.delete(`/pengaturan/roles/deleteRoles/${userId}`, {
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
                            let errorMessage = 'Terjadi kesalahan saat menghapus roles.';

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

@extends('layouts.app')
@section('title', 'Users Eksternal - PT Reina Barokah Teknik')
@section('title-content', 'Users Eksternal')
@section('content')
    <div class="card h-100 p-0 radius-12">
        <div
            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <h5 class="card-title">Data Users Eksternal</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-bordered table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Perusahaan</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataUsers as $show)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $show->company->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div
                                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                                <span class="text-primary fw-bold">
                                                    {{ substr($show->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-truncate" style="max-width: 150px;"
                                                title="{{ $show->name }}">
                                                {{ $show->name }}
                                            </div>
                                            @if (!empty($show->username))
                                                <small class="text-muted text-truncate d-block" style="max-width: 150px;"
                                                    title="{{ $show->username }}">
                                                    @<span>{{ $show->username }}</span>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $show->email }}">
                                        {{ $show->email }}
                                    </div>
                                    @if (!empty($show->phone))
                                        <small class="text-muted d-block">
                                            <iconify-icon icon="solar:phone-calling-linear" class="me-1"></iconify-icon>
                                            {{ $show->phone }}
                                        </small>
                                    @endif
                                </td>

                                <!-- Role -->
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($show->roles as $role)
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                        @if ($show->roles->isEmpty())
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">No Role</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">

                                        @if ($show->status === 'pending')
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                                Pending
                                            </span>
                                        @elseif ($show->status === 'active')
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                Active
                                            </span>
                                        @elseif ($show->status === 'suspended')
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                                Suspended
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-nowrap">
                                        <iconify-icon icon="solar:calendar-outline" class="me-1"></iconify-icon>
                                        {{ \Carbon\Carbon::parse($show->created)->format('d M Y') }}
                                    </div>

                                </td>

                                <!-- Action -->
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @can('edit users')
                                            @if ($show->status === 'pending')
                                                <button type="button" onclick="approveUser('{{ $show->code_user }}')"
                                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    title="Approve User">
                                                    <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                                </button>
                                                <button type="button" onclick="suspendUser('{{ $show->code_user }}')"
                                                    class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    title="Tolak User">
                                                    <iconify-icon icon="solar:user-block-outline"></iconify-icon>
                                                </button>
                                            @else
                                                @role('superadmin')
                                                    @if ($show->source === 'register' && $show->status === 'active' && $show->hasRole('user'))
                                                        <button type="button"
                                                            data-href="{{ route('usersEksternal.permitInternal', ['usercode' => $show->code_user]) }}"
                                                            data-bs-title="Promote User to Internal" data-bs-toggle="modal"
                                                            data-bs-target="#dinamicModal" data-bs-backdrop="static"
                                                            data-bs-keyboard="false"
                                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            title="Promote to Internal">
                                                            <iconify-icon icon="solar:user-plus-outline"></iconify-icon>
                                                        </button>
                                                    @endif
                                                @endrole
                                            @endif
                                            <button type="button"
                                                data-href="{{ route('usersEksternal.detil', ['usercode' => $show->code_user]) }}"
                                                data-bs-title="Detil User Eksternal" data-bs-toggle="modal"
                                                data-bs-target="#dinamicModal" data-bs-backdrop="static"
                                                data-bs-keyboard="false"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                title="Detil User Eksternal">
                                                <iconify-icon icon="solar:eye-outline" class="me-1"></iconify-icon>
                                            </button>
                                            <button type="button"
                                                data-href="{{ route('usersEksternal.edit', ['usercode' => $show->code_user]) }}"
                                                data-bs-title="Edit User Eksternal" data-bs-toggle="modal"
                                                data-bs-target="#dinamicModal" data-bs-backdrop="static"
                                                data-bs-keyboard="false"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                title="Edit User Eksternal">
                                                <iconify-icon icon="tabler:edit" class="me-1"></iconify-icon>
                                            </button>
                                        @endcan

                                        @can('delete users')
                                            <button type="button" onclick="hapusConfirm('{{ $show->code_user }}')"
                                                class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                title="Hapus User Eksternal">
                                                <iconify-icon icon="mingcute:delete-2-line" class="me-1"></iconify-icon>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                responsive: true
            });
        });

        function approveUser(userCode) {
            Swal.fire({
                title: 'Apakah Anda Yakin ?',
                text: 'User ini akan diaktifkan dan dapat login.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Approve',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.post(`/users/users-eksternal-approve/${userCode}`, {}, {
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
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: error.response?.data?.message ?? 'Terjadi kesalahan.'
                            });
                        });
                }
            });
        }

        function suspendUser(userCode) {
            Swal.fire({
                title: 'Apakah Anda Yakin ?',
                text: 'User ini akan disuspend dan tidak dapat login.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Suspend',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.post(`/users/users-eksternal-suspend/${userCode}`, {}, {
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
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: error.response?.data?.message ?? 'Terjadi kesalahan.'
                            });
                        });
                }
            });
        }

        function hapusConfirm(userId) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ingin menghapus user ini !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.delete(`/users/users-eksternal-delete/${userId}`, {
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

        function impersonateUser(codeUser) {
            Swal.fire({
                title: 'View sebagai user?',
                text: 'Anda akan melihat sistem sebagai user ini.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    const token = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');

                    axios.post(`/impersonate/${codeUser}`, {}, {
                            headers: {
                                'X-CSRF-TOKEN': token
                            }
                        })
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Sekarang Anda melihat sebagai user',
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('dashboard') }}";
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: err.response?.data?.message || 'Tidak dapat impersonate user'
                            });
                        });
                }
            });
        }
    </script>
@endsection

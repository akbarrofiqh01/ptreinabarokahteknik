@extends('layouts.app')
@section('title', 'Users - PT Reina Barokah Teknik')
@section('title-content', 'Users')
@section('content')
    <div class="card h-100 p-0 radius-12">
        <div
            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <h5 class="card-title">Data Users</h5>

            @can('create users')
                <a data-href="{{ route('users.create') }}" data-bs-title="Tambah Users" data-bs-remote="false"
                    data-bs-toggle="modal" data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon> Users
                </a>
            @endcan

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-bordered table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>User</th>
                            <th>Email</th>
                            <th width="15%">Role</th>
                            <th>Dibuat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datausers as $show)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

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

                                <!-- Created -->
                                <td>
                                    <div class="text-nowrap">
                                        <iconify-icon icon="solar:calendar-outline" class="me-1"></iconify-icon>
                                        {{ \Carbon\Carbon::parse($show->created)->format('d M Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($show->created)->format('H:i') }}
                                    </small>
                                </td>

                                <!-- Action -->
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @can('edit users')
                                            <!-- Edit Data User -->
                                            <button type="button"
                                                data-href="{{ route('users.edit', ['usercode' => $show->code_user]) }}"
                                                data-bs-title="Edit User" data-bs-toggle="modal" data-bs-target="#dinamicModal"
                                                data-bs-backdrop="static" data-bs-keyboard="false"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <iconify-icon icon="tabler:edit" class="me-1"></iconify-icon>
                                            </button>

                                            <!-- Reset Password (Opsional) -->
                                            {{-- <button type="button"
                                                data-href="{{ route('users.reset-password', ['usercode' => $show->code_user]) }}"
                                                data-bs-title="Reset Password" data-bs-toggle="modal"
                                                data-bs-target="#dinamicModal" data-bs-backdrop="static"
                                                data-bs-keyboard="false"
                                                class="btn btn-sm btn-outline-warning d-flex align-items-center">
                                                <iconify-icon icon="solar:lock-password-outline" class="me-1"></iconify-icon>
                                                Reset
                                            </button> --}}
                                        @endcan

                                        @can('delete users')
                                            <button type="button" onclick="hapusConfirm('{{ $show->code_user }}')"
                                                class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
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

                    axios.delete(`/users/deleteUser/${userId}`, {
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

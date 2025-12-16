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
                <table id="example3" class="table table-bordered text-nowrap border-bottom align-middle">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>User</th>
                            <th>Email</th>
                            <th width="15%">Role</th>
                            <th width="15%">Dibuat</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datausers as $show)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <!-- User Info -->
                                <td>
                                    <div class="fw-semibold">{{ $show->fullname }}</div>
                                </td>

                                <td>{{ $show->email }}</td>

                                <!-- Role -->
                                <td>
                                    @foreach ($show->roles as $role)
                                        <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                </td>

                                <!-- Created -->
                                <td>
                                    {{ \Carbon\Carbon::parse($show->created)->format('d M Y') }}
                                </td>

                                <!-- Action -->
                                <td>
                                    @can('edit users')
                                        <!-- Edit Data User -->
                                        <a data-href="{{ route('users.edit', ['usercode' => $show->code_user]) }}"
                                            data-bs-title="Edit Users" data-bs-toggle="modal" data-bs-target="#dinamicModal"
                                            data-bs-backdrop="static" data-bs-keyboard="false"
                                            class="btn btn-sm btn-outline-primary mb-1">
                                            <i class="fe fe-edit"></i>
                                            Edit
                                        </a>

                                        <!-- Change Role -->
                                        {{-- <a data-href="{{ route('users.role', ['usercode' => $show->code_user]) }}"
                                            data-bs-title="Ubah Role" data-bs-toggle="modal" data-bs-target="#dinamicModal"
                                            data-bs-backdrop="static" data-bs-keyboard="false"
                                            class="btn btn-sm btn-outline-secondary mb-1">
                                            <i class="fe fe-shield"></i>
                                            Role
                                        </a> --}}
                                    @endcan

                                    @can('delete users')
                                        <button class="btn btn-sm btn-outline-danger mb-1"
                                            onclick="hapusConfirm('{{ $show->code_user }}')">
                                            <i class="fe fe-trash-2"></i>
                                            Hapus
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#example3').DataTable({
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

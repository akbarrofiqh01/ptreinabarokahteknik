@extends('layouts.app')
@section('title', 'Users - Siakad')
@section('title-content', 'Users')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Users</h3>
            <div class="card-options">
                @can('create users')
                    <a data-href="{{ route('users.create') }}" data-bs-title="Tambah Users" data-bs-remote="false"
                        data-bs-toggle="modal" data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                        class="btn btn-sm btn-primary text-white mb-1">
                        <i class="fe fe-plus-circle"></i> Tambah
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example3" class="table table-bordered text-nowrap border-bottom">
                    <thead>
                        <tr>
                            <th class="border-bottom-0" width="5%">No</th>
                            <th class="border-bottom-0">Nama</th>
                            <th class="border-bottom-0">Email</th>
                            <th class="border-bottom-0">Roles</th>
                            <th class="border-bottom-0">Dibuat</th>
                            <th class="border-bottom-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datausers as $show)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $show->name }}</td>
                                <td>{{ $show->email }}</td>
                                <td>{{ ucfirst($show->roles->pluck('name')->implode(',')) }}</td>
                                <td>{{ \Carbon\Carbon::parse($show->created)->format('d M, Y') }}</td>
                                <td>
                                    @can('edit users')
                                        <a data-href="{{ route('users.edit', ['usercode' => $show->code_user]) }}"
                                            data-bs-title="Edit Users" data-bs-remote="false" data-bs-toggle="modal"
                                            data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                            class="btn btn-sm btn-primary text-white mb-1">
                                            <i class="fe fe-edit"></i> Edit
                                        </a>
                                    @endcan
                                    @can('delete users')
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white mb-1"
                                            onclick="hapusConfirm('{{ $show->code_user }}')">
                                            <i class="fe fe-trash-2"></i> Hapus
                                        </a>
                                    @endcan

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border-bottom-0" width="5%">No</th>
                            <th class="border-bottom-0">Nama</th>
                            <th class="border-bottom-0">Email</th>
                            <th class="border-bottom-0">Roles</th>
                            <th class="border-bottom-0">Dibuat</th>
                            <th class="border-bottom-0">Aksi</th>
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

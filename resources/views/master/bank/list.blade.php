@extends('layouts.app')
@section('title', 'List Bank - PT Reina Barokah Teknik')
@section('title-content', 'List Bank')
@section('content')
    <div class="card h-100 p-0 radius-12">
        <div
            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <h5 class="card-title">List Bank</h5>
            @can('bank.create')
                <a data-href="{{ route('bank.create') }}" data-bs-title="Tambah Bank" data-bs-remote="false" data-bs-toggle="modal"
                    data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon> Bank
                </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="bnkTable" class="table table-bordered table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Bank</th>
                            <th>Rekening</th>
                            <th width="12%">Kode Bank</th>
                            <th>Dibuat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataBank as $show)
                            <tr>
                                <!-- No -->
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <!-- Bank -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div
                                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                                <span class="text-primary fw-bold">
                                                    {{ strtoupper(substr($show->account_bank, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-truncate" style="max-width: 180px;"
                                                title="{{ $show->account_bank }}">
                                                {{ $show->account_bank }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Rekening -->
                                <td>
                                    <div class="fw-semibold text-truncate" style="max-width: 200px;"
                                        title="{{ $show->account_name }}">
                                        {{ $show->account_name }}
                                    </div>
                                    <small class="text-muted d-block text-truncate" style="max-width: 200px;"
                                        title="{{ $show->account_number }}">
                                        {{ $show->account_number }}
                                    </small>
                                </td>

                                <!-- Kode Bank -->
                                <td class="text-center">
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                        {{ $show->account_bank_code }}
                                    </span>
                                </td>

                                <td>
                                    <div class="text-nowrap">
                                        <iconify-icon icon="solar:calendar-outline" class="me-1"></iconify-icon>
                                        {{ $show->created_at->format('d M Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $show->created_at->format('H:i') }}
                                    </small>
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap gap-1">

                                        @can('bank.edit')
                                            <button type="button" data-href="{{ route('bank.edit', $show->code_bank) }}"
                                                data-bs-title="Edit Bank" data-bs-toggle="modal" data-bs-target="#dinamicModal"
                                                data-bs-backdrop="static" data-bs-keyboard="false"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <iconify-icon icon="tabler:edit"></iconify-icon>
                                            </button>
                                        @endcan

                                        @can('bank.delete')
                                            <button type="button" onclick="hapusConfirm('{{ $show->code_bank }}')"
                                                class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
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
            $('#bnkTable').DataTable({
                responsive: true
            });
        });

        function hapusConfirm(userId) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ingin menghapus data bank ini !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.delete(`/data-master/data-bank/Deletebank/${userId}`, {
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

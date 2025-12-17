<style>
    .permissions-wrapper {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px 16px;
        max-height: 260px;
        overflow-y: auto;
        background-color: #fafafa;
    }

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

<form id="formUpdRoles">
    <div class="row">
        <div class="col-12 mb-20">
            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Name Roles</label>
            <input type="text" class="form-control" id="valNameUpd" placeholder="Entry name roles" autocomplete="off"
                value="{{ $dataRole->name }}">
        </div>
        @if ($dataPermissions->isNotEmpty())
            <div class="col-12 mb-20">
                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                    Permissions
                </label>

                <div class="permissions-wrapper">
                    <div class="d-flex flex-column gap-12">
                        @foreach ($dataPermissions as $permissions)
                            <div class="form-check checked-primary d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox"
                                    id="permission_upd_{{ $permissions->code_permissions }}" name="permissionsUpd[]"
                                    value="{{ $permissions->name }}"
                                    {{ $hasPermissions->contains($permissions->name) ? 'checked' : '' }}>

                                <label class="form-check-label fw-medium text-secondary-light text-sm"
                                    for="permission_upd_{{ $permissions->code_permissions }}">
                                    {{ $permissions->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <div class="col-12 mb-20">
            <button type="submit" id="SubmitBtn01"
                class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8 w-100">Ubah
                Data</button>
        </div>
        <div class="col-12 mb-20">
            <div id="loadingSpinner01"
                class="d-none btn btn-danger border border-danger-600 text-md px-48 py-12 radius-8 w-100">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Proses Ubah...
            </div>
        </div>
    </div>
</form>
<script>
    document.getElementById('formUpdRoles').addEventListener('submit', function(e) {
        e.preventDefault();

        const valNameUpd = document.getElementById('valNameUpd').value;
        const selectedPermissions = Array.from(document.querySelectorAll(
                'input[name="permissionsUpd[]"]:checked'))
            .map(checkbox => checkbox.value);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('SubmitBtn01').classList.add('d-none');
        document.getElementById('loadingSpinner01').classList.remove('d-none');

        axios.put('{{ route('roles.update', ['roleCode' => $dataRole->code_role]) }}', {
                name: valNameUpd,
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
                    errorMessages = 'Terjadi kesalahan saat mengupdate data.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: errorMessages
                });

                document.getElementById('SubmitBtn01').classList.remove('d-none');
                document.getElementById('loadingSpinner01').classList.add('d-none');
            });
    });
</script>

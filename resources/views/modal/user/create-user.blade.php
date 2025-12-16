<style>
    .password-field {
        position: relative;
    }

    .password-field .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #98a2b3;
    }

    .password-field .toggle-password:hover {
        color: #475467;
    }
</style>
<form id="formNewUsers">
    <div class="row gy-3">
        <!-- Nama Lengkap -->
        <div class="col-12">
            <label class="form-label">Nama Lengkap</label>
            <div class="icon-field">
                <span class="icon">
                    <iconify-icon icon="f7:person"></iconify-icon>
                </span>
                <input type="text" id="valName" name="name" class="form-control"
                    placeholder="Masukkan nama lengkap anda" autocomplete="off" />
            </div>
        </div>

        <!-- Email -->
        <div class="col-12">
            <label class="form-label">Email</label>
            <div class="icon-field">
                <span class="icon">
                    <iconify-icon icon="mage:email"></iconify-icon>
                </span>
                <input type="email" id="valEmail" name="email" class="form-control"
                    placeholder="Masukkan email anda" autocomplete="off" />
            </div>
        </div>

        <!-- Password -->
        <div class="col-12">
            <label class="form-label">Password</label>
            <div class="icon-field password-field">
                <span class="icon">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>

                <input type="password" id="valPassword" name="password" class="form-control" placeholder="********" />

                <span class="toggle-password" role="button">
                    <iconify-icon icon="solar:eye-closed-outline"></iconify-icon>
                </span>
            </div>
        </div>
        <!-- Konfirmasi Password -->
        <div class="col-12">
            <label class="form-label">Konfirmasi Password</label>
            <div class="icon-field password-field">
                <span class="icon">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>

                <input type="password" id="valConfirmPwd" name="password_confirmation" class="form-control"
                    placeholder="********" />

                <span class="toggle-password" role="button">
                    <iconify-icon icon="solar:eye-closed-outline"></iconify-icon>
                </span>
            </div>
        </div>
        <!-- Roles -->
        @if ($role->isNotEmpty())
            <div class="col-12">
                <label class="form-label fw-semibold">Roles</label>
                <div class="d-flex align-items-center flex-wrap gap-28 mt-2">
                    @foreach ($role as $rolesss)
                        <div class="form-check checked-primary d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" id="role_{{ $rolesss->code_role }}"
                                name="role[]" value="{{ $rolesss->name }}" />
                            <label class="form-check-label fw-medium text-secondary-light text-sm"
                                for="role_{{ $rolesss->code_role }}">
                                {{ ucfirst($rolesss->name) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Submit -->
        <div class="col-12 mt-3">
            <button type="submit" id="SubmitsBtn" class="btn btn-primary-600 w-100">
                Tambah Data
            </button>

            <div id="loadingSpinners" class="d-none btn btn-danger w-100 mt-2">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Proses Tambah...
            </div>
        </div>

    </div>

</form>
<script>
    document.getElementById('formNewUsers').addEventListener('submit', function(e) {
        e.preventDefault();

        const valName = document.getElementById('valName').value;
        const valEmail = document.getElementById('valEmail').value;
        const valPassword = document.getElementById('valPassword').value;
        const valConfirmPwd = document.getElementById('valConfirmPwd').value;
        const role = Array.from(document.querySelectorAll('input[name="role[]"]:checked'))
            .map(checkbox => checkbox.value) || [];
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('SubmitsBtn').classList.add('d-none');
        document.getElementById('loadingSpinners').classList.remove('d-none');

        axios.put('{{ route('users.store') }}', {
                name: valName,
                email: valEmail,
                password: valPassword,
                confirm: valConfirmPwd,
                role: role,
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

                document.getElementById('SubmitsBtn').classList.remove('d-none');
                document.getElementById('loadingSpinners').classList.add('d-none');
            });
    });

    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const field = this.closest('.password-field');
            const input = field.querySelector('input');
            const icon = this.querySelector('iconify-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('icon', 'solar:eye-outline');
            } else {
                input.type = 'password';
                icon.setAttribute('icon', 'solar:eye-closed-outline');
            }
        });
    });
</script>

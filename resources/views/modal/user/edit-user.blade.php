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

<form id="formEditUsers">
    <div class="row gy-3">
        @if (isset($userdata->username))
            <div class="col-12">
                <label class="form-label">Username</label>
                <div class="icon-field">
                    <span class="icon">
                        <iconify-icon icon="f7:person"></iconify-icon>
                    </span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username anda"
                        autocomplete="off" value="{{ old('username', $userdata->username ?? '') }}" />
                </div>
            </div>
        @endif

        @if (isset($userdata->nik))
            <div class="col-12">
                <label class="form-label">NIK <small>(Nomor Induk Kependudukan)</small></label>
                <div class="icon-field">
                    <span class="icon">
                        <iconify-icon icon="f7:person"></iconify-icon>
                    </span>
                    <input type="text" name="nik" class="form-control" placeholder="Masukkan nik lengkap anda"
                        autocomplete="off" value="{{ old('nik', $userdata->nik ?? '') }}" />
                </div>
            </div>
        @endif

        <div class="col-sm-12 col-md-6 col-lg-6">
            <label class="form-label">Nama Lengkap</label>
            <div class="icon-field">
                <span class="icon">
                    <iconify-icon icon="f7:person"></iconify-icon>
                </span>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap anda"
                    autocomplete="off" value="{{ old('name', $userdata->name) }}" />
            </div>
        </div>

        <!-- Email -->
        <div class="col-sm-12 col-md-6 col-lg-6">
            <label class="form-label">Email</label>
            <div class="icon-field">
                <span class="icon">
                    <iconify-icon icon="mage:email"></iconify-icon>
                </span>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email anda"
                    autocomplete="off" value="{{ old('email', $userdata->email) }}" />
            </div>
        </div>

        <!-- No Telp (jika ada) -->
        @if (isset($userdata->phone))
            <div class="col-12">
                <label class="form-label">No Telp</label>
                <div class="icon-field">
                    <span class="icon">
                        <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
                    </span>
                    <input type="tel" name="phone" class="form-control" placeholder="Masukkan nomor telpon anda"
                        autocomplete="off" value="{{ old('phone', $userdata->phone ?? '') }}" />
                </div>
            </div>
        @endif

        <div class="col-12">
            <label class="form-label">Password </label>
            <div class="icon-field password-field">
                <span class="icon">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>
                <input type="password" name="password" class="form-control" placeholder="********" />
                <span class="toggle-password" role="button">
                    <iconify-icon icon="solar:eye-closed-outline"></iconify-icon>
                </span>
            </div>
            <small class="text-muted text-danger-500">*(Kosongkan jika tidak ingin
                mengubah)</small>
        </div>

        <div class="col-12">
            <label class="form-label">Konfirmasi Password</label>
            <div class="icon-field password-field">
                <span class="icon">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>
                <input type="password" name="password_confirmation" class="form-control" placeholder="********" />
                <span class="toggle-password" role="button">
                    <iconify-icon icon="solar:eye-closed-outline"></iconify-icon>
                </span>
            </div>
        </div>

        <!-- Roles -->
        @if ($roles->isNotEmpty())
            <div class="col-12">
                <label class="form-label fw-semibold">Roles</label>
                <div class="d-flex align-items-center flex-wrap gap-28 mt-2">
                    @foreach ($roles as $role)
                        <div class="form-check checked-primary d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" id="role_{{ $role->code_role }}"
                                name="role[]" value="{{ $role->name }}"
                                {{ $hasRoles->contains($role->id) ? 'checked' : '' }} />
                            <label class="form-check-label fw-medium text-secondary-light text-sm"
                                for="role_{{ $role->code_role }}">
                                {{ ucfirst($role->name) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Submit -->
        <div class="col-12 mt-3">
            <button type="submit" id="SubmitBtn" class="btn btn-primary-600 w-100">
                Ubah Data
            </button>

            <div id="loadingSpinner" class="d-none btn btn-danger w-100 mt-2">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Proses Ubah...
            </div>
        </div>

    </div>
</form>

<script>
    document.getElementById('formEditUsers').addEventListener('submit', function(e) {
        e.preventDefault();

        // Membuat FormData dari form
        const formData = new FormData(this);

        // Tambahkan method spoofing untuk PUT (Laravel)
        formData.append('_method', 'PUT');

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('SubmitBtn').classList.add('d-none');
        document.getElementById('loadingSpinner').classList.remove('d-none');

        axios.post('{{ route('users.update', ['usercode' => $userdata->code_user]) }}', formData, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'multipart/form-data'
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

                // Update CSRF token jika expired
                if (error.response && error.response.data && error.response.data.csrf_token) {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = error.response.data.csrf_token;
                    const meta = document.querySelector('meta[name="csrf-token"]');
                    if (meta) {
                        meta.setAttribute('content', error.response.data.csrf_token);
                    }
                }

                // Menangani error validasi
                if (error.response && error.response.status === 422 && error.response.data.errors) {
                    Object.values(error.response.data.errors).forEach(function(messages) {
                        messages.forEach(function(message) {
                            errorMessages += `${message}<br>`;
                        });
                    });
                } else if (error.response && error.response.data.message) {
                    errorMessages = `${error.response.data.message}<br>`;
                } else {
                    errorMessages = 'Terjadi kesalahan saat mengubah data.';
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

    // Toggle password visibility
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

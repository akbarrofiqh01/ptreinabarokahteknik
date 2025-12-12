<form id="formNewUsers">
    <div class="form-group">
        <label for="">Nama Lengkap</label>
        <input type="text" class="form-control" id="valName" placeholder="Masukkan nama lengkap anda"
            autocomplete="off">
    </div>
    <div class="form-group">
        <label for="">Email</label>
        <input type="email" class="form-control" id="valEmail" placeholder="Masukkan email anda" autocomplete="off">
    </div>
    <div class="form-group">
        <label class="form-label">Password</label>
        <div class="wrap-input100 validate-input input-group" id="Password-toggle1">
            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
            </a>
            <input class="input100 form-control" type="password" placeholder="New Password" id="valPassword"
                name="password">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Konfirmasi Password</label>
        <div class="wrap-input100 validate-input input-group" id="Password-toggle2">
            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
            </a>
            <input class="input100 form-control" type="password" placeholder="Konfirmasi Password" id="valConfirmPwd"
                name="password_confirmation">
        </div>
    </div>


    @if ($role->isNotEmpty())
        @foreach ($role as $rolesss)
            <div class="form-group">
                <label class="custom-switch form-switch">
                    <input type="checkbox" class="custom-switch-input" id="role_{{ $rolesss->code_role }}"
                        name="role[]" value="{{ $rolesss->name }}">
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">{{ ucfirst($rolesss->name) }}</span>
                </label>
            </div>
        @endforeach
    @endif

    <div class="form-group">
        <button type="submit" id="SubmitsBtn" class="btn btn-sm btn-primary btn-block btn-space mb-0">Tambah
            Data</button>
        <div id="loadingSpinners" class="d-none btn btn-sm btn-danger btn-block btn-space mb-0">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Proses Tambah...
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
</script>

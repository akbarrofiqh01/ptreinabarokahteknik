<form id="formpUsers">
    <div class="form-group">
        <label for="">Nama Lengkap</label>
        <input type="text" class="form-control" id="valName" placeholder="Masukkan nama lengkap anda" autocomplete="off"
            value="{{ $userdata->name }}">
    </div>
    <div class="form-group">
        <label for="">Email</label>
        <input type="email" class="form-control" id="valEmail" placeholder="Masukkan email anda" autocomplete="off"
            value="{{ $userdata->email }}">
    </div>

    @if ($roles->isNotEmpty())
        @foreach ($roles as $role)
            <div class="form-group">
                <label class="custom-switch form-switch">
                    <input type="checkbox" class="custom-switch-input" id="role_{{ $role->code_role }}" name="role[]"
                        value="{{ $role->name }}" {{ $hasRoles->contains($role->id) ? 'checked' : '' }}>
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">{{ ucfirst($role->name) }}</span>
                </label>
            </div>
        @endforeach
    @endif

    <div class="form-group">
        <button type="submit" id="SubmitBtn" class="btn btn-sm btn-block btn-primary btn-space mb-0">Ubah
            Data</button>
        <div id="loadingSpinner" class="d-none btn btn-sm btn-block btn-danger btn-space mb-0">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Proses Ubah...
        </div>
    </div>
</form>
<script>
    document.getElementById('formpUsers').addEventListener('submit', function(e) {
        e.preventDefault();

        const valName = document.getElementById('valName').value;
        const valEmail = document.getElementById('valEmail').value;
        const role = Array.from(document.querySelectorAll('input[name="role[]"]:checked'))
            .map(checkbox => checkbox.value) || [];
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('SubmitBtn').classList.add('d-none');
        document.getElementById('loadingSpinner').classList.remove('d-none');

        axios.put('{{ route('users.update', ['usercode' => $userdata->code_user]) }}', {
                name: valName,
                email: valEmail,
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
</script>

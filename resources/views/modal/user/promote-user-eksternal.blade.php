<form id="formPermitInternal">
    <div class="row gy-3">

        <!-- Nama -->
        <div class="col-12">
            <label class="form-label">Nama Lengkap</label>
            <div class="icon-field">
                <span class="icon">
                    <iconify-icon icon="f7:person"></iconify-icon>
                </span>
                <input type="text" class="form-control" value="{{ $user->name }}" readonly>
            </div>
        </div>

        <!-- Email -->
        <div class="col-12">
            <label class="form-label">Email</label>
            <div class="icon-field">
                <span class="icon">
                    <iconify-icon icon="mage:email"></iconify-icon>
                </span>
                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
            </div>
        </div>

        <!-- ALERT -->
        <div class="col-12">
            <div class="alert alert-warning">
                ⚠️ User ini akan dipromosikan menjadi <strong>INTERNAL</strong>
                dan dapat mengakses sistem sesuai role.
            </div>
        </div>

        <!-- ROLE -->
        <div class="col-12">
            <label class="form-label fw-semibold">Pilih Role Internal</label>
            <div class="d-flex align-items-center flex-wrap gap-28 mt-2">
                @foreach ($roles as $role)
                    <div class="form-check checked-primary d-flex align-items-center gap-2">
                        <input class="form-check-input" type="radio" id="role_{{ $role->code_role }}" name="role"
                            value="{{ $role->name }}" required>
                        <label class="form-check-label fw-medium text-secondary-light text-sm"
                            for="role_{{ $role->code_role }}">
                            {{ ucfirst($role->name) }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="col-12 mt-3">
            <button type="submit" id="SubmitBtn" class="btn btn-info w-100">
                Promote ke Internal
            </button>

            <div id="loadingSpinner" class="d-none btn btn-danger w-100 mt-2">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Proses Promote...
            </div>
        </div>

    </div>
</form>
<script>
    document.getElementById('formPermitInternal').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('SubmitBtn').classList.add('d-none');
        document.getElementById('loadingSpinner').classList.remove('d-none');

        axios.post(
                '{{ route('usersEksternal.permitInternal.store', $user->code_user) }}',
                formData, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
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
                }

                if (error.response && error.response.status === 422) {
                    Object.values(error.response.data.errors).forEach(msgs => {
                        msgs.forEach(msg => errorMessages += msg + '<br>');
                    });
                } else if (error.response?.data?.message) {
                    errorMessages = error.response.data.message;
                } else {
                    errorMessages = 'Terjadi kesalahan saat permit user.';
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

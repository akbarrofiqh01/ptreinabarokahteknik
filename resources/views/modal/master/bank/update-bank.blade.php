<form id="formUpdBank">
    <div class="row gy-3">
        <div class="col-12">
            <label class="form-label">Rekening Atas Nama</label>
            <input type="text" name="valbank" class="form-control" placeholder="Rekening Atas Nama" autocomplete="off"
                value="{{ $dataBank->account_name }}" />
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            <label class="form-label">Kode Bank</label>
            <input type="text" name="valbank_kd" class="form-control" placeholder="Kode Bank" autocomplete="off"
                value="{{ $dataBank->account_bank_code }}" />
        </div>
        <div class="col-sm-12 col-md-4
                col-lg-4">
            <label class="form-label">Nama Bank</label>
            <input type="text" name="valbank_name" class="form-control" placeholder="Nama Bank" autocomplete="off"
                value="{{ $dataBank->account_bank }}" />
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            <label class="form-label">Nomor Rekening Bank</label>
            <input type="text" name="valbank_rek" class="form-control" placeholder="Nomor Rekening Bank"
                autocomplete="off" value="{{ $dataBank->account_number }}" />
        </div>
        <div class="col-12
                mt-3">
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
    document.getElementById('formUpdBank').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_method', 'PUT');

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.getElementById('SubmitBtn').classList.add('d-none');
        document.getElementById('loadingSpinner').classList.remove('d-none');

        axios.post('{{ route('bank.update', ['bnkcode' => $dataBank->code_bank]) }}', formData, {
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
</script>

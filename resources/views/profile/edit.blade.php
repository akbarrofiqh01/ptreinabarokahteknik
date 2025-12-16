@extends('layouts.app')
@section('title', 'Profile - Siakad')
@section('title-content', 'Profile')
@section('content')
    @php
        $avatarUrl = auth()->user()->avatar
            ? asset('storage/' . auth()->user()->avatar)
            : asset('assets/backend/images/default-avatar.png');
    @endphp
    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                <img src="{{ asset('assets/backend/images/user-grid/user-grid-bg1.png') }}" alt="Image"
                    class="w-100 object-fit-cover">
                <div class="pb-24 ms-16 mb-24 me-16  mt--100">
                    <div class="text-center border border-top-0 border-start-0 border-end-0">
                        <img id="profileAvatarTop" src="{{ $avatarUrl }}" alt="Image"
                            class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">
                        <h6 class="mb-0 mt-16" id="displayHeadFullname">{{ Auth()->user()->name }}</h6>
                        <span class="text-secondary-light mb-16" id="displayHeadEmail">{{ Auth()->user()->email }}</span>
                    </div>
                    <div class="mt-24" id="personalInfoSection">
                        <h6 class="text-xl mb-16">Personal Info</h6>
                        <ul>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Nama</span>
                                <span class="w-70 text-secondary-light fw-medium" id="displayFullname">
                                    : {{ Auth()->user()->fullname }}
                                </span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Email</span>
                                <span class="w-70 text-secondary-light fw-medium" id="displayEmail">
                                    : {{ Auth()->user()->email }}
                                </span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> No Telp</span>
                                <span class="w-70 text-secondary-light fw-medium" id="displayPhone">
                                    : {{ Auth()->user()->user_phone }}
                                </span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Status</span>
                                <span class="w-70 text-secondary-light fw-medium" id="displayStatus">
                                    :
                                    @if (Auth()->user()->is_active == 1)
                                        <span
                                            class="bg-success-focus text-success-600 border border-success-main px-12 py-2 radius-4 fw-small text-xs">Active</span>
                                    @else
                                        <span
                                            class="bg-danger-focus text-danger-600 border border-danger-main px-12 py-2 radius-4 fw-small text-xs">Inactive</span>
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body p-24">
                    <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab"
                                aria-controls="pills-edit-profile" aria-selected="true">
                                Edit Profile
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab"
                                aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                                Change Password
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                            <div class="mb-24 mt-16">
                                <div class="avatar-upload">
                                    <div
                                        class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                        <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                        <label for="imageUpload"
                                            class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                            <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                        </label>
                                    </div>

                                    <div class="avatar-preview">
                                        <div id="imagePreview"
                                            style="
                                                width:150px;
                                                height:150px;
                                                background-size:cover;
                                                background-position:center;
                                                border-radius:50%;
                                                background-image:url('{{ $avatarUrl }}');
                                            ">
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    const csrf = document.querySelector('meta[name="csrf-token"]').content;

                                    document.getElementById("imageUpload").addEventListener("change", function(event) {
                                        let file = event.target.files[0];
                                        if (!file) return;

                                        let reader = new FileReader();
                                        reader.onload = function(e) {
                                            document.getElementById("imagePreview").style.backgroundImage = `url(${e.target.result})`;
                                        };
                                        reader.readAsDataURL(file);

                                        let formData = new FormData();
                                        formData.append("avatar", file);

                                        axios.post("{{ route('profile.avatar') }}", formData, {
                                                headers: {
                                                    "X-CSRF-TOKEN": csrf,
                                                    "Content-Type": "multipart/form-data"
                                                }
                                            })
                                            .then(res => {
                                                document.getElementById("imagePreview").style.backgroundImage =
                                                    `url(${res.data.avatar_url})`;
                                                if (document.getElementById("profileAvatarTop")) {
                                                    document.getElementById("profileAvatarTop").src = res.data.avatar_url;
                                                }
                                                if (document.getElementById("profileAvatarSidebar")) {
                                                    document.getElementById("profileAvatarSidebar").src = res.data.avatar_url;
                                                }

                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil!',
                                                    text: res.data.message,
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                });
                                            })
                                            .catch(err => {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Upload Gagal',
                                                    text: err.response?.data?.message ?? 'Terjadi kesalahan. Coba lagi.',
                                                });
                                                console.error(err);
                                            });
                                    });
                                </script>
                            </div>
                            <form id="formEditProfile">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap
                                                <span class="text-danger-600">*</span></label>
                                            <input type="text" class="form-control radius-8" name="fullname"
                                                placeholder="Masukkan nama lengkap anda...."
                                                value="{{ Auth()->user()->fullname }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-20">
                                            <label for="email"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                                    class="text-danger-600">*</span></label>
                                            <input type="email" class="form-control radius-8" name="email"
                                                placeholder="Masukkan email anda...." value="{{ Auth()->user()->email }}"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-20">
                                            <label for="number"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">No
                                                Telp</label>
                                            <input type="text" class="form-control radius-8" name="usr_number"
                                                placeholder="Masukkan no telp anda...."
                                                value="{{ Auth()->user()->user_phone }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit" id="SubmitBtn"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8 w-100">
                                        Ubah Profile
                                    </button>
                                    <button type="button" id="loadingSpinner"
                                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8 w-100 d-none"
                                        disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Proses Ubah...
                                    </button>
                                </div>
                            </form>
                            <script>
                                document.getElementById('formEditProfile').addEventListener('submit', function(e) {
                                    e.preventDefault();

                                    const form = this;
                                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                                    let formData = new FormData(form);
                                    formData.append('_method', 'PATCH');

                                    document.getElementById('SubmitBtn').classList.add('d-none');
                                    document.getElementById('loadingSpinner').classList.remove('d-none');

                                    axios.post("{{ route('profile.update') }}", formData, {
                                            headers: {
                                                "X-CSRF-TOKEN": token,
                                                "Content-Type": "multipart/form-data"
                                            }
                                        })
                                        .then(function(response) {
                                            const message = response.data.message || 'Profil berhasil diperbarui.';

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil',
                                                text: message,
                                                timer: 1500,
                                                showConfirmButton: false
                                            }).then(() => {
                                                if (response.data.data) {
                                                    document.querySelector('input[name="fullname"]').value = response.data.data
                                                        .fullname || '';
                                                    document.querySelector('input[name="email"]').value = response.data.data
                                                        .email || '';
                                                    document.querySelector('input[name="usr_number"]').value = response.data
                                                        .data.user_phone || '';
                                                }
                                                updatePersonalInfoDisplay(response.data.data);
                                            });
                                        })
                                        .catch(function(error) {
                                            let errorMessage = '';

                                            // Jika ada error validasi dari request
                                            if (error.response?.status === 422) {
                                                const errors = error.response.data.errors;
                                                Object.values(errors).forEach(messages => {
                                                    messages.forEach(msg => errorMessage += `${msg}<br>`);
                                                });
                                            }
                                            // Jika controller mengembalikan pesan error
                                            else if (error.response?.data?.message) {
                                                errorMessage = error.response.data.message;
                                            }
                                            // Pesan default
                                            else {
                                                errorMessage = "Terjadi kesalahan saat memperbarui data.";
                                            }

                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Gagal',
                                                html: errorMessage
                                            });
                                        })
                                        .finally(function() {
                                            document.getElementById('SubmitBtn').classList.remove('d-none');
                                            document.getElementById('loadingSpinner').classList.add('d-none');
                                        });
                                });

                                function updatePersonalInfoDisplay(data) {
                                    if (!data) return;

                                    if (data.fullname && document.getElementById('displayHeadFullname')) {
                                        document.getElementById('displayHeadFullname').innerHTML = `${data.fullname}`;
                                    }

                                    if (data.email && document.getElementById('displayHeadEmail')) {
                                        document.getElementById('displayHeadEmail').innerHTML = `${data.email}`;
                                    }

                                    if (data.fullname && document.getElementById('displayFullname')) {
                                        document.getElementById('displayFullname').innerHTML = `: ${data.fullname}`;
                                    }

                                    if (data.email && document.getElementById('displayEmail')) {
                                        document.getElementById('displayEmail').innerHTML = `: ${data.email}`;
                                    }

                                    if (data.user_phone !== undefined && document.getElementById('displayPhone')) {
                                        const phoneDisplay = data.user_phone ? `: ${data.user_phone}` : ': -';
                                        document.getElementById('displayPhone').innerHTML = phoneDisplay;
                                    }

                                    if (data.is_active !== undefined && document.getElementById('displayStatus')) {
                                        const statusHTML = data.is_active == 1 ?
                                            ': <span class="bg-success-focus text-success-600 border border-success-main px-12 py-2 radius-4 fw-small text-xs">Active</span>' :
                                            ': <span class="bg-danger-focus text-danger-600 border border-danger-main px-12 py-2 radius-4 fw-small text-xs">Inactive</span>';
                                        document.getElementById('displayStatus').innerHTML = statusHTML;
                                    }
                                }
                            </script>

                        </div>

                        <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel"
                            aria-labelledby="pills-change-passwork-tab" tabindex="0">
                            <form id="formUpdatePassword">
                                <div class="mb-20">
                                    <label for="current_password"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Password Saat Ini <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control radius-8" name="current_password"
                                            id="current_password" placeholder="Masukkan password saat ini">
                                        <span
                                            class="toggle-password ri-eye-off-line cursor-pointer position-absolute end-0 top-50
                       translate-middle-y me-16 text-secondary-light"
                                            data-target="#current_password"></span>
                                    </div>
                                </div>

                                <div class="mb-20">
                                    <label for="password" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Password Baru <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control radius-8" name="password"
                                            id="password" placeholder="Masukkan password baru">
                                        <span
                                            class="toggle-password ri-eye-off-line cursor-pointer position-absolute end-0 top-50
                       translate-middle-y me-16 text-secondary-light"
                                            data-target="#password"></span>
                                    </div>
                                </div>

                                <div class="mb-20">
                                    <label for="password_confirmation"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Konfirmasi Password Baru <span class="text-danger-600">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control radius-8" name="password_confirmation"
                                            id="password_confirmation" placeholder="Konfirmasi password baru">
                                        <span
                                            class="toggle-password ri-eye-off-line cursor-pointer position-absolute end-0 top-50
                       translate-middle-y me-16 text-secondary-light"
                                            data-target="#password_confirmation"></span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit" id="submitPasswordBtn"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8 w-100">
                                        Ubah Password
                                    </button>
                                    <button type="button" id="loadingPasswordSpinner"
                                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8 w-100 d-none"
                                        disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Memproses...
                                    </button>
                                </div>
                            </form>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.addEventListener('click', function(e) {
                                        if (e.target.classList.contains('toggle-password')) {
                                            const targetId = e.target.getAttribute('data-target');
                                            const passwordInput = document.querySelector(targetId);

                                            if (passwordInput) {
                                                const type = passwordInput.getAttribute('type') === 'password' ? 'text' :
                                                    'password';
                                                passwordInput.setAttribute('type', type);

                                                e.target.classList.toggle('ri-eye-off-line');
                                                e.target.classList.toggle('ri-eye-line');
                                            }
                                        }
                                    });
                                });
                                document.getElementById('formUpdatePassword').addEventListener('submit', function(e) {
                                    e.preventDefault();

                                    const form = this;
                                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                                    let formData = new FormData(form);

                                    // Tampilkan loading, sembunyikan tombol submit
                                    document.getElementById('submitPasswordBtn').classList.add('d-none');
                                    document.getElementById('loadingPasswordSpinner').classList.remove('d-none');

                                    // Kirim request ke endpoint update password
                                    axios.post("{{ route('profile.password.update') }}", formData, {
                                            headers: {
                                                "X-CSRF-TOKEN": token,
                                                "Content-Type": "multipart/form-data"
                                            }
                                        })
                                        .then(function(response) {
                                            const message = response.data.message || 'Password berhasil diperbarui.';

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: message,
                                                timer: 2000,
                                                showConfirmButton: false
                                            }).then(() => {
                                                // Reset form setelah sukses
                                                form.reset();
                                                // Reset semua input password ke type="password"
                                                document.querySelectorAll('.toggle-password').forEach(icon => {
                                                    icon.classList.add('ri-eye-line');
                                                    icon.classList.remove('ri-eye-off-line');
                                                });
                                                document.querySelectorAll('input[type="text"][name*="password"]').forEach(
                                                    input => {
                                                        input.setAttribute('type', 'password');
                                                    });
                                            });
                                        })
                                        .catch(function(error) {
                                            let errorMessage = '';

                                            // Jika ada error validasi dari request
                                            if (error.response?.status === 422) {
                                                const errors = error.response.data.errors;
                                                Object.values(errors).forEach(messages => {
                                                    messages.forEach(msg => errorMessage += `${msg}<br>`);
                                                });
                                            }
                                            // Jika controller mengembalikan pesan error
                                            else if (error.response?.data?.message) {
                                                errorMessage = error.response.data.message;
                                            }
                                            // Pesan default
                                            else {
                                                errorMessage = "Terjadi kesalahan saat mengupdate password.";
                                            }

                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Gagal',
                                                html: errorMessage
                                            });
                                        })
                                        .finally(function() {
                                            document.getElementById('submitPasswordBtn').classList.remove('d-none');
                                            document.getElementById('loadingPasswordSpinner').classList.add('d-none');
                                        });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

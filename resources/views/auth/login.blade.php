@extends('layouts.guest')
@section('title', 'Login - PT Reina Barokah Teknik')
@section('content')
    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ asset('assets/backend/logo_png.png') }}" alt="Image">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <a href="{{ route('login') }}" class="mb-40 max-w-290-px" style="width: 168px;height: 40px;">
                        <img src="{{ asset('assets/backend/logo_landscape.png') }}" alt="Image">
                    </a>
                    <h4 class="mb-12">Selamat Datang</h4>
                    <p class="mb-32 text-secondary-light text-lg">Silahkan masukkan akun anda
                    </p>
                </div>
                <form id="loginForm">
                    @csrf
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="f7:person"></iconify-icon>
                        </span>
                        <input type="text" id="auth_email" class="form-control h-56-px bg-neutral-50 radius-12"
                            placeholder="Email / Username">
                    </div>

                    <div class="position-relative mb-20">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" class="form-control h-56-px bg-neutral-50 radius-12" id="auth_password"
                                placeholder="Password">
                        </div>
                        <span
                            class="toggle-password ri-eye-off-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                            data-toggle="#auth_password"></span>
                    </div>

                    <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32"
                        id="loginBtn">
                        Login</button>

                    <div id="loadingSpinner" class="d-none btn btn-danger text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Proses Login...
                    </div>

                </form>

                <script>
                    document.getElementById('loginForm').addEventListener('submit', function(e) {
                        e.preventDefault();

                        const login = document.getElementById('auth_email').value;
                        const password = document.getElementById('auth_password').value;
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        const formData = new FormData();
                        formData.append('login', login);
                        formData.append('password', password);

                        document.getElementById('loginBtn').classList.add('d-none');
                        document.getElementById('loadingSpinner').classList.remove('d-none');

                        axios.post('{{ route('doLogin') }}', formData, {
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
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = response.data.redirect || '/dashboard';
                                });
                            })
                            .catch(function(error) {
                                let errorMessages = '';

                                // refresh CSRF jika expired
                                if (error.response && error.response.data && error.response.data.csrf_token) {
                                    axios.defaults.headers.common['X-CSRF-TOKEN'] = error.response.data.csrf_token;
                                    const meta = document.querySelector('meta[name="csrf-token"]');
                                    if (meta) meta.setAttribute('content', error.response.data.csrf_token);
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
                                    errorMessages = 'Terjadi kesalahan saat login.';
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Login Gagal',
                                    html: errorMessages
                                });

                                document.getElementById('loginBtn').classList.remove('d-none');
                                document.getElementById('loadingSpinner').classList.add('d-none');
                            });
                    });

                    document.addEventListener("DOMContentLoaded", function() {
                        const toggleBtn = document.querySelector(".toggle-password");
                        const target = document.querySelector(toggleBtn.getAttribute("data-toggle"));

                        toggleBtn.addEventListener("click", function() {
                            const isPassword = target.getAttribute("type") === "password";
                            target.setAttribute("type", isPassword ? "text" : "password");

                            // toggle icon
                            toggleBtn.classList.remove("ri-eye-off-line", "ri-eye-line");
                            toggleBtn.classList.add(isPassword ? "ri-eye-line" : "ri-eye-off-line");
                        });
                    });
                </script>
                <div class="mt-32 center-border-horizontal text-center">
                    <span class="bg-base z-1 px-4"></span>
                </div>
                <div class="mt-32 text-center text-sm">
                    <p class="mb-0">Belum Punya Akun ? <a href="{{ route('register') }}"
                            class="text-primary-600 fw-semibold">Registrasi</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection

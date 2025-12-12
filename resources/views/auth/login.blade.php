@extends('layouts.guest')
@section('title', 'Login - Laravel Spatie')
@section('content')
    <form id="loginForm">
        @csrf
        <div class="icon-field mb-16">
            <span class="icon top-50 translate-middle-y">
                <iconify-icon icon="mage:email"></iconify-icon>
            </span>
            <input type="email" id="auth_email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email">
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

        <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32" id="loginBtn">
            Login</button>

        <div id="loadingSpinner" class="d-none btn btn-danger text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Proses Login...
        </div>

    </form>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('auth_email').value;
            const password = document.getElementById('auth_password').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.getElementById('loginBtn').classList.add('d-none');
            document.getElementById('loadingSpinner').classList.remove('d-none');

            axios.post('{{ route('doLogin') }}', {
                    email: email,
                    password: password,
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
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.data.redirect || '/dashboard';
                    });
                })
                .catch(function(error) {
                    let errorMessages = '';

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
@endsection

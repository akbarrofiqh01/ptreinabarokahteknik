@extends('layouts.guest')
@section('title', 'Registrasi - Siakad')
@section('content')
    <form class="login100-form validate-form" id="loginForm">
        <span class="login100-form-title">
            Registrasi
        </span>
        <div class="wrap-input100 validate-input input-group">
            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                <i class="mdi mdi-account" aria-hidden="true"></i>
            </a>
            <input class="input100 border-start-0 ms-0 form-control" name="name" id="name" type="text"
                placeholder="Nama Lengkap" autocomplete="off">
        </div>
        <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                <i class="zmdi zmdi-email" aria-hidden="true"></i>
            </a>
            <input class="input100 border-start-0 ms-0 form-control" name="email" id="email" type="email"
                placeholder="Email" autocomplete="off">
        </div>
        <div class="wrap-input100 validate-input input-group">
            <a href="javascript:void(0)" class="input-group-text bg-white text-muted toggle-password"
                data-target="#pwdBaru">
                <i class="zmdi zmdi-eye" aria-hidden="true"></i>
            </a>
            <input class="input100 form-control" type="password" id="password" placeholder="Password">
        </div>
        <div class="wrap-input100 validate-input input-group">
            <a href="javascript:void(0)" class="input-group-text bg-white text-muted toggle-password"
                data-target="#confirmPwdBaru">
                <i class="zmdi zmdi-eye" aria-hidden="true"></i>
            </a>
            <input class="input100 form-control" type="password" id="password_confirmation"
                placeholder="Konfirmasi Password">
        </div>
        <div class="container-login100-form-btn">
            <button type="submit" id="RegBtn" class="login100-form-btn btn-primary">Register</button>
            <div id="loadingSpinner" class="d-none login100-form-btn btn-danger">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Proses Register...
            </div>
        </div>
        <div class="text-center pt-3">
            <p class="text-dark mb-0">Sudah ada akun?<a href="{{ route('login') }}" class="text-primary ms-1">Login</a></p>
        </div>
    </form>
    <script>
        $(document).on('click', '.toggle-password', function() {
            const input = $($(this).data('target'));
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('zmdi-eye').addClass('zmdi-eye-off');
            } else {
                input.attr('type', 'password');
                icon.removeClass('zmdi-eye-off').addClass('zmdi-eye');
            }
        });
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.getElementById('RegBtn').classList.add('d-none');
            document.getElementById('loadingSpinner').classList.remove('d-none');

            axios.post('{{ route('register') }}', {
                    name: name,
                    email: email,
                    password: password,
                    password_confirmation: password_confirmation,
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
                        window.location.href = response.data.redirect
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
                        errorMessages = 'Terjadi kesalahan saat registrasi.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: errorMessages
                    });

                    document.getElementById('RegBtn').classList.remove('d-none');
                    document.getElementById('loadingSpinner').classList.add('d-none');
                });
        });
    </script>
@endsection

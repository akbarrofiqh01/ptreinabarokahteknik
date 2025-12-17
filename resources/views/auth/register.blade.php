@extends('layouts.guest')
@section('title', 'Registrasi - PT Reina Barokah Teknik')
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
                    <h4 class="mb-12">Form Registrasi</h4>
                    <p class="mb-32 text-secondary-light text-lg">Silahkan isi data diri anda
                    </p>
                </div>
                <div class="form-wizard">
                    <form id="registerForm">
                        @csrf
                        <div class="form-wizard-header overflow-x-auto scroll-sm pb-8 my-32">
                            <ul class="list-unstyled form-wizard-list style-three">
                                <li class="form-wizard-list__item d-flex align-items-center gap-8 active">
                                    <div class="form-wizard-list__line">
                                        <span class="count">1</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Data Personal</span>
                                </li>
                                <li class="form-wizard-list__item d-flex align-items-center gap-8">
                                    <div class="form-wizard-list__line">
                                        <span class="count">2</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Data Perusahaan</span>
                                </li>
                            </ul>
                        </div>
                        <fieldset class="wizard-fieldset show">
                            <h6 class="text-md text-neutral-500">Data Personal</h6>

                            <div class="row gy-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control wizard-required" name="name"
                                        placeholder="Nama Lengkap" autocomplete="off">
                                    <div class="wizard-form-error"></div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control wizard-required" name="username"
                                        placeholder="Username" autocomplete="off">
                                    <div class="wizard-form-error"></div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control wizard-required" name="email"
                                        placeholder="Email" autocomplete="off">
                                    <div class="wizard-form-error"></div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">NIK</label>
                                    <input type="text" class="form-control" name="nik"
                                        placeholder="Nomor Induk Keluarga" autocomplete="off">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">No. Telp</label>
                                    <input type="text" class="form-control" name="phone" placeholder="No. Telp"
                                        autocomplete="off">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control wizard-required" name="password"
                                        placeholder="Password" autocomplete="off">
                                    <div class="wizard-form-error"></div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control wizard-required" name="password_confirmation"
                                        placeholder="Konfirmasi Password" autocomplete="off">
                                    <div class="wizard-form-error"></div>
                                </div>

                                <div class="text-end">
                                    <button type="button" class="form-wizard-next-btn btn btn-primary px-32">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="wizard-fieldset">
                            <h6 class="text-md text-neutral-500">Data Perusahaan</h6>

                            <div class="row gy-3">
                                <div class="col-12">
                                    <label class="form-label">Nama Perusahaan</label>
                                    <input type="text" class="form-control wizard-required" name="company_name"
                                        placeholder="Nama Perusahaan">
                                    <div class="wizard-form-error"></div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">NPWP</label>
                                    <input type="text" class="form-control" name="npwp" placeholder="NPWP"
                                        autocomplete="off">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Telp Perusahaan</label>
                                    <input type="text" class="form-control" name="company_phone"
                                        placeholder="Telp Perusahaan" autocomplete="off">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" name="company_address" placeholder="Alamat" autocomplete="off"></textarea>
                                </div>

                                <div class="d-flex justify-content-end gap-8">
                                    <button type="button" class="form-wizard-previous-btn btn btn-neutral-500 px-32">
                                        Back
                                    </button>

                                    <button type="button" class="form-wizard-submit btn btn-primary px-32"
                                        id="registerBtn">
                                        Register
                                    </button>
                                </div>

                                <div id="loadingSpinner" class="d-none mt-3">
                                    <span class="spinner-border spinner-border-sm"></span> Proses registrasi...
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {

                        const form = document.getElementById('registerForm');
                        document.querySelector(".form-wizard-submit").addEventListener("click", function() {
                            const token = document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content');
                            const formData = new FormData(form);
                            document.getElementById('registerBtn').classList.add('d-none');
                            document.getElementById('loadingSpinner').classList.remove('d-none');

                            axios.post("{{ route('register') }}", formData, {
                                    headers: {
                                        'X-CSRF-TOKEN': token,
                                        'Content-Type': 'multipart/form-data'
                                    }
                                })
                                .then(res => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Registrasi Berhasil',
                                        text: res.data.message,
                                        timer: 3000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.href = res.data.redirect || '/login';
                                    });
                                })
                                .catch(err => {
                                    let msg = 'Terjadi kesalahan';

                                    if (err.response?.status === 422) {
                                        msg = Object.values(err.response.data.errors)
                                            .flat()
                                            .join('<br>');
                                    }

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        html: msg
                                    });

                                    document.getElementById('registerBtn').classList.remove('d-none');
                                    document.getElementById('loadingSpinner').classList.add('d-none');
                                });
                        });

                    });

                    $(document).ready(function() {
                        // click on next button
                        $('.form-wizard-next-btn').on("click", function() {
                            var parentFieldset = $(this).parents('.wizard-fieldset');
                            var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-list .active');
                            var next = $(this);
                            var nextWizardStep = true;
                            parentFieldset.find('.wizard-required').each(function() {
                                var thisValue = $(this).val();

                                if (thisValue == "") {
                                    $(this).siblings(".wizard-form-error").show();
                                    nextWizardStep = false;
                                } else {
                                    $(this).siblings(".wizard-form-error").hide();
                                }
                            });
                            if (nextWizardStep) {
                                next.parents('.wizard-fieldset').removeClass("show", "400");
                                currentActiveStep.removeClass('active').addClass('activated').next().addClass('active',
                                    "400");
                                next.parents('.wizard-fieldset').next('.wizard-fieldset').addClass("show", "400");
                                $(document).find('.wizard-fieldset').each(function() {
                                    if ($(this).hasClass('show')) {
                                        var formAtrr = $(this).attr('data-tab-content');
                                        $(document).find('.form-wizard-list .form-wizard-step-item').each(
                                            function() {
                                                if ($(this).attr('data-attr') == formAtrr) {
                                                    $(this).addClass('active');
                                                    var innerWidth = $(this).innerWidth();
                                                    var position = $(this).position();
                                                    $(document).find('.form-wizard-step-move').css({
                                                        "left": position.left,
                                                        "width": innerWidth
                                                    });
                                                } else {
                                                    $(this).removeClass('active');
                                                }
                                            });
                                    }
                                });
                            }
                        });
                        //click on previous button
                        $('.form-wizard-previous-btn').on("click", function() {
                            var counter = parseInt($(".wizard-counter").text());;
                            var prev = $(this);
                            var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-list .active');
                            prev.parents('.wizard-fieldset').removeClass("show", "400");
                            prev.parents('.wizard-fieldset').prev('.wizard-fieldset').addClass("show", "400");
                            currentActiveStep.removeClass('active').prev().removeClass('activated').addClass('active',
                                "400");
                            $(document).find('.wizard-fieldset').each(function() {
                                if ($(this).hasClass('show')) {
                                    var formAtrr = $(this).attr('data-tab-content');
                                    $(document).find('.form-wizard-list .form-wizard-step-item').each(
                                        function() {
                                            if ($(this).attr('data-attr') == formAtrr) {
                                                $(this).addClass('active');
                                                var innerWidth = $(this).innerWidth();
                                                var position = $(this).position();
                                                $(document).find('.form-wizard-step-move').css({
                                                    "left": position.left,
                                                    "width": innerWidth
                                                });
                                            } else {
                                                $(this).removeClass('active');
                                            }
                                        });
                                }
                            });
                        });
                        //click on form submit button
                        $(document).on("click", ".form-wizard .form-wizard-submit", function() {
                            var parentFieldset = $(this).parents('.wizard-fieldset');
                            var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-list .active');
                            parentFieldset.find('.wizard-required').each(function() {
                                var thisValue = $(this).val();
                                if (thisValue == "") {
                                    $(this).siblings(".wizard-form-error").show();
                                } else {
                                    $(this).siblings(".wizard-form-error").hide();
                                }
                            });
                        });
                        // focus on input field check empty or not
                        $(".form-control").on('focus', function() {
                            var tmpThis = $(this).val();
                            if (tmpThis == '') {
                                $(this).parent().addClass("focus-input");
                            } else if (tmpThis != '') {
                                $(this).parent().addClass("focus-input");
                            }
                        }).on('blur', function() {
                            var tmpThis = $(this).val();
                            if (tmpThis == '') {
                                $(this).parent().removeClass("focus-input");
                                $(this).siblings(".wizard-form-error").show();
                            } else if (tmpThis != '') {
                                $(this).parent().addClass("focus-input");
                                $(this).siblings(".wizard-form-error").hide();
                            }
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

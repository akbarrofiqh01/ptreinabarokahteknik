<!DOCTYPE html>
<html lang="en" data-theme="light">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/backend/logosaja.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/apexcharts.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/editor-katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/editor.atom-one-dark.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/editor.quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/full-calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/jquery-jvectormap-2.0.5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/file-upload.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/lib/audioplayer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}">
    <script src="{{ asset('assets/backend/js/lib/jquery-3.7.1.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        if (typeof axios !== 'undefined') {
            axios.defaults.headers.common['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        } else {
            console.error('Axios gagal dimuat!');
        }
    </script>
    <style>
        .swal2-popup {
            max-width: 330px !important;
            width: 330px !important;
            padding: 1rem 1.2rem !important;
            /* lebih kecil dan proporsional */
            border-radius: 0.75rem !important;
        }

        /* TITLE */
        .swal2-title {
            font-size: 1.25rem !important;
            margin: 0 !important;
            /* hilangkan semua margin default */
            padding: 0 !important;
        }

        /* TEXT */
        .swal2-html-container {
            font-size: 0.95rem !important;
            margin-top: 0.35rem !important;
            /* jarak sangat kecil */
            margin-bottom: 0 !important;
            padding: 0 !important;
        }

        /* ICON */
        .swal2-icon {
            transform: scale(0.6) !important;
            /* icon lebih kecil */
            margin: 0.2rem auto !important;
        }

        /* ACTION BUTTONS */
        .swal2-actions {
            margin-top: 0.8rem !important;
            /* lebih rapat */
        }

        .swal2-confirm,
        .swal2-cancel {
            font-size: 0.85rem !important;
            padding: 0.4rem 0.85rem !important;
        }
    </style>
</head>

<body>

    <div class="body-overlay"></div>

    @include('layouts.partials.sidebar')
    <main class="dashboard-main">
        @include('layouts.partials.header')

        <div class="dashboard-main-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">@yield('title-content')</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="javascript:void(0)" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            @yield('title-content')
                        </a>
                    </li>

                </ul>
            </div>

            @yield('content')
        </div>

        @include('layouts.partials.footer')
        <div class="modal fade" id="dinamicModals" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">

                    <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                        <h1 class="modal-title fs-5" id="myModalLabels">Modal Title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-24">
                        <i class="fa fa-spinner fa-spin"></i> loading ...
                    </div>

                </div>
            </div>
        </div>


        <div class="modal fade" id="dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content radius-16 bg-base">

                    <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                        <h1 class="modal-title fs-5" id="myModalLabel">Modal Title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-24">
                        <i class="fa fa-spinner fa-spin"></i> loading ...
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- jQuery library js -->
    <script src="{{ asset('assets/backend/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/backend/js/lib/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/lib/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/lib/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/backend/js/lib/magnifc-popup.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/lib/slick.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/lib/prism.js') }}"></script>
    <script src="{{ asset('assets/backend/js/lib/file-upload.js') }}"></script>
    <script src="{{ asset('assets/backend/js/lib/audioplayer.js') }}"></script>
    <script src="{{ asset('assets/backend/js/app.js') }}"></script>
    {{-- <script src="{{ asset('assets/backend/js/homeOneChart.js') }}"></script> --}}
    <script>
        $("#dinamicModals").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            var modal = $(this);

            modal.find(".modal-title").text(link.attr("data-title"));
            modal.find(".modal-body").load(link.attr("data-href"));
        });

        $("#dinamicModal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-body").load(link.attr("data-href"));
            $(this).find("#myModalLabel").text(link.attr("data-bs-title"));
        });
    </script>
</body>

</html>

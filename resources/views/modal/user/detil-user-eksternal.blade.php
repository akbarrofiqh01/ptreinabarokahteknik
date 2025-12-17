<ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill"
            data-bs-target="#pills-edit-profile" type="button" role="tab" aria-controls="pills-edit-profile"
            aria-selected="true">
            Informasi Personal
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab" data-bs-toggle="pill"
            data-bs-target="#pills-change-passwork" type="button" role="tab" aria-controls="pills-change-passwork"
            aria-selected="false" tabindex="-1">
            Informasi Perusahaan
        </button>
    </li>
</ul>

<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
        aria-labelledby="pills-edit-profile-tab" tabindex="0">
        <div class="row">
            <div class="col-12">
                <div class="mb-20">
                    <label for="name"
                        class="form-label fw-semibold text-primary-light text-sm mb-8">Username</label>
                    <input type="text" class="form-control radius-8" value="{{ $userdata->username }}"disabled />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-20">
                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                        Lengkap</label>
                    <input type="text" class="form-control radius-8" value="{{ $userdata->name }}"disabled />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-20">
                    <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email</label>
                    <input type="email" class="form-control radius-8" value="{{ $userdata->email }}" disabled />
                </div>
            </div>
            <div class="col-12">
                <div class="mb-20">
                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">No.
                        Telp</label>
                    <input type="email" class="form-control radius-8" value="{{ $userdata->phone }}" disabled />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-20">
                    <label for="desig" class="form-label fw-semibold text-primary-light text-sm mb-8">Status</label>
                    @if ($userdata->status === 'pending')
                        <span
                            class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                            Pending
                        </span>
                    @elseif ($userdata->status === 'active')
                        <span
                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                            Active
                        </span>
                    @elseif ($userdata->status === 'suspended')
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                            Suspended
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel" aria-labelledby="pills-change-passwork-tab"
        tabindex="0">
        <div class="row">
            <div class="col-12">
                <div class="mb-20">
                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                        Perusahaan</label>
                    <input type="text" class="form-control radius-8"
                        value="{{ $userdata->company->name }}"disabled />
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="mb-20">
                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">NPWP
                        Perusahaan</label>
                    <input type="text" class="form-control radius-8"
                        value="{{ $userdata->company->npwp }}"disabled />
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="mb-20">
                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">No. Telp
                        Perusahaan</label>
                    <input type="text" class="form-control radius-8"
                        value="{{ $userdata->company->phone }}"disabled />
                </div>
            </div>
            <div class="col-12">
                <div class="mb-20">
                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Alamat
                        Perusahaan</label>
                    <textarea class="form-control radius-8"rows="5" disabled />{{ $userdata->company->phone }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab"
        tabindex="0">
        <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
            <label for="companzNew" class="position-absolute w-100 h-100 start-0 top-0"></label>
            <div class="d-flex align-items-center gap-3 justify-content-between">
                <span class="form-check-label line-height-1 fw-medium text-secondary-light">Company News</span>
                <input class="form-check-input" type="checkbox" role="switch" id="companzNew" />
            </div>
        </div>
        <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
            <label for="pushNotifcation" class="position-absolute w-100 h-100 start-0 top-0"></label>
            <div class="d-flex align-items-center gap-3 justify-content-between">
                <span class="form-check-label line-height-1 fw-medium text-secondary-light">Push
                    Notification</span>
                <input class="form-check-input" type="checkbox" role="switch" id="pushNotifcation" checked />
            </div>
        </div>
        <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
            <label for="weeklyLetters" class="position-absolute w-100 h-100 start-0 top-0"></label>
            <div class="d-flex align-items-center gap-3 justify-content-between">
                <span class="form-check-label line-height-1 fw-medium text-secondary-light">Weekly News
                    Letters</span>
                <input class="form-check-input" type="checkbox" role="switch" id="weeklyLetters" checked />
            </div>
        </div>
        <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
            <label for="meetUp" class="position-absolute w-100 h-100 start-0 top-0"></label>
            <div class="d-flex align-items-center gap-3 justify-content-between">
                <span class="form-check-label line-height-1 fw-medium text-secondary-light">Meetups Near
                    you</span>
                <input class="form-check-input" type="checkbox" role="switch" id="meetUp" />
            </div>
        </div>
        <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
            <label for="orderNotification" class="position-absolute w-100 h-100 start-0 top-0"></label>
            <div class="d-flex align-items-center gap-3 justify-content-between">
                <span class="form-check-label line-height-1 fw-medium text-secondary-light">Orders
                    Notifications</span>
                <input class="form-check-input" type="checkbox" role="switch" id="orderNotification" checked />
            </div>
        </div>
    </div>
</div>

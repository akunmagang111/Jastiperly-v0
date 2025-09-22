@extends('layout.layout')
@php
    $title='View Profile';
    $subTitle = 'View Profile';
    $script ='<script>
                    // ======================== Upload Image Start =====================
                    function readURL(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                                $("#imagePreview").hide();
                                $("#imagePreview").fadeIn(650);
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                    $("#imageUpload").change(function() {
                        readURL(this);
                    });
                    // ======================== Upload Image End =====================

                    // ================== Password Show Hide Js Start ==========
                    function initializePasswordToggle(toggleSelector) {
                        $(toggleSelector).on("click", function() {
                            $(this).toggleClass("ri-eye-off-line");
                            var input = $($(this).attr("data-toggle"));
                            if (input.attr("type") === "password") {
                                input.attr("type", "text");
                            } else {
                                input.attr("type", "password");
                            }
                        });
                    }
                    // Call the function
                    initializePasswordToggle(".toggle-password");
                    // ========================= Password Show Hide Js End ===========================
            </script>';
@endphp

@section('content')

            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                        <img src="{{ asset('assets/images/card/card-bg.png') }}" alt="" class="w-100 object-fit-cover">
                        <div class="pb-24 ms-16 mb-24 me-16  mt--100">
                            <div class="text-center border border-top-0 border-start-0 border-end-0">
                                 <img id="profileImage"
                                    src="{{ $user->detail->account_image
                                        ? asset('storage/'.$user->detail->account_image)
                                        : asset('assets/images/user-grid/user-grid-img14.png') }}"
                                    alt="Profile"
                                    class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/user-grid/user-grid-img14.png') }}'">

                                <h6 class="mb-0 mt-16">{{ $user->detail->name ?? $user->name }}</h6>
                                <span class="text-secondary-light mb-16">{{ $user->email }}</span>
                            </div>
                            <div class="mt-24">
                                <h6 class="text-xl mb-16">Personal Info</h6>
                                <ul>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->name ?? $user->name }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Email</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->email }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Phone Number</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->phone ?? '-' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Address</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->address ?? '-' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Date of Birth</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->date_birth ?? '-' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Gender</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->gender ?? '-' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Role</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ ucfirst($user->role) }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Account Status</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ ucfirst($user->account_status) }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Bank Name</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->bank_name ?? '-' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Bank Number</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->bank_number ?? '-' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Verified</span>
                                        <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->verified_type ? 'Yes' : 'No' }}</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">ID Card</span>
                                        <span class="w-70 text-secondary-light fw-medium">
                                            <img src="{{ $user->detail->id_card_image 
                                                ? asset('storage/'.$user->detail->id_card_image) 
                                                : asset('assets/images/card-component/card-img1.png') }}"
                                                alt="ID Card"
                                                class="border border-1 w-150-px h-100-px rounded-3 object-fit-cover">
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-start gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Passport</span>
                                        <span class="w-70 text-secondary-light fw-medium">
                                            <img src="{{ $user->detail->pasport_image 
                                                ? asset('storage/'.$user->detail->pasport_image) 
                                                : asset('assets/images/card-component/card-img1.png') }}"
                                                alt="Passport"
                                                class="border border-1 w-150-px h-100-px rounded-3 object-fit-cover">
                                        </span>
                                    </li>

                                    <li class="d-flex align-items-start gap-1 mb-12">
                                        <span class="w-30 text-md fw-semibold text-primary-light">Account Image</span>
                                        <span class="w-70 text-secondary-light fw-medium">
                                            <img src="{{ $user->detail->account_image 
                                                ? asset('storage/'.$user->detail->account_image) 
                                                : asset('assets/images/user-grid/user-grid-img14.png') }}"
                                                alt="Account Image"
                                                class="border border-1 w-150-px h-100-px rounded-3 object-fit-cover">
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
                                    <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab" aria-controls="pills-edit-profile" aria-selected="true">
                                        Edit Profile
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab" data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab" aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                                        Change Password
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link d-flex align-items-center px-24" id="pills-notification-tab" data-bs-toggle="pill" data-bs-target="#pills-notification" type="button" role="tab" aria-controls="pills-notification" aria-selected="false" tabindex="-1">
                                        Notification Settings
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel" aria-labelledby="pills-edit-profile-tab" tabindex="0">
                                    <h6 class="text-md text-primary-light mb-16">Profile & Documents</h6>
                                    <div class="row align-items-end">
                                        {{-- Profile --}}
                                        <div class="col-md-4 text-center">
                                            <form action="{{ route('finance.updateProfileImage') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-16 position-relative d-inline-block">
                                                    <img src="{{ $user->detail->account_image 
                                                        ? asset('storage/'.$user->detail->account_image) 
                                                        : asset('assets/images/user-grid/user-grid-img14.png') }}"
                                                        alt="Profile"
                                                        class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">

                                                    <!-- Tombol Kamera -->
                                                    <input type="file" name="profile_image" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                                    <label for="imageUpload" 
                                                        class="position-absolute bottom-0 end-0 w-32-px h-32-px d-flex justify-content-center align-items-center 
                                                            bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle cursor-pointer">
                                                        <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                                    </label>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                                            </form>
                                        </div>

                                        {{-- ID Card --}}
                                        <div class="col-md-4 text-center">
                                            <form action="{{ route('finance.updateIdCard') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-16 position-relative d-inline-block">
                                                    <img src="{{ $user->detail->id_card_image 
                                                        ? asset('storage/'.$user->detail->id_card_image) 
                                                        : asset('assets/images/card-component/card-img1.png') }}"
                                                        alt="ID Card"
                                                        class="border border-1 w-150-px h-100-px rounded-3 object-fit-cover">

                                                    <!-- Tombol Kamera -->
                                                    <input type="file" name="id_card_image" id="idCardUpload" accept=".png, .jpg, .jpeg" hidden>
                                                    <label for="idCardUpload" 
                                                        class="position-absolute bottom-0 end-0 w-32-px h-32-px d-flex justify-content-center align-items-center 
                                                            bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle cursor-pointer">
                                                        <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                                    </label>
                                                </div>
                                                {{-- Current Password --}}
                                                <div class="mb-3">
                                                    <label for="current_password" class="form-label">Current Password</label>
                                                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                                                    @error('current_password')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100">Update ID Card</button>
                                            </form>
                                        </div>

                                        {{-- Passport --}}
                                        <div class="col-md-4 text-center">
                                            <form action="{{ route('finance.updatePassport') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-16 position-relative d-inline-block">
                                                    <img src="{{ $user->detail->pasport_image 
                                                        ? asset('storage/'.$user->detail->pasport_image) 
                                                        : asset('assets/images/card-component/card-img1.png') }}"
                                                        alt="Passport"
                                                        class="border border-1 w-150-px h-100-px rounded-3 object-fit-cover">

                                                    <!-- Tombol Kamera -->
                                                    <input type="file" name="pasport_image" id="passportUpload" accept=".png, .jpg, .jpeg" hidden>
                                                    <label for="passportUpload" 
                                                        class="position-absolute bottom-0 end-0 w-32-px h-32-px d-flex justify-content-center align-items-center 
                                                            bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle cursor-pointer">
                                                        <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                                    </label>
                                                </div>
                                                {{-- Current Password --}}
                                                <div class="mb-3">
                                                    <label for="current_password" class="form-label">Current Password</label>
                                                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                                                    @error('current_password')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100">Update Passport</button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Upload Image End -->
                                    <h6></h6>
                                    <hr class="my-4">
                                    <form action="{{ route('finance.updateProfile') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="name" class="form-label">Full Name</label>
                                                    <input type="text" name="name" class="form-control" id="name" 
                                                        value="{{ old('name', $user->detail->name ?? $user->name) }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control" id="email" 
                                                        value="{{ old('email', $user->email) }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label for="number" class="form-label">Phone</label>
                                                    <input type="text" name="phone" class="form-control" id="number" 
                                                        value="{{ old('phone', $user->detail->phone) }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-20">
                                                    <label for="address" class="form-label">Address</label>
                                                    <textarea name="address" class="form-control">{{ old('address', $user->detail->address) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Current Password --}}
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                                            @error('current_password')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel" aria-labelledby="pills-change-passwork-tab" tabindex="0">
                                    <form action="{{ route('finance.updatePassword') }}" method="POST">
                                        @csrf
                                        <div class="mb-20">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" name="current_password" class="form-control" id="current_password" required>
                                        </div>

                                        <div class="mb-20">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" name="password" class="form-control" id="password" required>
                                        </div>

                                        <div class="mb-20">
                                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab" tabindex="0">
                                    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                        <label for="companzNew" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                        <div class="d-flex align-items-center gap-3 justify-content-between">
                                            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Company News</span>
                                            <input class="form-check-input" type="checkbox" role="switch" id="companzNew">
                                        </div>
                                    </div>
                                    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                        <label for="pushNotifcation" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                        <div class="d-flex align-items-center gap-3 justify-content-between">
                                            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Push Notification</span>
                                            <input class="form-check-input" type="checkbox" role="switch" id="pushNotifcation" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                        <label for="weeklyLetters" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                        <div class="d-flex align-items-center gap-3 justify-content-between">
                                            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Weekly News Letters</span>
                                            <input class="form-check-input" type="checkbox" role="switch" id="weeklyLetters" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                        <label for="meetUp" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                        <div class="d-flex align-items-center gap-3 justify-content-between">
                                            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Meetups Near you</span>
                                            <input class="form-check-input" type="checkbox" role="switch" id="meetUp">
                                        </div>
                                    </div>
                                    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                        <label for="orderNotification" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                        <div class="d-flex align-items-center gap-3 justify-content-between">
                                            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Orders Notifications</span>
                                            <input class="form-check-input" type="checkbox" role="switch" id="orderNotification" checked>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection

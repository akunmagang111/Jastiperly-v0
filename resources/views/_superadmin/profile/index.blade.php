@extends('layout.layout')

@php
    $title='View Profile';
    $subTitle = 'View Profile';
    $script ='
        <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
        <script>
            // Upload Image Preview
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("#avatarImagePreview").attr("src", e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imageUpload").change(function() {
                readURL(this);
            });

            // Password Toggle
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
            initializePasswordToggle(".toggle-password");

            // Particle.js Background for Avatar
            particlesJS("avatar-bg-particles", {
                "particles": {
                    "number": { "value": 50 },
                    "color": { "value": ["#ff6b6b","#feca57","#48dbfb","#5f27cd"] },
                    "shape": { "type": "circle" },
                    "opacity": { "value": 0.7 },
                    "size": { "value": 3 },
                    "line_linked": { "enable": true, "distance": 120, "color": "#fff", "opacity": 0.4, "width": 1 },
                    "move": { "enable": true, "speed": 2, "direction": "none", "out_mode": "bounce" }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": { "onhover": { "enable": true, "mode": "repulse" }, "onclick": { "enable": true, "mode": "push" } },
                    "modes": { "repulse": { "distance": 100 }, "push": { "particles_nb": 4 } }
                },
                "retina_detect": true
            });
        </script>';
@endphp

@section('content')
<div class="row gy-4">
    <!-- Left Profile Card -->
    <div class="col-lg-4">
        <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
            <!-- Background Image + Particle -->
            <div class="position-relative w-100" style="height:180px;">
                <img src="https://images.unsplash.com/photo-1503264116251-35a269479413?auto=format&fit=crop&w=800&q=80" 
                     alt="background" class="w-100 h-100 object-fit-cover">
                <div id="avatar-bg-particles" class="position-absolute w-100 h-100 top-0 start-0" style="z-index:0;"></div>
            </div>

            <div class="pb-24 ms-16 mb-24 me-16 mt--100 position-relative z-1">
                <div class="text-center border border-top-0 border-start-0 border-end-0">
                    <!-- Avatar Circle -->
                    <div class="mx-auto mb-16" style="width:200px; height:200px; border-radius:50%; overflow:hidden; border:3px solid #fff; background-color:#f0f0f0;">
                        <img id="avatarImage" 
                             src="{{ $user->detail && $user->detail->account_image 
                                 ? asset('storage/'.$user->detail->account_image) 
                                 : 'https://images.unsplash.com/photo-1603415526960-f7e0328f97c6?auto=format&fit=crop&w=200&q=80' }}" 
                             alt="avatar" 
                             style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                    </div>

                    <h6 class="mb-0 mt-16">{{ $user->name }}</h6>
                    <span class="text-secondary-light mb-16">{{ $user->email }}</span>
                </div>

                <div class="mt-24">
                    <h6 class="text-xl mb-16">Personal Info</h6>
                    <ul>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $user->name }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Email</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $user->email }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Phone</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->phone ?? '-' }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1">
                            <span class="w-30 text-md fw-semibold text-primary-light">Bio</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $user->detail->address ?? '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Tabs -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-24">
                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active px-24" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button">Edit Profile</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-24" id="pills-change-passwork-tab" data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button">Change Password</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <!-- Edit Profile -->
                    <div class="tab-pane fade show active" id="pills-edit-profile">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                            <div class="mb-24 mt-16 text-center">
                                <div class="position-relative d-inline-block">
                                    <div style="width:120px; height:120px; border-radius:50%; overflow:hidden; border:2px solid #fff; background-color:#f0f0f0;">
                                        <img id="avatarImagePreview" 
                                            src="{{ $user->detail && $user->detail->account_image ? asset('storage/'.$user->detail->account_image) : 'https://images.unsplash.com/photo-1603415526960-f7e0328f97c6?auto=format&fit=crop&w=200&q=80' }}" 
                                            alt="Preview" 
                                            style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                                    </div>
                                    <!-- Kamera button -->
                                    <div class="position-absolute bottom-0 end-0">
                                        <input type="file" id="imageUpload" name="avatar" accept=".png, .jpg, .jpeg" hidden>
                                        <label for="imageUpload"
                                                class="d-flex justify-content-center align-items-center bg-primary text-white rounded-circle shadow"
                                                style="width:36px; height:36px; cursor:pointer; border:2px solid #fff;">
                                            <i class="ri-camera-line"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 mb-20">
                                    <label class="form-label text-sm">Full Name</label>
                                    <input type="text" class="form-control radius-8" name="name" value="{{ $user->name }}">
                                </div>
                                <div class="col-sm-6 mb-20">
                                    <label class="form-label text-sm">Email</label>
                                    <input type="email" class="form-control radius-8" name="email" value="{{ $user->email }}">
                                </div>
                                <div class="col-sm-6 mb-20">
                                    <label class="form-label text-sm">Phone</label>
                                    <input type="text" class="form-control radius-8" name="phone" value="{{ $user->detail->phone ?? '' }}">
                                </div>
                                <div class="col-sm-12 mb-20">
                                    <label class="form-label text-sm">Bio</label>
                                    <textarea class="form-control radius-8" name="bio">{{ $user->detail->address ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="reset" class="border border-danger-600 text-danger-600 px-56 py-11 radius-8">Cancel</button>
                                <button type="submit" class="btn btn-primary px-56 py-12 radius-8">Save</button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="tab-pane fade" id="pills-change-passwork">
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-20">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control radius-8" name="old_password" id="old-password" required>
                            </div>
                            <div class="mb-20">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control radius-8" name="new_password" id="new-password" required>
                            </div>
                            <div class="mb-20">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control radius-8" name="new_password_confirmation" id="confirm-password" required>
                            </div>
                            <button type="submit" class="btn btn-primary px-56 py-12 radius-8">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

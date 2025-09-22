<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head/>

<body>

<section class="auth bg-base d-flex flex-wrap">
    <!-- Left Image -->
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <img src="{{ asset('assets/images/auth/auth-img.png') }}" alt="">
        </div>
    </div>

    <!-- Right Form -->
    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-464-px mx-auto w-100">
            <div>
                <a href="{{ route('index') }}" class="mb-40 max-w-290-px d-inline-block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
                </a>
                <h4 class="mb-12">Sign Up to your Account</h4>
                <p class="mb-32 text-secondary-light text-lg">Create your account by filling the form below</p>
            </div>

            <!-- 🚀 Form Breeze -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="icon-field mb-16">
                    <span class="icon top-50 translate-middle-y">
                        <iconify-icon icon="f7:person"></iconify-icon>
                    </span>
                    <input id="name" type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required autofocus autocomplete="name"
                           class="form-control h-56-px bg-neutral-50 radius-12"
                           placeholder="Username">
                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="icon-field mb-16">
                    <span class="icon top-50 translate-middle-y">
                        <iconify-icon icon="mage:email"></iconify-icon>
                    </span>
                    <input id="email" type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required autocomplete="username"
                           class="form-control h-56-px bg-neutral-50 radius-12"
                           placeholder="Email">
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-20">
                    <div class="position-relative">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input id="password" type="password"
                                   name="password"
                                   required autocomplete="new-password"
                                   class="form-control h-56-px bg-neutral-50 radius-12"
                                   placeholder="Password">
                        </div>
                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#password"></span>
                    </div>
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                    <span class="mt-12 text-sm text-secondary-light d-block">Your password must have at least 8 characters</span>
                </div>

                <!-- Confirm Password -->
                <div class="mb-20">
                    <div class="position-relative">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input id="password_confirmation" type="password"
                                   name="password_confirmation"
                                   required autocomplete="new-password"
                                   class="form-control h-56-px bg-neutral-50 radius-12"
                                   placeholder="Confirm Password">
                        </div>
                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#password_confirmation"></span>
                    </div>
                    @error('password_confirmation')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Terms & Conditions -->
                <div class="mb-20">
                    <div class="form-check style-check d-flex align-items-start">
                        <input class="form-check-input border border-neutral-300 mt-4" type="checkbox" required id="condition">
                        <label class="form-check-label text-sm" for="condition">
                            By creating an account you agree to the
                            <a href="javascript:void(0)" class="text-primary-600 fw-semibold">Terms & Conditions</a>
                            and our
                            <a href="javascript:void(0)" class="text-primary-600 fw-semibold">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Sign Up</button>

                <!-- Divider -->
                <div class="mt-32 center-border-horizontal text-center">
                    <span class="bg-base z-1 px-4">Or sign up with</span>
                </div>

                <!-- Social -->
                <div class="mt-32 d-flex align-items-center gap-3">
                    <button type="button" class="fw-semibold text-primary-light py-16 px-24 w-50 border radius-12 text-md d-flex align-items-center justify-content-center gap-12 bg-hover-primary-50">
                        <iconify-icon icon="ic:baseline-facebook" class="text-primary-600 text-xl"></iconify-icon>
                        Facebook
                    </button>
                    <button type="button" class="fw-semibold text-primary-light py-16 px-24 w-50 border radius-12 text-md d-flex align-items-center justify-content-center gap-12 bg-hover-primary-50">
                        <iconify-icon icon="logos:google-icon" class="text-primary-600 text-xl"></iconify-icon>
                        Google
                    </button>
                </div>

                <!-- Already have account -->
                <div class="mt-32 text-center text-sm">
                    <p class="mb-0">Already have an account?
                        <a href="{{ route('login') }}" class="text-primary-600 fw-semibold">Sign In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

@php
$script = '<script>
    $(".toggle-password").on("click", function() {
        $(this).toggleClass("ri-eye-off-line");
        var input = $($(this).attr("data-toggle"));
        input.attr("type", input.attr("type") === "password" ? "text" : "password");
    });
</script>';
@endphp

<x-script />

</body>
</html>
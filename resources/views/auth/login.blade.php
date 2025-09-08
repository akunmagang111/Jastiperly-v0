<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head/>

<body>

<section class="auth bg-base d-flex flex-wrap">
    <!-- Left image -->
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <img src="{{ asset('assets/images/auth/auth-img.png') }}" alt="">
        </div>
    </div>

    <!-- Right form -->
    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-464-px mx-auto w-100">
            <div>
                <a href="{{ route('index') }}" class="mb-40 max-w-290-px d-inline-block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
                </a>
                <h4 class="mb-12">Sign In to your Account</h4>
                <p class="mb-32 text-secondary-light text-lg">Welcome back! Please enter your detail</p>
            </div>

            <!-- 🚀 Form Breeze tetap -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="icon-field mb-16">
                    <span class="icon top-50 translate-middle-y">
                        <iconify-icon icon="mage:email"></iconify-icon>
                    </span>
                    <input id="email" type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           class="form-control h-56-px bg-neutral-50 radius-12"
                           placeholder="Email">
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="position-relative mb-20">
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span>
                        <input id="password" type="password"
                               name="password"
                               required autocomplete="current-password"
                               class="form-control h-56-px bg-neutral-50 radius-12"
                               placeholder="Password">
                    </div>
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                    <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#password"></span>
                </div>

                <!-- Remember + Forgot -->
                <div class="d-flex justify-content-between gap-2">
                    <div class="form-check style-check d-flex align-items-center">
                        <input class="form-check-input border border-neutral-300" type="checkbox" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-primary-600 fw-medium">Forgot Password?</a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Sign In</button>

                <!-- Divider -->
                <div class="mt-32 center-border-horizontal text-center">
                    <span class="bg-base z-1 px-4">Or sign in with</span>
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

                <!-- Register link -->
                <div class="mt-32 text-center text-sm">
                    <p class="mb-0">Don’t have an account?
                        <a href="{{ route('register') }}" class="text-primary-600 fw-semibold">Sign Up</a>
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
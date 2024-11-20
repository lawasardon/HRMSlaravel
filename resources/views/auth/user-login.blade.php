@include('layouts.include.link')

<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">
            <div class="loginbox">
                <div class="login-left" style="box-shadow: 5px 0px 15px rgba(0, 0, 0, 0.10);">
                    <img class="img-fluid" src="{{ asset('admin/assets/img/hr-login.png') }}" alt="Logo"
                        style="margin-bottom: 30px">
                </div>
                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Welcome to HR System</h1>
                        <h2>Sign in</h2>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <label>Username <span class="login-danger">*</span></label>
                                <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Password <span class="login-danger">*</span></label>
                                <span class="profile-views feather-eye toggle-password" id="togglePassword"></span>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="forgotpass">
                                <div class="remember-me">
                                    <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.include.script')

<style>
    .login-wrapper .loginbox .login-left {
        background-color: #fff;
    }

    .login-wrapper .loginbox .login-left:after {
        display: none;
    }

    /* Additional styles for the toggle password icon */
    .feather-eye {
        cursor: pointer;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .form-group {
        position: relative;
    }
</style>

<script>
    // JavaScript for toggle password visibility
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    togglePassword.addEventListener("click", function() {
        // Toggle the password visibility
        const type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;

        // Toggle the eye icon (optional, you can also change it to 'feather-eye-off' if desired)
        togglePassword.classList.toggle("feather-eye");
    });
</script>

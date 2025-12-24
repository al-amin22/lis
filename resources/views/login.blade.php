<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - PT. Arvindo Karya Utama</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/fonts/remix/remixicon.css">
    <link rel="stylesheet" href="assets/css/main.min.css">
</head>

<body class="login-bg">

    <div class="container">
        <div class="auth-wrapper">

            <!-- FORM LOGIN -->
            <form method="POST" action="/login">
                @csrf

                <div class="auth-box">

                    <a href="#" class="auth-logo mb-4 fw-bold fs-4 text-decoration-none">
                        PT Arvindo Karya Utama
                    </a>

                    <h4 class="mb-4">Silahkan Login</h4>

                    <!-- ERROR MESSAGE -->
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <i class="ri-error-warning-line"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label class="form-label" for="email">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" id="email" name="email" class="form-control"
                            placeholder="Enter your email" required autofocus>
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label" for="password">
                            Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Enter password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="ri-eye-line text-primary"></i>
                            </button>
                        </div>
                    </div>

                    <!-- REMEMBER ME -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>

                    <!-- BUTTON -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="loginBtn">
                            Login
                        </button>
                        <a href="#" class="btn btn-secondary">
                            Butuh Bantuan?
                        </a>
                    </div>

                </div>
            </form>
            <!-- FORM END -->

        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function togglePassword() {
            const pwd = document.getElementById("password");
            pwd.type = pwd.type === "password" ? "text" : "password";
        }

        // Loading saat submit
        document.querySelector("form").addEventListener("submit", function() {
            const btn = document.getElementById("loginBtn");
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Authenticating...';
        });
    </script>

</body>

</html>

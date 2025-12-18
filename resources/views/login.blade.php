<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laboratory Information System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #1abc9c;
            --light-bg: #f8f9fa;
            --border-radius: 10px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }

        .login-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 25px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(30deg);
        }

        .card-header h1 {
            font-weight: 600;
            font-size: 1.8rem;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }

        .card-header p {
            opacity: 0.9;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: #f1f5f9;
            border-right: none;
        }

        .form-control {
            border-left: none;
            padding-left: 5px;
            transition: all 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
            border-color: #86b7fe;
        }

        .btn-login {
            background-color: var(--secondary-color);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .error-message {
            background-color: #ffeaea;
            color: #d32f2f;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-left: 4px solid #d32f2f;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message i {
            font-size: 1.2rem;
        }

        .lis-features {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #555;
            font-size: 0.9rem;
        }

        .feature-item i {
            color: var(--accent-color);
            margin-right: 10px;
            font-size: 0.9rem;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 0.85rem;
        }

        .footer-links a {
            color: var(--secondary-color);
            text-decoration: none;
            margin: 0 5px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .brand-logo {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 15px;
            display: inline-block;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .card-body {
                padding: 25px 20px;
            }

            .card-header {
                padding: 20px;
            }

            .login-container {
                padding: 10px;
            }
        }

        @media (max-width: 400px) {
            .card-header h1 {
                font-size: 1.5rem;
            }

            .brand-logo {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="card-header">
                <div class="brand-logo">
                    <i class="fas fa-flask"></i>
                </div>
                <h1>Laboratory Information System</h1>
                <p>Secure access to laboratory management system</p>
            </div>

            <div class="card-body">
                @if ($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i> Login to System
                        </button>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">
                            Remember me on this device
                        </label>
                    </div>

                    <div class="lis-features">
                        <p class="small text-muted mb-3">LIS provides:</p>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Sample tracking and management</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Test results and reporting</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="footer-links">
            <a href="#">Forgot password?</a> |
            <a href="#">Contact Administrator</a> |
            <a href="#">System Help</a>
            <p class="mt-2">&copy; 2023 Laboratory Information System. All rights reserved.</p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Simple form validation and interaction
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const form = document.querySelector('form');

            // Focus on email input on page load
            emailInput.focus();

            // Add visual feedback for inputs
            [emailInput, passwordInput].forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.boxShadow = '0 0 0 0.25rem rgba(52, 152, 219, 0.25)';
                    this.parentElement.style.borderRadius = '6px';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.boxShadow = 'none';
                });
            });

            // Form submission animation
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Authenticating...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>

</html>
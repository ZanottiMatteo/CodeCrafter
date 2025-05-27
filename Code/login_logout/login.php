<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index/index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeCrafters - Login / Signup</title>
    <link rel="icon" href="../utils/Icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../nav_header_footer/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/flat-icons/css/flat-icons.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="login.js"></script>
</head>

<body>
    <a href="../index/index.php" class="back-arrow slide-in-left">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    <div class="auth-container">
        <div class="toggle">
            <button id="logBtn" class="active">Log In</button>
            <button id="signBtn">Sign Up</button>
        </div>
        <form id="loginForm" class="active">
            <div class="form-group">
                <span><i class="fas fa-envelope"></i></span>
                <input type="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <span><i class="fas fa-lock"></i></span>
                <input type="password" placeholder="Password" required>
                <button type="button" class="show-password-btn" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                    <i class="fas fa-eye-slash" style="display: none;"></i>
                </button>
            </div>
            <div class="options">
                <a href="#" id="forgotPasswordLink"><i class="fas fa-key"></i> Forgot password?</a>
                <a href="javascript:void(0)"><i class="fas fa-question-circle"></i> Need help?</a>
            </div>
            <button type="submit" class="submit-btn"><i class="fas fa-sign-in-alt"></i> LOGIN</button>

            <div class="social-login">
                <span>Or login with</span>
            </div>

            <div class="social-buttons">
                <button type="button" class="facebook-btn"><i class="fab fa-facebook-f"></i></button>
                <button type="button" class="google-btn"><i class="fab fa-google"></i></button>
                <button type="button" class="apple-btn"><i class="fab fa-apple"></i></button>
            </div>

            <div class="footer">
                Don't have an account? <a href="#" id="switchToSignup"><i class="fas fa-user-plus"></i> Sign Up</a>
            </div>
        </form>

        <form id="signupForm">
            <div class="form-group">
                <span><i class="fas fa-user"></i></span>
                <input type="text" placeholder="Name" required>
            </div>
            <div class="form-group">
                <span><i class="fas fa-envelope"></i></span>
                <input type="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <span><i class="fas fa-lock"></i></span>
                <input type="password" placeholder="Password" required>
                <button type="button" class="show-password-btn" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                    <i class="fas fa-eye-slash" style="display: none;"></i>
                </button>
            </div>
            <div class="form-group">
                <span><i class="fas fa-lock"></i></span>
                <input type="password" placeholder="Confirm Password" required>
                <button type="button" class="show-password-btn" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                    <i class="fas fa-eye-slash" style="display: none;"></i>
                </button>
            </div>
            <button type="submit" class="submit-btn"><i class="fas fa-user-edit"></i> REGISTER</button>

            <div class="social-login">
                <span>Or sign up with</span>
            </div>

            <div class="social-buttons">
                <button type="button" class="facebook-btn"><i class="fab fa-facebook-f"></i></button>
                <button type="button" class="google-btn"><i class="fab fa-google"></i></button>
                <button type="button" class="apple-btn"><i class="fab fa-apple"></i></button>
            </div>

            <div class="footer">
                Already have an account? <a href="#" id="switchToLogin"><i class="fas fa-sign-in-alt"></i> Log In</a>
            </div>
        </form>
    </div>
</body>

</html>
document.addEventListener('DOMContentLoaded', () => {
    const logBtn = document.getElementById('logBtn');
    const signBtn = document.getElementById('signBtn');
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const switchToSignup = document.getElementById('switchToSignup');
    const switchToLogin = document.getElementById('switchToLogin');

    function showLogin() {
        logBtn.classList.add('active');
        signBtn.classList.remove('active');
        loginForm.classList.add('active');
        signupForm.classList.remove('active');
    }

    function showSignup() {
        signBtn.classList.add('active');
        logBtn.classList.remove('active');
        signupForm.classList.add('active');
        loginForm.classList.remove('active');
    }

    logBtn.addEventListener('click', showLogin);
    signBtn.addEventListener('click', showSignup);
    switchToSignup?.addEventListener('click', (e) => {
        e.preventDefault();
        showSignup();
    });
    switchToLogin?.addEventListener('click', (e) => {
        e.preventDefault();
        showLogin();
    });

    if (window.location.hash === "#signup") {
        showSignup();
    }

    document.querySelectorAll('.show-password-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.parentElement.querySelector('input');
            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';

            this.classList.toggle('active');

            const newLabel = isPassword ? 'Hide password' : 'Show password';
            this.setAttribute('aria-label', newLabel);

            input.focus();
        });
    });
});
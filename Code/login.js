document.addEventListener('DOMContentLoaded', () => {
    const logBtn = document.getElementById('logBtn');
    const signBtn = document.getElementById('signBtn');
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const switchToSignup = document.getElementById('switchToSignup');
    const switchToLogin = document.getElementById('switchToLogin');
    const authContainer = document.querySelector('.auth-container');

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

    // Registrazione
    document.getElementById('signupForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const inputs = e.target.querySelectorAll('input');
        const nome = inputs[0].value.trim();
        const mail = inputs[1].value.trim();
        const password = inputs[2].value;
        const confirm = inputs[3].value;

        if (password !== confirm) return alert("Le password non coincidono!");

        const res = await fetch('auth_register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nome, mail, password })
        });

        const text = await res.text();
        console.log("🔍 RISPOSTA SERVER GREZZA:", text);
        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error("❌ JSON parse error:", e);
            return;
        }

        if (result.success) {
            alert("Registrazione completata!");
            e.target.reset();
            showLogin();
        } else {
            alert(result.message || "Errore");
        }

    });

    // Login
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const inputs = e.target.querySelectorAll('input');
        const mail = inputs[0].value.trim();
        const password = inputs[1].value;

        const res = await fetch('auth_login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mail, password })
        });

        const text = await res.text();
        console.log("🔍 RISPOSTA SERVER GREZZA:", text);
        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error("❌ JSON parse error:", e);
            return;
        }

        if (result.success) {
            window.location.href = "index.php";
        } else {
            alert(result.message || "Errore nel login");
        }
    });
});
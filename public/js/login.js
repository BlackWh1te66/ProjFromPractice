const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
    document.body.classList.add('dark-theme');
}

document.getElementById('themeSwitcher').addEventListener('click', function() {
    document.body.classList.toggle('dark-theme');
    const isDark = document.body.classList.contains('dark-theme');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    this.textContent = isDark ? '☀️' : '🌓';
});

window.addEventListener('DOMContentLoaded', function() {
    const themeSwitcher = document.getElementById('themeSwitcher');
    const isDark = document.body.classList.contains('dark-theme');
    themeSwitcher.textContent = isDark ? '☀️' : '🌓';
    
    // Check if user is already logged in
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'false';  //true false
    if (isLoggedIn) {
        window.location.href = 'welcome.blade.php'; 
    }
});

// Form switching
const showRegisterBtn = document.getElementById('showRegister');
if (showRegisterBtn) {
    showRegisterBtn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('loginFormContainer').classList.remove('active');
        document.getElementById('registerFormContainer').classList.add('active');
    });
}

const showLoginBtn = document.getElementById('showLogin');
if (showLoginBtn) {
    showLoginBtn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('registerFormContainer').classList.remove('active');
        document.getElementById('loginFormContainer').classList.add('active');
    });
}

const loginLink = document.getElementById('loginLink');
if (loginLink) {
    loginLink.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('registerFormContainer').classList.remove('active');
        document.getElementById('loginFormContainer').classList.add('active');
    });
}

// Form submission - Login
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const errorMessage = document.getElementById('loginErrorMessage');

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ username, password })
            });
            const data = await response.json();
            if (data.success) {
                localStorage.setItem('isLoggedIn', 'true');
                window.location.href = '/';
            } else {
                errorMessage.textContent = data.message || 'Невірний логін або пароль';
            }
        } catch (err) {
            errorMessage.textContent = 'Помилка з\'єднання з сервером';
        }
    });
}

// Form submission - Registration
const registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const username = document.getElementById('regUsername').value;
        const first_name = document.getElementById('regFirstName').value;
        const last_name = document.getElementById('regLastName').value;
        const birthday = document.getElementById('regBirthday').value;
        const email = document.getElementById('regEmail').value;
        const phone = document.getElementById('regPhone').value;
        const password = document.getElementById('regPassword').value;
        const errorMessage = document.getElementById('registerErrorMessage');

        // Проверка на пустые значения
        if (!username || !first_name || !last_name || !birthday || !email || !phone || !password) {
            errorMessage.textContent = 'Будь ласка, заповніть всі поля';
            return;
        }

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            const response = await fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ username, first_name, last_name, birthday, email, phone, password })
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Registration server error:', errorText);
                errorMessage.textContent = 'Внутрішня помилка сервера. Спробуйте пізніше.';
                return;
            }

            const data = await response.json();
            if (data.success) {
                localStorage.setItem('isLoggedIn', 'true');
                window.location.href = '/';
            } else {
                errorMessage.textContent = data.message || 'Помилка реєстрації';
            }
        } catch (err) {
            errorMessage.textContent = 'Помилка з\'єднання з сервером';
            console.error('Registration error:', err);
        }
    });
}
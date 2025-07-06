// Перемикач теми
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }

    const themeSwitcher = document.getElementById('themeSwitcher');
    themeSwitcher.addEventListener('click', function() {
        document.body.classList.toggle('dark-theme');
        const isDark = document.body.classList.contains('dark-theme');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        this.textContent = isDark ? '☀️' : '🌓';
    });

    const isDark = document.body.classList.contains('dark-theme');
    themeSwitcher.textContent = isDark ? '☀️' : '🌓';

    // Для демонстрації - можна змінити на true, щоб побачити кнопку профілю
    const isLoggedIn = false;
    
    const profileBtn = document.getElementById('profileBtn');
    const loginBtn = document.getElementById('loginBtn');
    
    if (isLoggedIn) {
        profileBtn.style.display = 'flex';
        loginBtn.style.display = 'none';
    } else {
        profileBtn.style.display = 'none';
        loginBtn.style.display = 'flex';
    }
    
    // Обробник кнопки профілю
    profileBtn.addEventListener('click', function() {
        window.location.href = 'profile.html';
    });

    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.fade-in');
        elements.forEach(el => {
            const elementPosition = el.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (elementPosition < screenPosition) {
                el.classList.add('active');
            }
        });
    };

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll();
});

document.addEventListener('DOMContentLoaded', function() {
    // Профильное выпадающее меню
    const profileDropdownBtn = document.getElementById('profileDropdownBtn');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');
    if (profileDropdownBtn && profileDropdownMenu) {
        profileDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            this.parentElement.classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            if (!profileDropdownBtn.contains(e.target)) {
                profileDropdownBtn.parentElement.classList.remove('open');
            }
        });
    }
});
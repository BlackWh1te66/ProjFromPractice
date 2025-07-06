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
});

// Герой-слайдер
document.addEventListener('DOMContentLoaded', function() {
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroControls = document.querySelectorAll('.slide-control');
    let currentHeroSlide = 0;
    let heroInterval = setInterval(nextHeroSlide, 5000);
    
    function nextHeroSlide() {
        showHeroSlide((currentHeroSlide + 1) % heroSlides.length);
    }
    
    function showHeroSlide(index) {
        heroSlides[currentHeroSlide].classList.remove('active');
        heroControls[currentHeroSlide].classList.remove('active');
        currentHeroSlide = index;
        heroSlides[currentHeroSlide].classList.add('active');
        heroControls[currentHeroSlide].classList.add('active');
    }
    
    heroControls.forEach((control, index) => {
        control.addEventListener('click', () => {
            clearInterval(heroInterval);
            showHeroSlide(index);
            heroInterval = setInterval(nextHeroSlide, 5000);
        });
    });
});

// Банерний слайдер
document.addEventListener('DOMContentLoaded', function() {
    const bannerSlider = document.querySelector('.banner-slider');
    const bannerSlides = document.querySelectorAll('.banner-slide');
    const prevBtns = document.querySelectorAll('.prev-banner');
    const nextBtns = document.querySelectorAll('.next-banner');
    const indicators = document.querySelectorAll('.banner-indicator');
    let currentBanner = 0;
    let bannerInterval;
    
    function showBanner(index) {
        bannerSlides.forEach(slide => slide.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));
        bannerSlides[index].classList.add('active');
        indicators[index].classList.add('active');
        currentBanner = index;
        resetBannerInterval();
    }
    
    function nextBanner() {
        const nextIndex = (currentBanner + 1) % bannerSlides.length;
        showBanner(nextIndex);
    }
    
    function prevBanner() {
        const prevIndex = (currentBanner - 1 + bannerSlides.length) % bannerSlides.length;
        showBanner(prevIndex);
    }
    
    function resetBannerInterval() {
        clearInterval(bannerInterval);
        bannerInterval = setInterval(nextBanner, 5000);
    }
    
    prevBtns.forEach(btn => btn.addEventListener('click', prevBanner));
    nextBtns.forEach(btn => btn.addEventListener('click', nextBanner));
    
    indicators.forEach(indicator => {
        indicator.addEventListener('click', function() {
            const slideIndex = parseInt(this.getAttribute('data-slide'));
            showBanner(slideIndex);
        });
    });
    
    resetBannerInterval();
    
    bannerSlider.addEventListener('mouseenter', () => {
        clearInterval(bannerInterval);
    });
    
    bannerSlider.addEventListener('mouseleave', resetBannerInterval);
});

// Симуляція авторизації
document.addEventListener('DOMContentLoaded', function() {
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
    
    profileBtn.addEventListener('click', function() {
        window.location.href = 'profile.html';
    });
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

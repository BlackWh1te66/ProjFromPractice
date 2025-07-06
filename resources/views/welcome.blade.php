<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Головна | Слава-сервіс</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-tools"></i>
                <span>Слава-сервіс</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> Головна</a></li>
                    <li><a href="{{ url('/about') }}"><i class="fas fa-info-circle"></i> Про нас</a></li>
                    <li><a href="{{ url('/services') }}"><i class="fas fa-concierge-bell"></i> Послуги</a></li>
                    <li><a href="{{ url('/ses') }}"><i class="fas fa-solar-panel"></i> СЕС</a></li>
                    <li><a href="{{ url('/prices') }}"><i class="fas fa-shopping-cart"></i> Каталог</a></li>
                    <li><a href="{{ url('/contacts') }}"><i class="fas fa-address-book"></i> Контакти</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <button class="theme-switcher" id="themeSwitcher">🌓</button>
                @auth
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileDropdownBtn">
                            <img src="{{ Auth::user()->getAvatarUrl() }}" alt="avatar" class="profile-avatar-mini">
                            <span>{{ Auth::user()->username }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="profile-dropdown-menu" id="profileDropdownMenu">
                            <a href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Профіль</a>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ url('/profile-admin') }}"><i class="fas fa-cogs"></i> Панель адміністратора</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Вийти</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ url('/login') }}" class="login-btn" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Увійти</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1517430816045-df4b7de11d1d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
            <div class="hero-content">
                <h1>Професійний ремонт побутової техніки</h1>
                <p>Швидко, якісно, з гарантією. Наші майстри відремонтують вашу техніку в найкоротші терміни.</p>
                <a href="{{ url('/services') }}" class="btn">Замовити послугу</a>
            </div>
        </div>
        
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
            <div class="hero-content">
                <h1>Сонячні електростанції</h1>
                <p>Проектування, монтаж та обслуговування сонячних електростанцій для вашого дому чи бізнесу.</p>
                <a href="{{ url('/ses') }}" class="btn">Дізнатись про СЕС</a>
            </div>
        </div>
        
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
            <div class="hero-content">
                <h1>Онлайн-магазин запчастин</h1>
                <p>Широкий вибір запчастин та аксесуарів для побутової техніки з доставкою по всій Україні.</p>
                <a href="{{ url('/prices') }}" class="btn">Переглянути каталог</a>
            </div>
        </div>
        
        <div class="slide-controls">
            <div class="slide-control active" data-slide="0"></div>
            <div class="slide-control" data-slide="1"></div>
            <div class="slide-control" data-slide="2"></div>
        </div>
    </section>

    <section class="site-description">
        <div class="container">
            <p>Компанія "Слава-сервіс" - це команда професіоналів з більш ніж 15-річним досвідом роботи у сфері ремонту побутової техніки та встановлення сонячних електростанцій. Ми пропонуємо якісні послуги, використовуємо оригінальні запчастини та надаємо гарантію на всі види робіт. Наша мета - забезпечити клієнтів надійними рішеннями для комфортного побуту та енергоефективності.</p>
        </div>
    </section>

    <section class="container">
        <div class="banner-slider">
            <div class="banner-slide active" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                <div class="banner-content">
                    <span class="banner-badge">АКЦІЯ</span>
                    <h3>Спеціальна пропозиція на СЕС</h3>
                    <p>До 30 червня знижка 15% на монтаж сонячних електростанцій потужністю від 10 кВт. Встигніть скористатися вигідною пропозицією!</p>
                </div>
            </div>
            
            <div class="banner-slide" style="background-image: url('https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                <div class="banner-content">
                    <span class="banner-badge">НОВИНА</span>
                    <h3>Розширення асортименту</h3>
                    <p>Тепер у магазині доступні запчастини для нових моделей побутової техніки від провідних виробників. Оновлений каталог вже на сайті!</p>
                </div>
            </div>
            
            <div class="banner-slide" style="background-image: url('https://images.unsplash.com/photo-1600566752225-555b8f58f5c1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                <div class="banner-content">
                    <span class="banner-badge">АКЦІЯ</span>
                    <h3>Безкоштовна діагностика</h3>
                    <p>Протягом липня при замовленні ремонту - діагностика безкоштовно! Записуйтесь заздалегідь, кількість місць обмежена.</p>
                </div>
            </div>
            
            <div class="banner-controls">
                <button class="banner-control prev-banner"><i class="fas fa-chevron-left"></i></button>
                <button class="banner-control next-banner"><i class="fas fa-chevron-right"></i></button>
            </div>
            
            <div class="banner-indicators">
                <div class="banner-indicator active" data-slide="0"></div>
                <div class="banner-indicator" data-slide="1"></div>
                <div class="banner-indicator" data-slide="2"></div>
            </div>
        </div>
    </section>

    <section class="main-services">
        <div class="container">
            <h2 class="section-title">Основні послуги</h2>
            <div class="services-grid">
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="service-info">
                        <h3>Ремонт техніки</h3>
                        <p>Професійний ремонт всіх видів побутової техніки: холодильників, пральних машин, посудомийок, плит та інше.</p>
                        <a href="{{ url('/services') }}" class="service-link">Детальніше <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-solar-panel"></i>
                    </div>
                    <div class="service-info">
                        <h3>Сонячні електростанції</h3>
                        <p>Проектування, монтаж та обслуговування сонячних електростанцій для приватних будинків та бізнесу.</p>
                        <a href="{{ url('/ses') }}" class="service-link">Детальніше <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="service-info">
                        <h3>Продаж запчастин</h3>
                        <p>Широкий вибір оригінальних та аналогових запчастин для побутової техніки з доставкою по всій Україні.</p>
                        <a href="{{ url('/prices') }}" class="service-link">Детальніше <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container footer-content">
            <div class="footer-section">
                <h3>Контакти</h3>
                <p><i class="fas fa-map-marker-alt"></i> м. Житомир, 3-й провулок Госпітальний, 5</p>
                <p><i class="fas fa-phone"></i> +38 (097) 829-43-36</p>
                <p><i class="fas fa-envelope"></i> slava-service@ukr.net</p>
            </div>
            <div class="footer-section">
                <h3>Графік роботи</h3>
                <p><i class="far fa-clock"></i> Пн-Пт: 10:00–18:00</p>
                <p><i class="far fa-clock"></i> Сб: 10:00–14:00</p>
                <p><i class="fas fa-ban"></i> Нд: вихідний</p>
            </div>
            <div class="footer-section">
                <h3>Навігація</h3>
                <ul>
                    <li><a href="{{ url('/') }}"><i class="fas fa-chevron-right"></i> Головна</a></li>
                    <li><a href="{{ url('/about') }}"><i class="fas fa-chevron-right"></i> Про нас</a></li>
                    <li><a href="{{ url('/services') }}"><i class="fas fa-chevron-right"></i> Послуги</a></li>
                    <li><a href="{{ url('/prices') }}"><i class="fas fa-chevron-right"></i> Каталог</a></li>
                    <li><a href="{{ url('/contacts') }}"><i class="fas fa-chevron-right"></i> Контакти</a></li>
                    <li><a href="{{ url('/ses') }}"><i class="fas fa-chevron-right"></i> СЕС</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Ми в соцмережах</h3>
                <div class="social-links">
                    <a href="https://www.instagram.com/slava_service/" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/slava.service.zt" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="http://www.youtube.com/@slavaservice2008" target="_blank" class="social-icon"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.tiktok.com/@slava_service" target="_blank" class="social-icon"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2023 Слава-сервіс. Всі права захищені.</p>
        </div>
    </footer>

    <script src="js/welcome.js"></script>
</body>
</html>
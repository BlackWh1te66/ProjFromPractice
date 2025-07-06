<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Про нас | Слава-сервіс</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
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

    <section class="about-hero">
        <div class="container about-hero-content">
            <h1>Про нашу компанію</h1>
            <p>Ми - команда професіоналів з багаторічним досвідом у сфері ремонту техніки та встановлення СЕС</p>
        </div>
    </section>

    <section class="about-content container">

        <h2 class="section-title">Наша історія</h2>
        <div class="about-text">
            <p>Слава-сервіс було засновано у 2008 році як невеликий сервісний центр з ремонту побутової техніки. За роки роботи ми перетворилися на повноцінний сервіс з комплексним обслуговуванням техніки, встановленням сонячних електростанцій та онлайн-магазином запчастин.</p>
        </div>
        
        <div class="history-timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">2008</div>
                    <p>Заснування компанії як невеликої майстерні з ремонту побутової техніки</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">2012</div>
                    <p>Отримання статусу авторизованого сервісного центру від провідних виробників техніки</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">2015</div>
                    <p>Запуск онлайн-магазину запчастин з доставкою по всій Україні</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">2018</div>
                    <p>Початок роботи у сфері проектування та встановлення сонячних електростанцій</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">2023</div>
                    <p>Відкриття нового сервісного центру з сучасним обладнанням та розширенням спектру послуг</p>
                </div>
            </div>
        </div>

        <div class="mission-values">
            <div class="mission-card">
                <i class="fas fa-bullseye"></i>
                <h3>Наша місія</h3>
                <p>Надавати якісні послуги з ремонту та обслуговування техніки, використовуючи сучасні технології та індивідуальний підхід до кожного клієнта.</p>
            </div>
            <div class="mission-card">
                <i class="fas fa-eye"></i>
                <h3>Наші цінності</h3>
                <p>Чесність, професіоналізм, відповідальність та інноваційність - це основні принципи нашої роботи.</p>
            </div>
            <div class="mission-card">
                <i class="fas fa-chart-line"></i>
                <h3>Наші досягнення</h3>
                <p>За останні 3 роки ми встановили понад 50 сонячних електростанцій та виконали понад 5000 ремонтів техніки.</p>
            </div>
        </div>

        <div class="service-center-status">
            <div class="status-content">
                <i class="fas fa-award"></i>
                <h3>Авторизований сервісний центр</h3>
                <p>Ми є офіційно авторизованим сервісним центром провідних виробників побутової техніки, що гарантує якість наших послуг та використання оригінальних запчастин.</p>
            </div>
        </div>

        <div class="team">
            <h2 class="section-title">Наша команда</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Олег Петренко">
                    <h3>Олег Петренко</h3>
                    <p>Технічний директор</p>
                    <p>12 років досвіду</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Ірина Коваль">
                    <h3>Ірина Коваль</h3>
                    <p>Менеджер з обслуговування</p>
                    <p>8 років досвіду</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Андрій Сидоренко">
                    <h3>Андрій Сидоренко</h3>
                    <p>Інженер з ремонту</p>
                    <p>6 років досвіду</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="certificates">
            <h2 class="section-title">Наші сертифікати</h2>
            <div class="certificates-grid">
                <div class="certificate">
                    <img src="https://via.placeholder.com/300x200/0056b3/ffffff?text=Microsoft" alt="Сертифікат Microsoft">
                    <p>Сертифікат від Microsoft</p>
                </div>
                <div class="certificate">
                    <img src="https://via.placeholder.com/300x200/0056b3/ffffff?text=Intel" alt="Сертифікат Intel">
                    <p>Сертифікат від Intel</p>
                </div>
                <div class="certificate">
                    <img src="https://via.placeholder.com/300x200/0056b3/ffffff?text=Cisco" alt="Сертифікат Cisco">
                    <p>Сертифікат від Cisco</p>
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

    <script src="js/about.js"></script>
</body>
</html>
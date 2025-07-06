<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакти | Слава-сервіс</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/contacts.css') }}">
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

    <section class="contacts-hero">
        <div class="container contacts-hero-content">
            <h1>Наші контакти</h1>
            <p>Ми завжди раді допомогти вам з вашою технікою. Зв'яжіться з нами будь-яким зручним для вас способом.</p>
        </div>
    </section>

    <section class="contacts-content container">
        <h2 class="section-title">Зв'яжіться з нами</h2>

        <div class="contacts-grid">
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Наша адреса</h3>
                <div class="contact-info">м. Житомир, 3-й провулок Госпітальний, 5</div>
                <div class="contact-info">
                    <a href="https://goo.gl/maps/xyz" target="_blank" class="contact-link">Подивитися на мапі</a>
                </div>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                <h3>Телефони</h3>
                <div class="contact-info">
                    <a href="tel:+380978294336" class="contact-link">+38 (097) 829-43-36</a>
                </div>
                <div class="contact-info">
                    <a href="tel:+380123456789" class="contact-link">+38 (012) 345-67-89</a>
                </div>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <h3>Електронна пошта</h3>
                <div class="contact-info">
                    <a href="mailto:slava-service@ukr.net" class="contact-link">slava-service@ukr.net</a>
                </div>
                <div class="contact-info">
                    <a href="mailto:support@slava-service.com" class="contact-link">support@slava-service.com</a>
                </div>
            </div>
        </div>

        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2259.0522337189377!2d28.685575876160048!3d50.24710997155403!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x472c6363ebf559e5%3A0xc6a5540f909db48e!2zMy3QuNC5INCT0L7RgdC_0ZbRgtCw0LvRjNC90LjQuSDQv9GA0L7QstGD0LvQvtC6LCA1LCDQltC40YLQvtC80LjRgCwg0JbQuNGC0L7QvNC40YDRgdGM0LrQsCDQvtCx0LvQsNGB0YLRjCwgMTAwMDE!5e1!3m2!1sru!2sua!4v1750077887249!5m2!1sru!2sua" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="working-hours">
            <h2 class="section-title">Графік роботи</h2>
            <table class="hours-table">
                <thead>
                    <tr>
                        <th>День</th>
                        <th>Години роботи</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Понеділок - П'ятниця</td>
                        <td>09:00 - 18:00</td>
                    </tr>
                    <tr>
                        <td>Субота</td>
                        <td>10:00 - 15:00</td>
                    </tr>
                    <tr>
                        <td>Неділя</td>
                        <td>Вихідний</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="contact-form-container">
            <h2 class="section-title">Форма зворотного зв'язку</h2>
            @auth
            <form id="feedbackForm" class="contact-form" method="POST" action="/admin/feedback">
                @csrf
                <div class="form-group">
                    <label for="name">Ваше ім'я:</label>
                    <input type="text" id="name" name="name" required placeholder="Введіть ваше ім'я" value="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                </div>
                <div class="form-group">
                    <label for="phone">Телефон:</label>
                    <input type="tel" id="phone" name="phone" required placeholder="Введіть ваш телефон" value="{{ Auth::user()->phone }}">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Введіть ваш email (необов'язково)" value="{{ Auth::user()->email }}">
                </div>
                <div class="form-group">
                    <label for="service">Послуга:</label>
                    <select id="service" name="service">
                        <option value="">Оберіть послугу</option>
                        <option value="repair">Ремонт комп'ютера</option>
                        <option value="laptop">Ремонт ноутбука</option>
                        <option value="data">Відновлення даних</option>
                        <option value="network">Мережеві послуги</option>
                        <option value="software">Програмне забезпечення</option>
                        <option value="other">Інше</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Повідомлення:</label>
                    <textarea id="message" name="message" placeholder="Опишіть вашу проблему або питання"></textarea>
                </div>
                <button type="submit" class="btn">Надіслати повідомлення</button>
            </form>
            @else
            <div style="text-align:center; color:#888; padding:2rem;">
                Щоб скористатися формою зворотного зв'язку, <a href="{{ url('/login') }}">увійдіть у свій акаунт</a>.
            </div>
            @endauth
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

    <script src="js/contacts.js"></script>
</body>
</html>
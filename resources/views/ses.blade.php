<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Послуги по монтажу СЕС | Слава-сервіс</title>
    <link rel="stylesheet" href="{{ asset('css/ses.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <section class="solar-hero">
        <div class="container solar-hero-content">
            <h1>Сонячні електростанції для вашого дому та бізнесу</h1>
            <p>Професійний монтаж сонячних електростанцій під ключ з гарантією якості</p>
            <a href="#application-href" class="btn">Залишити заявку</a>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Чому варто обрати сонячну енергетику?</h2>
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <h3>Економія коштів</h3>
                    <p>Зменшення витрат на електроенергію до 90% та можливість продажу надлишків в мережу</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fas fa-bolt"></i></div>
                    <h3>Енергонезалежність</h3>
                    <p>Захист від зростання тарифів та перебоїв у централізованому електропостачанні</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fas fa-leaf"></i></div>
                    <h3>Екологічність</h3>
                    <p>Чиста енергія без шкідливих викидів та впливу на навколишнє середовище</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section" style="background: #f8f9fa;">
        <div class="container">
            <h2 class="section-title">Калькулятор вартості СЕС</h2>
            <div class="calculator-container" id="calculator">
                <div class="calculator-form">
                    <div class="form-group">
                        <label for="system-type">Тип системи:</label>
                        <select id="system-type" onchange="calculateCost()">
                            <option value="grid">Мережева СЕС</option>
                            <option value="hybrid">Гібридна СЕС</option>
                            <option value="offgrid">Автономна СЕС</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="power">Потужність системи (кВт):</label>
                        <input type="range" id="power" min="1" max="30" value="5" step="1" class="range-slider" oninput="updatePowerValue()" onchange="calculateCost()">
                        <div class="range-value" id="power-value">5 кВт</div>
                    </div>
                    <div class="form-group">
                        <label for="roof-type">Тип покрівлі:</label>
                        <select id="roof-type" onchange="calculateCost()">
                            <option value="metal">Металева</option>
                            <option value="tile">Черепиця</option>
                            <option value="flat">Плоска</option>
                            <option value="ground">Грунтове кріплення</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="battery">Акумулятори:</label>
                        <select id="battery" onchange="calculateCost()">
                            <option value="none">Без акумуляторів</option>
                            <option value="small">Мала ємність (8-12 кВт·год)</option>
                            <option value="medium">Середня ємність (12-20 кВт·год)</option>
                            <option value="large">Велика ємність (20+ кВт·год)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="brand">Бренд обладнання:</label>
                        <select id="brand" onchange="calculateCost()">
                            <option value="premium">Premium (SunPower, LG)</option>
                            <option value="standard" selected>Standard (JA Solar, Longi)</option>
                            <option value="budget">Budget (Trina Solar, Canadian Solar)</option>
                        </select>
                    </div>
                </div>
                <div class="calculator-result">
                    <div class="result-item">
                        <div class="result-description">Орієнтовна вартість:</div>
                        <div class="result-value" id="total-cost">$5,000</div>
                    </div>
                    <div class="result-item">
                        <div class="result-description">Термін окупності:</div>
                        <div class="result-value" id="payback">4-6 років</div>
                    </div>
                    <div class="result-item">
                        <div class="result-description">Економія на рік:</div>
                        <div class="result-value" id="savings">$800-$1,200</div>
                    </div>
                    <div class="result-item">
                        <div class="result-description">Гарантія:</div>
                        <div class="result-value">10-25 років</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="application-href">
        <div class="container">
            <h2 class="section-title">Залишити заявку на встановлення СЕС</h2>
            <div class="application-form">
                <form id="solar-application">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="name">Ваше ім'я*</label>
                                <input type="text" id="name" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="phone">Телефон*</label>
                                <input type="tel" id="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="location">Місцезнаходження об'єкта*</label>
                                <input type="text" id="location" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="system-config">Обрана конфігурація системи</label>
                        <input type="text" id="system-config" value="Мережева СЕС, 5 кВт, металева покрівль, Standard бренд" readonly>
                    </div>
                    <div class="form-group">
                        <label for="message">Додаткова інформація</label>
                        <textarea id="message" placeholder="Опишіть ваш об'єкт, особливі побажання, терміни реалізації тощо"></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Надіслати заявку</button>
                </form>
            </div>
        </div>
    </section>

    <section class="section" style="background: #f8f9fa;">
        <div class="container">
            <h2 class="section-title">Типи сонячних електростанцій</h2>
            <div class="systems-grid">
                <div class="system-card">
                    <div class="system-image" style="background-image: url('https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80');"></div>
                    <div class="system-content">
                        <h3>Мережева СЕС</h3>
                        <p>Ідеальний варіант для отримання доходу за "Зеленим тарифом"</p>
                        <ul>
                            <li>Підключення до загальнодержавної мережі</li>
                            <li>Продаж надлишків електроенергії</li>
                            <li>Відсутність акумуляторів</li>
                            <li>Максимальна простота та ефективність</li>
                        </ul>
                    </div>
                </div>
                <div class="system-card">
                    <div class="system-image" style="background-image: url('https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80');"></div>
                    <div class="system-content">
                        <h3>Автономна СЕС</h3>
                        <p>Для об'єктів без можливості підключення до центральної мережі</p>
                        <ul>
                            <li>Повна енергонезалежність</li>
                            <li>Акумуляторні батареї для зберігання енергії</li>
                            <li>Ідеально для дач, котеджів, віддалених об'єктів</li>
                            <li>Захист від відключень світла</li>
                        </ul>
                    </div>
                </div>
                <div class="system-card">
                    <div class="system-image" style="background-image: url('https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80');"></div>
                    <div class="system-content">
                        <h3>Гібридна СЕС</h3>
                        <p>Поєднання переваг мережевої та автономної систем</p>
                        <ul>
                            <li>Робота від сонця, мережі та акумуляторів</li>
                            <li>Максимальна ефективність та надійність</li>
                            <li>Можливість участі в "Зеленому тарифі"</li>
                            <li>Захист від відключень електроенергії</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Приклади наших робіт</h2>
            <div class="examples-grid">
                <div class="example-card">
                    <img src="https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="СЕС для приватного будинку" class="example-image">
                    <div class="example-overlay">
                        <h3>Приватний будинок, 10 кВт</h3>
                        <p>Київська область, м. Ірпінь</p>
                    </div>
                </div>
                <div class="example-card">
                    <img src="https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="СЕС для бізнесу" class="example-image">
                    <div class="example-overlay">
                        <h3>Кафе, 15 кВт</h3>
                        <p>м. Житомир</p>
                    </div>
                </div>
                <div class="example-card">
                    <img src="https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="СЕС для фермерського господарства" class="example-image">
                    <div class="example-overlay">
                        <h3>Фермерське господарство, 30 кВт</h3>
                        <p>Житомирська область</p>
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

    <script src="js/ses.js"></script>
</body>
</html>
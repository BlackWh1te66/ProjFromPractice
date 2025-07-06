<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог послуг | Слава-сервіс</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    @auth
        <script>
            window.authUser = {
                first_name: "{{ Auth::user()->first_name }}",
                phone: "{{ Auth::user()->phone }}",
                email: "{{ Auth::user()->email }}"
            };
            document.addEventListener('DOMContentLoaded', function() {
                // Автоматично підставляємо значення у форму замовлення, якщо користувач авторизований
                if (document.getElementById('clientName')) {
                    document.getElementById('clientName').value = "{{ Auth::user()->first_name }}";
                }
                if (document.getElementById('clientPhone')) {
                    document.getElementById('clientPhone').value = "{{ Auth::user()->phone }}";
                }
                if (document.getElementById('clientEmail')) {
                    document.getElementById('clientEmail').value = "{{ Auth::user()->email }}";
                }
            });
        </script>
    @endauth
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

    <section class="services-hero">
        <div class="container services-hero-content">
            <h1>Каталог послуг</h1>
            <p>Професійний ремонт та обслуговування техніки з гарантією якості</p>
        </div>
    </section>

    <section class="services-content container">
        <h2 class="section-title">Наші послуги</h2>
        
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Пошук послуг за назвою або описом...">
        </div>
        
        <div class="catalog-container">
            <div class="catalog-sidebar">
                <div class="category-menu">
                    <h3>Категорії послуг</h3>
                    <ul class="category-list">
                        <li class="category-item active">
                            <a href="#" data-category="all">Всі послуги <span class="count" style="float:right;color:#888;">{{ $services->count() }}</span></a>
                        </li>
                        @foreach($serviceCategories as $cat)
                            <li class="category-item">
                                <a href="#" data-category="{{ $cat->id }}">{{ $cat->name }} <span class="count" style="float:right;color:#888;">{{ $services->where('category_id', $cat->id)->count() }}</span></a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="filters">
                    <h3>Фільтри</h3>
                    <div class="filter-group">
                        <h4>Ціна грн</h4>
                        <div class="filter-option">
                            <input type="range" id="priceRange" min="0" max="20000" value="20000" step="100" style="width: 100%;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>0-</span>
                                <span id="maxPriceValue">20000</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="catalog-main">
                <div class="services-grid" id="servicesGrid">
                    <!-- Услуги будут добавлены через JS -->
                </div>
            </div>
        </div>
    </section>

    <!-- Модальне вікно замовлення -->
    <div class="modal-overlay" id="orderModal">
        <div class="order-modal">
            <div class="modal-header">
                <h3 class="modal-title">Оформлення замовлення</h3>
                <button class="close-modal" id="closeModal">&times;</button>
            </div>
            <form class="order-form" id="orderForm">
                <div class="order-info" id="orderServiceInfo">
                    <!-- Інформація про послугу буде додана через JS -->
                </div>
                <div class="form-group">
                    <label for="clientName" class="form-label">Ваше ім'я*</label>
                    <input type="text" id="clientName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="clientPhone" class="form-label">Номер телефону*</label>
                    <input type="tel" id="clientPhone" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="clientEmail" class="form-label">Email</label>
                    <input type="email" id="clientEmail" class="form-input">
                </div>
                <div class="form-group">
                    <label for="clientAddress" class="form-label">Адреса</label>
                    <input type="text" id="clientAddress" class="form-input">
                </div>
                <button type="submit" class="submit-btn">Відправити заявку</button>
            </form>
        </div>
    </div>

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

    <!-- Передача данных из PHP в JavaScript -->
    <script>
        // Передаем данные услуг из PHP в JavaScript
        window.services = @json(
            $services->map(function($service) {
                $serviceArr = $service->toArray();
                $serviceArr['title'] = $service->name;
                // Исправлено: не добавляем storage/ если путь уже содержит storage/
                $img = $service->image;
                if ($img && str_starts_with($img, 'services/')) {
                    $serviceArr['image'] = asset('storage/' . $img);
                } else if ($img) {
                    $serviceArr['image'] = asset($img);
                } else {
                    $serviceArr['image'] = asset('img/no-image.png');
                }
                $serviceArr['time'] = $service->duration_minutes ? 
                    ($service->duration_minutes <= 1440 ? 'fast' : 
                     ($service->duration_minutes <= 4320 ? 'standard' : 'long')) : 'standard';
                return $serviceArr;
            })
        );

        // Передаем категории
        window.serviceCategories = @json($serviceCategories);

        // Логируем данные для отладки
        console.log('Services loaded:', window.services);
        console.log('Categories loaded:', window.serviceCategories);
    </script>
    
    <script src="{{ asset('js/services.js') }}"></script>
</body>
</html>
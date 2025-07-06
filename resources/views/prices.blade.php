<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог товарів | Слава-сервіс</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/prices.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <script>
            window.authUser = {
                first_name: "{{ Auth::user()->first_name }}",
                phone: "{{ Auth::user()->phone }}",
                email: "{{ Auth::user()->email }}"
            };
            document.addEventListener('DOMContentLoaded', function() {
                // Автоматически подставлять значения в форму заказа, если пользователь авторизован
                if (document.getElementById('orderName')) {
                    document.getElementById('orderName').value = "{{ Auth::user()->first_name }}";
                }
                if (document.getElementById('orderPhone')) {
                    document.getElementById('orderPhone').value = "{{ Auth::user()->phone }}";
                }
                if (document.getElementById('orderEmail')) {
                    document.getElementById('orderEmail').value = "{{ Auth::user()->email }}";
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

    <section class="catalog-hero">
        <div class="container catalog-hero-content">
            <h1>Каталог товарів</h1>
            <p>Широкий вибір запчастин та аксесуарів для вашого обладнання</p>
        </div>
    </section>

    <section class="catalog-content container">
        <h2 class="section-title">Наші товари</h2>
        
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Пошук товарів за назвою або описом...">
        </div>
        
        <div class="catalog-container">
            <div class="catalog-sidebar">
                <div class="category-menu">
                    <h3>Категорії товарів</h3>
                    <ul class="category-list">
                        <li class="category-item active">
                            <a href="#" data-category="all">Всі товари <span class="count" style="float:right;color:#888;">{{ \App\Models\Product::count() }}</span></a>
                        </li>
                        @foreach($deviceCategories as $cat)
                            <li class="category-item">
                                <a href="#" data-category="{{ $cat->id }}">{{ $cat->name }} <span class="count" style="float:right;color:#888;">{{ $categoryCounts[$cat->id] ?? 0 }}</span> </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="filters">
                    <h3>Фільтри</h3>
                    <div class="filter-group">
                        <h4>Виробник</h4>
                        @foreach($brands as $brand)
                            <div class="filter-option" style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <input type="checkbox" id="brand_{{ $loop->index }}" data-brand="{{ $brand }}">
                                    <label for="brand_{{ $loop->index }}">{{ $brand }}</label>
                                </div>
                                <span class="count" style="color:#888;">{{ $brandCounts[$brand] ?? 0 }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="filter-group">
                        <h4>Наявність</h4>
                        <div class="filter-option">
                            <input type="checkbox" id="stock1" data-stock="in_stock">
                            <label for="stock1">В наявності</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="stock2" data-stock="preorder">
                            <label for="stock2">Під замовлення</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="stock3" data-stock="out_of_stock">
                            <label for="stock3">Немає в наявності</label>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <h4>Ціна грн</h4>
                        <div class="filter-option">
                            <input type="range" id="priceRange" min="0" max="40000" value="40000" step="100" style="width: 100%;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>0-</span>
                                <span id="maxPriceValue">40000</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="catalog-main">
                <div class="products-grid" id="productsGrid">
                    <!-- Товари будуть додані через JavaScript -->
                </div>
            </div>
        </div>
    </section>

    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 class="modal-title">Замовлення товару</h2>
            
            <div class="order-product-info">
                <div class="order-product-image" id="modalProductImage"></div>
                <div class="order-product-details">
                    <h3 class="order-product-title" id="modalProductTitle"></h3>
                    <div class="order-product-price" id="modalProductPrice"></div>
                    <div class="product-stock in-stock">
                        <i class="fas fa-check-circle"></i>
                        <span id="modalProductStock">В наявності</span>
                    </div>
                </div>
            </div>
            
            <form id="orderForm">
                <div class="order-form-group">
                    <label for="orderName">Ваше ім'я *</label>
                    <input type="text" id="orderName" required>
                </div>
                
                <div class="order-form-group">
                    <label for="orderPhone">Телефон *</label>
                    <input type="tel" id="orderPhone" placeholder="+38 (___) ___-__-__" required>
                </div>
                
                <div class="order-form-group">
                    <label for="orderEmail">Email</label>
                    <input type="email" id="orderEmail">
                </div>
                
                <div class="order-form-group">
                    <label for="orderAddress">Адреса доставки</label>
                    <input type="text" id="orderAddress">
                </div>
                
                <button type="submit" class="submit-order-btn">Підтвердити замовлення</button>
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

    <script src="{{ asset('js/prices.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профіль | Слава-сервіс</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/profile-user.css') }}">
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

    <div class="container profile-container">
        <!-- Ліве меню -->
        <aside class="profile-sidebar">
            <div class="profile-info">
                <img src="{{ $user->getAvatarUrl() }}" alt="Аватар користувача" class="profile-avatar" id="userAvatar">
                <h3 class="profile-name" id="userName">{{ $user->username }}</h3>
                <p class="profile-email" id="userEmail">{{ $user->email }}</p>
            </div>
            
            <ul class="profile-menu">
                <li><a href="#" class="active" data-section="personal"><i class="fas fa-user"></i> Особисті дані</a></li>
                <li><a href="#" data-section="orders"><i class="fas fa-history"></i> Історія замовлень</a></li>
                <li><a href="#" data-section="wishlist"><i class="fas fa-heart"></i> Обрані товари</a></li>
                <li><a href="#" data-section="password"><i class="fas fa-lock"></i> Зміна пароля</a></li>
            </ul>
        </aside>
        
        <!-- Основний вміст -->
        <main class="profile-content">
            <!-- Особисті дані -->
            <section class="content-section active" id="personal-section">
                <div class="profile-header">
                    <h2><i class="fas fa-user"></i> Особисті дані</h2>
                    <p>Тут ви можете переглядати та редагувати свої особисті дані</p>
                </div>
                
                <form id="personalForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="avatar-upload">
                        <img src="{{ $user->getAvatarUrl() }}" alt="Аватар" class="avatar-preview" id="avatarPreview">
                        <div class="upload-btn">
                            <label class="btn btn-primary" style="cursor:pointer;">
                                <i class="fas fa-upload"></i> Змінити фото
                                <input type="file" id="avatarUpload" name="avatar" accept="image/*" style="display:none;">
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">Ім'я</label>
                            <input type="text" id="firstName" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Прізвище</label>
                            <input type="text" id="lastName" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Електронна пошта</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="birthday">Дата народження</label>
                        <input type="date" id="birthday" name="birthday" class="form-control" value="{{ old('birthday', $user->birthday) }}">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Зберегти зміни</button>
                    </div>
                </form>
            </section>
            
            <!-- Історія замовлень -->
            <section class="content-section" id="orders-section">
                <div class="profile-header">
                    <h2><i class="fas fa-history"></i> Історія замовлень</h2>
                    <p>Переглядайте статус та деталі своїх замовлень</p>
                </div>
                <div id="orders-loading" style="padding:1.5rem;text-align:center;display:none;">
                    <i class="fas fa-spinner fa-spin"></i> Завантаження...
                </div>
                <table class="orders-table" id="ordersTable">
                    <thead>
                        <tr>
                            <th>ID замовлення</th>
                            <th>Дата</th>
                            <th>Послуга/Товар</th>
                            <th>Сума</th>
                            <th>Статус</th>
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Заказы пользователя будут подгружаться через JS -->
                    </tbody>
                </table>
            </section>
            
            <!-- Обрані товари -->
            <section class="content-section" id="wishlist-section">
                <div class="profile-header">
                    <h2><i class="fas fa-heart"></i> Обрані товари</h2>
                    <p>Товари, які ви додали до списку бажаного</p>
                </div>
                
                <div class="wishlist">
                    <div class="wishlist-item">
                        
                    </div>
                    
                    <div class="wishlist-item">
                        
                    </div>
                    
                    <div class="wishlist-item">
                        
                    </div>
                </div>
            </section>
            
            <!-- Зміна пароля -->
            <section class="content-section" id="password-section">
                <div class="profile-header">
                    <h2><i class="fas fa-lock"></i> Зміна пароля</h2>
                    <p>Для зміни пароля введіть поточний та новий пароль</p>
                </div>
                
                <form id="passwordForm">
                    <div class="form-group">
                        <label for="currentPassword">Поточний пароль</label>
                        <input type="password" id="currentPassword" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="newPassword">Новий пароль</label>
                        <input type="password" id="newPassword" name="new_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">Підтвердіть новий пароль</label>
                        <input type="password" id="confirmPassword" name="new_password_confirmation" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Змінити пароль</button>
                    </div>
                </form>
            </section>
        </main>
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

    <!-- Подключаем JS файл, где уже есть обработка смены пароля -->
    <script src="js/profile-user.js"></script>
    
    <!-- Только загрузка заказов при инициализации -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Загружаем заказы при загрузке страницы
            if (typeof window.loadOrders === 'function') {
                window.loadOrders();
            }
        });

        // Вспомогательная функция для определения класса статуса
        function getStatusClass(status) {
            switch (status) {
                case 'completed':
                    return 'status-completed';
                case 'in-progress':
                    return 'status-in-progress';
                case 'cancelled':
                    return 'status-cancelled';
                default:
                    return '';
            }
        }
    </script>
    
    <style>
    /* Стили для модального окна деталей заказа */
    .order-details-modal {
        position: fixed; 
        left: 0; 
        top: 0; 
        width: 100vw; 
        height: 100vh;
        background: rgba(0,0,0,0.4); 
        display: none; 
        align-items: center; 
        justify-content: center; 
        z-index: 2000;
    }
    .order-details-modal-content {
        background: #fff; 
        padding: 2rem; 
        border-radius: 8px; 
        min-width: 320px; 
        max-width: 90vw; 
        position: relative;
        box-shadow: 0 2px 16px rgba(0,0,0,0.15);
    }
    .dark-theme .order-details-modal-content { 
        background: #222; 
        color: #fff; 
    }
    .order-details-table td { 
        padding: 0.3em 0.7em; 
    }
    </style>
</body>
</html>
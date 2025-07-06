<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель адміністратора | Слава-сервіс</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/profile-admin.css') }}">
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

    <section class="admin-panel">
        <div class="container">
            <h1 class="admin-title">Панель адміністратора</h1>

            <div class="admin-tabs">
                <div class="admin-tab active" data-tab="products">Товари та послуги</div>
                <div class="admin-tab" data-tab="categories">Категорії</div>
                <div class="admin-tab" data-tab="orders">Замовлення</div>
                <div class="admin-tab" data-tab="feedbacks">Зворотній зв'язок</div>
                <div class="admin-tab" data-tab="solar-applications">Заявки на СЕС</div>
            </div>

            <!-- Товари та послуги -->
            <div class="admin-content active" id="products-content">
                <div class="admin-form">
                    <h2>Додати новий товар/послугу</h2>
                    <form id="product-form" method="POST" action="/admin/products" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label for="product-type">Тип</label>
                                <select id="product-type" required>
                                    <option value="">Виберіть тип</option>
                                    <option value="product">Товар</option>
                                    <option value="service">Послуга</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product-category">Категорія</label>
                                <select id="product-category" required>
                                    <option value="">Виберіть категорію</option>
                                    <optgroup label="Категорії послуг" class="service-categories">
                                        @foreach($serviceCategories as $cat)
                                            <option value="service_{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Категорії пристроїв" class="device-categories">
                                        @foreach($deviceCategories as $cat)
                                            <option value="device_{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="product-name">Назва</label>
                            <input type="text" id="product-name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="product-description">Опис</label>
                            <textarea id="product-description" required></textarea>
                        </div>
                        
                        <div class="form-row product-fields" style="display:none;">
                            <div class="form-group">
                                <label for="product-price">Ціна (грн)</label>
                                <input type="number" id="product-price" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="product-stock">Наявність</label>
                                <select id="product-stock">
                                    <option value="in_stock">В наявності</option>
                                    <option value="out_of_stock">Немає в наявності</option>
                                    <option value="preorder">Під замовлення</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product-brand">Виробник</label>
                                <input type="text" id="product-brand">
                            </div>
                            <div class="form-group">
                                <label for="product-image">Зображення</label>
                                <input type="file" id="product-image" accept="image/*">
                            </div>
                        </div>
                        <div class="form-row service-fields" style="display:none;">
                            <div class="form-group">
                                <label for="service-price">Ціна (грн)</label>
                                <input type="number" id="service-price" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="service-duration">Тривалість (хв)</label>
                                <input type="number" id="service-duration" min="1">
                            </div>
                            <div class="form-group">
                                <label for="service-image">Зображення</label>
                                <input type="file" id="service-image" accept="image/*">
                            </div>
                        </div>
                        <button type="submit" class="btn">Зберегти</button>
                    </form>
                </div>
                
                <h2>Список товарів та послуг</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Назва</th>
                            <th>Тип</th>
                            <th>Категорія</th>
                            <th>Ціна</th>
                            <th>Наявність</th>
                            <th style="min-width:120px;">Виробник</th>
                            <th>Зображення</th>
                            <th style="min-width:140px;">Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Product::with('category')->get() as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>Товар</td>
                            <td>{{ $product->category ? $product->category->name : '' }}</td>
                            <td>{{ $product->price }} грн</td>
                            <td>
                                @if($product->stock_status === 'in_stock')
                                    В наявності
                                @elseif($product->stock_status === 'preorder')
                                    Під замовлення
                                @elseif($product->stock_status === 'out_of_stock')
                                    Немає в наявності
                                @else
                                    {{ $product->stock_status }}
                                @endif
                            </td>
                            <td style="max-width:140px;overflow-wrap:break-word;">{{ $product->brand }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="img" style="max-width:60px;max-height:60px;">
                                @endif
                            </td>
                            <td >
                                <button class="btn edit-btn"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger delete-btn"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        @endforeach
                        @foreach(\App\Models\Service::with('category')->get() as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->name }}</td>
                            <td>Послуга</td>
                            <td>{{ $service->category ? $service->category->name : '' }}</td>
                            <td>{{ $service->price }} грн</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                @if($service->image)
                                    <img src="{{ $service->image }}" alt="img" style="max-width:60px;max-height:60px;">
                                @endif
                            </td>
                            <td>
                                <button class="btn edit-btn"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger delete-btn"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Категорії -->
            <div class="admin-content" id="categories-content">
                <h2>Категорії товарів</h2>
                <form id="add-device-category-form" style="margin-bottom:1rem;">
                    <input type="text" id="new-device-category-name" placeholder="Нова категорія товарів" required>
                    <button type="submit" class="btn">Додати</button>
                </form>
                <table class="admin-table" id="device-categories-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Назва</th>
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deviceCategories as $cat)
                        <tr data-id="{{ $cat->id }}">
                            <td>{{ $cat->id }}</td>
                            <td class="cat-name">{{ $cat->name }}</td>
                            <td>
                                <button class="btn edit-device-cat-btn">Редагувати</button>
                                <button class="btn btn-danger delete-device-cat-btn">Видалити</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <h2 style="margin-top:2rem;">Категорії послуг</h2>
                <form id="add-service-category-form" style="margin-bottom:1rem;">
                    <input type="text" id="new-service-category-name" placeholder="Нова категорія послуг" required>
                    <button type="submit" class="btn">Додати</button>
                </form>
                <table class="admin-table" id="service-categories-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Назва</th>
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($serviceCategories as $cat)
                        <tr data-id="{{ $cat->id }}">
                            <td>{{ $cat->id }}</td>
                            <td class="cat-name">{{ $cat->name }}</td>
                            <td>
                                <button class="btn edit-service-cat-btn">Редагувати</button>
                                <button class="btn btn-danger delete-service-cat-btn">Видалити</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Замовлення -->
            <div class="admin-content" id="orders-content">
                <h2>Замовлення</h2>
                <table id="orders-table" class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Товар</th>
                            <th>Ціна</th>
                            <th>Ім'я</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Адреса</th>
                            <th>Дата</th>
                            <th>Статус</th> <!-- Новый столбец -->
                            <th>Завантажити</th> <!-- Новая колонка -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Заказы будут подгружаться через JS -->
                    </tbody>
                </table>
            </div>
            
            <!-- Новый раздел для обратной связи -->
            <div class="admin-content" id="feedbacks-content">
                <h2>Зворотній зв'язок (Форма з контактів)</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ім'я</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Послуга</th>
                            <th>Повідомлення</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedbacks as $fb)
                        <tr>
                            <td>{{ $fb->id }}</td>
                            <td>{{ $fb->name }}</td>
                            <td>{{ $fb->phone }}</td>
                            <td>{{ $fb->email }}</td>
                            <td>{{ $fb->service }}</td>
                            <td>{{ $fb->message }}</td>
                            <td>{{ $fb->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Заявки на СЕС -->
            <div class="admin-content" id="solar-applications-content">
                <h2>Заявки на СЕС</h2>
                <table id="solar-applications-table" class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ім'я</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Локація</th>
                            <th>Конфігурація системи</th>
                            <th>Повідомлення</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Заявки будуть підгружатися через JS -->
                    </tbody>
                </table>
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

    <script src="{{ asset('js/profile-admin.js') }}"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Динамическое отображение категорий по типу
    const typeSelect = document.getElementById('product-type');
    const productFields = document.querySelector('.product-fields');
    const serviceFields = document.querySelector('.service-fields');
    const categorySelect = document.getElementById('product-category');
    const serviceOptGroup = categorySelect.querySelector('.service-categories');
    const deviceOptGroup = categorySelect.querySelector('.device-categories');

    function updateFormFields() {
        const type = typeSelect.value;
        // Сброс выбора
        categorySelect.value = '';
        serviceOptGroup.style.display = 'none';
        deviceOptGroup.style.display = 'none';
        productFields.style.display = 'none';
        serviceFields.style.display = 'none';
        if (type === 'service') {
            serviceOptGroup.style.display = '';
            serviceFields.style.display = '';
        } else if (type === 'product') {
            deviceOptGroup.style.display = '';
            productFields.style.display = '';
        }
    }
    // Инициализация при загрузке
    updateFormFields();

    typeSelect.addEventListener('change', function() {
        updateFormFields();
        // ...ваша логика для product-only/service-only полей...
    });

    // Обработка клика на кнопку редактирования
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const id = row.children[0].innerText;
            const name = row.children[1].innerText;
            const type = row.children[2].innerText;
            const category = row.children[3].innerText;
            const price = row.children[4].innerText.replace(' грн', '');
            const stock = row.children[5].innerText;
            const brand = row.children[6] ? row.children[6].innerText : '';
            // ...если нужно описание и др. поля, добавьте их аналогично...

            // Определяем тип для API
            let apiType = '';
            if (type === 'Товар') apiType = 'product';
            if (type === 'Послуга') apiType = 'service';

            // Формируем HTML для формы
            let formHtml = `
                <input type="hidden" id="edit-id" value="${id}">
                <input type="hidden" id="edit-type" value="${apiType}">
            `;

            if (apiType === 'product') {
                formHtml += `
                    <div class="form-group">
                        <label for="edit-product-name">Назва</label>
                        <input type="text" id="edit-product-name" value="${name}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-product-category">Категорія</label>
                        <select id="edit-product-category" required>
                            <option value="">Виберіть категорію</option>
                            <optgroup label="Категорії пристроїв" class="device-categories">
                                @foreach($deviceCategories as $cat)
                                    <option value="{{ $cat->id }}" ${category === '{{ $cat->name }}' ? 'selected' : ''}>{{ $cat->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-product-description">Опис</label>
                        <textarea id="edit-product-description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-product-price">Ціна (грн)</label>
                        <input type="number" id="edit-product-price" value="${price}" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="edit-product-stock">Наявність</label>
                        <select id="edit-product-stock">
                            <option value="in_stock" ${stock === 'В наявності' ? 'selected' : ''}>В наявності</option>
                            <option value="out_of_stock" ${stock === 'Немає в наявності' ? 'selected' : ''}>Немає в наявності</option>
                            <option value="preorder" ${stock === 'Під замовлення' ? 'selected' : ''}>Під замовлення</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-product-brand">Виробник</label>
                        <input type="text" id="edit-product-brand" value="${brand}">
                    </div>
                    <div class="form-group">
                        <label for="edit-product-image">Зображення</label>
                        <input type="file" id="edit-product-image" accept="image/*">
                    </div>
                `;
            } else if (apiType === 'service') {
                formHtml += `
                    <div class="form-group">
                        <label for="edit-service-name">Назва</label>
                        <input type="text" id="edit-service-name" value="${name}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-service-category">Категорія</label>
                        <select id="edit-service-category" required>
                            <option value="">Виберіть категорію</option>
                            <optgroup label="Категорії послуг" class="service-categories">
                                @foreach($serviceCategories as $cat)
                                    <option value="{{ $cat->id }}" ${category === '{{ $cat->name }}' ? 'selected' : ''}>{{ $cat->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-service-description">Опис</label>
                        <textarea id="edit-service-description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-service-price">Ціна (грн)</label>
                        <input type="number" id="edit-service-price" value="${price}" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="edit-service-duration">Тривалість (хв)</label>
                        <input type="number" id="edit-service-duration" min="1">
                    </div>
                    <div class="form-group">
                        <label for="edit-service-image">Зображення</label>
                        <input type="file" id="edit-service-image" name="image" accept="image/*">
                        <div id="edit-service-image-preview"></div>
                    </div>
                `;
            }
            formHtml += `<button type="submit" class="btn" id="edit-save-btn">Зберегти</button>`;

            document.getElementById('edit-form').innerHTML = formHtml;

            // Показ модального окна
            document.getElementById('editModal').style.display = 'flex';

            // Добавляем обработчик submit для формы
            document.getElementById('edit-form').onsubmit = async function(e) {
                e.preventDefault();
                const id = document.getElementById('edit-id').value;
                const type = document.getElementById('edit-type').value;
                let url = '';
                let payload = {};
                let method = 'PUT';

                if (type === 'product') {
                    url = `/admin/products/${id}`;
                    payload = {
                        name: document.getElementById('edit-product-name').value,
                        category_id: document.getElementById('edit-product-category').value,
                        description: document.getElementById('edit-product-description').value,
                        price: document.getElementById('edit-product-price').value,
                        stock_status: document.getElementById('edit-product-stock').value,
                        brand: document.getElementById('edit-product-brand').value
                        // Для image нужен FormData, если нужно — реализуйте отдельно
                    };
                } else if (type === 'service') {
                    url = `/admin/services/${id}`;
                    payload = {
                        name: document.getElementById('edit-service-name').value,
                        category_id: document.getElementById('edit-service-category').value,
                        description: document.getElementById('edit-service-description').value,
                        price: document.getElementById('edit-service-price').value,
                        duration_minutes: document.getElementById('edit-service-duration').value
                    };
                }

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json();
                    if (data.success) {
                        alert('Збережено успішно!');
                        document.getElementById('editModal').style.display = 'none';
                        // Можно обновить таблицу через location.reload() или ajax
                        location.reload();
                    } else {
                        alert('Помилка при збереженні! ' + (data.message || ''));
                    }
                } catch (err) {
                    alert('Помилка з\'єднання з сервером!');
                }
            };
        });
    });

    // Закрытие модального окна
    document.getElementById('closeEditModal').addEventListener('click', function() {
        document.getElementById('editModal').style.display = 'none';
    });

    // Обработка отправки формы редактирования
    document.getElementById('edit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const name = document.getElementById('edit-product-name').value;
        const type = document.getElementById('edit-product-type').value;
        const category = document.getElementById('edit-product-category').value;
        const description = document.getElementById('edit-product-description').value;
        const price = document.getElementById('edit-product-price').value;
        const stock = document.getElementById('edit-product-stock').value;
        
        // ...ваша логика для обновления товара/услуги...
        
        // Закрытие модального окна после сохранения
        document.getElementById('editModal').style.display = 'none';
    });

    // Обработка клика вне модального окна
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });

    // Переключение вкладок, чтобы показывать categories-content
    document.querySelectorAll('.admin-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.admin-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            const content = document.getElementById(`${tabId}-content`);
            if (content) content.classList.add('active');
            // Загружаем только нужную таблицу
            if (tabId === 'orders') loadOrders();
            if (tabId === 'solar-applications') loadSolarApplications();
        });
    });

    // --- CRUD device categories ---
    const deviceTable = document.getElementById('device-categories-table').querySelector('tbody');
    document.getElementById('add-device-category-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('new-device-category-name').value.trim();
        if (!name) return;
        fetch('/admin/device-categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.category) {
                const tr = document.createElement('tr');
                tr.setAttribute('data-id', data.category.id);
                tr.innerHTML = `
                    <td>${data.category.id}</td>
                    <td class="cat-name">${data.category.name}</td>
                    <td>
                        <button class="btn edit-device-cat-btn">Редагувати</button>
                        <button class="btn btn-danger delete-device-cat-btn">Видалити</button>
                    </td>
                `;
                deviceTable.appendChild(tr);
                document.getElementById('new-device-category-name').value = '';
            } else {
                alert('Помилка при додаванні категорії!');
            }
        });
    });

    deviceTable.addEventListener('click', function(e) {
        const tr = e.target.closest('tr');
        if (!tr) return;
        const id = tr.getAttribute('data-id');
        if (e.target.classList.contains('edit-device-cat-btn')) {
            const nameTd = tr.querySelector('.cat-name');
            const oldName = nameTd.textContent;
            const input = document.createElement('input');
            input.type = 'text';
            input.value = oldName;
            nameTd.innerHTML = '';
            nameTd.appendChild(input);
            input.focus();
            input.addEventListener('blur', function() {
                const newName = input.value.trim();
                if (newName && newName !== oldName) {
                    fetch(`/admin/device-categories/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ name: newName })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            nameTd.textContent = newName;
                        } else {
                            nameTd.textContent = oldName;
                            alert('Помилка при редагуванні!');
                        }
                    });
                } else {
                    nameTd.textContent = oldName;
                }
            });
        }
        if (e.target.classList.contains('delete-device-cat-btn')) {
            if (confirm('Видалити цю категорію?')) {
                fetch(`/admin/device-categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        tr.remove();
                    } else {
                        alert('Помилка при видаленні!');
                    }
                });
            }
        }
    });

    // --- CRUD service categories ---
    const serviceTable = document.getElementById('service-categories-table').querySelector('tbody');
    document.getElementById('add-service-category-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('new-service-category-name').value.trim();
        if (!name) return;
        fetch('/admin/service-categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.category) {
                const tr = document.createElement('tr');
                tr.setAttribute('data-id', data.category.id);
                tr.innerHTML = `
                    <td>${data.category.id}</td>
                    <td class="cat-name">${data.category.name}</td>
                    <td>
                        <button class="btn edit-service-cat-btn">Редагувати</button>
                        <button class="btn btn-danger delete-service-cat-btn">Видалити</button>
                    </td>
                `;
                serviceTable.appendChild(tr);
                document.getElementById('new-service-category-name').value = '';
            } else {
                alert('Помилка при додаванні категорії!');
            }
        });
    });

    serviceTable.addEventListener('click', function(e) {
        const tr = e.target.closest('tr');
        if (!tr) return;
        const id = tr.getAttribute('data-id');
        if (e.target.classList.contains('edit-service-cat-btn')) {
            const nameTd = tr.querySelector('.cat-name');
            const oldName = nameTd.textContent;
            const input = document.createElement('input');
            input.type = 'text';
            input.value = oldName;
            nameTd.innerHTML = '';
            nameTd.appendChild(input);
            input.focus();
            input.addEventListener('blur', function() {
                const newName = input.value.trim();
                if (newName && newName !== oldName) {
                    fetch(`/admin/service-categories/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ name: newName })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            nameTd.textContent = newName;
                        } else {
                            nameTd.textContent = oldName;
                            alert('Помилка при редагуванні!');
                        }
                    });
                } else {
                    nameTd.textContent = oldName;
                }
            });
        }
        if (e.target.classList.contains('delete-service-cat-btn')) {
            if (confirm('Видалити цю категорію?')) {
                fetch(`/admin/service-categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        tr.remove();
                    } else {
                        alert('Помилка при видаленні!');
                    }
                });
            }
        }
    });

    // --- Обработчик удаления товара или послуги ---
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const id = row.children[0].innerText;
            const type = row.children[2].innerText;
            let apiType = '';
            if (type === 'Товар') apiType = 'product';
            if (type === 'Послуга') apiType = 'service';
            if (!apiType) return;

            if (confirm('Видалити цей елемент?')) {
                let url = '';
                if (apiType === 'product') url = `/admin/products/${id}`;
                if (apiType === 'service') url = `/admin/services/${id}`;
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                    } else {
                        alert('Помилка при видаленні! ' + (data.message || ''));
                    }
                })
                .catch(() => alert('Помилка з\'єднання з сервером!'));
            }
        });
    });

    // Загрузка заказов
    function loadOrders() {
        fetch('/admin/orders')
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#orders-table tbody');
                tbody.innerHTML = '';
                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;">Немає замовлень</td></tr>';
                } else {
                    data.forEach(order => {
                        tbody.innerHTML += `
                            <tr data-order-id="${order.id}">
                                <td>${order.id}</td>
                                <td>${order.product_name}</td>
                                <td>${order.product_price || ''}</td>
                                <td>${order.customer_name}</td>
                                <td>${order.customer_phone}</td>
                                <td>${order.customer_email || ''}</td>
                                <td>${order.customer_address || ''}</td>
                                <td>${order.created_at ? new Date(order.created_at).toLocaleString('uk-UA') : ''}</td>
                                <td>
                                    <select class="order-status-select">
                                        <option value="Виконується" ${order.status === 'Виконується' ? 'selected' : ''}>Виконується</option>
                                        <option value="Виконано" ${order.status === 'Виконано' ? 'selected' : ''}>Виконано</option>
                                        <option value="Скасовано" ${order.status === 'Скасовано' ? 'selected' : ''}>Скасовано</option>
                                        <option value="Очікується" ${order.status === 'Очікується' ? 'selected' : ''}>Очікується</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn download-pdf-btn" data-order-id="${order.id}"><i class="fas fa-file-pdf"></i> PDF</button>
                                </td>
                            </tr>
                        `;
                    });

                    // Добавляем обработчик изменения статуса
                    tbody.querySelectorAll('.order-status-select').forEach(select => {
                        select.addEventListener('change', function() {
                            const tr = this.closest('tr');
                            const orderId = tr.getAttribute('data-order-id');
                            const newStatus = this.value;
                            fetch(`/admin/orders/${orderId}/status`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ status: newStatus })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (!data.success) {
                                    alert('Помилка при оновленні статусу!');
                                }
                            })
                            .catch(() => alert('Помилка з\'єднання з сервером!'));
                        });
                    });

                    // Исправленный обработчик для кнопки PDF:
                    document.querySelectorAll('.download-pdf-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const orderId = this.getAttribute('data-order-id');
                            window.open(`/admin/orders/${orderId}/pdf`, '_blank');
                        });
                    });
                }
            });
    }

    // Загрузка заявок на СЕС
    function loadSolarApplications() {
        fetch('/admin/solar-applications')
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#solar-applications-table tbody');
                tbody.innerHTML = '';
                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Немає заявок</td></tr>';
                } else {
                    data.forEach(app => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${app.id}</td>
                                <td>${app.name}</td>
                                <td>${app.phone}</td>
                                <td>${app.email}</td>
                                <td>${app.location}</td>
                                <td>${app.system_config}</td>
                                <td>${app.message}</td>
                                <td>${app.created_at ? new Date(app.created_at).toLocaleString('uk-UA') : ''}</td>
                            </tr>
                        `;
                    });
                }
            });
    }
});
</script>

<!-- Модальное окно для редактирования -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" id="closeEditModal" style="float:right;cursor:pointer;font-size:1.5rem;">&times;</span>
        <h2 id="editModalTitle">Редагувати</h2>
        <form id="edit-form">
            <!-- Динамически вставляемые поля -->
            <div id="edit-product-fields" style="display:none;">
                <div class="form-group">
                    <label for="edit-product-name">Назва</label>
                    <input type="text" id="edit-product-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="edit-product-category">Категорія</label>
                    <select id="edit-product-category" name="category_id" required>
                        <option value="">Виберіть категорію</option>
                        <optgroup label="Категорії пристроїв" class="device-categories">
                            @foreach($deviceCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-product-description">Опис</label>
                    <textarea id="edit-product-description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit-product-price">Ціна (грн)</label>
                    <input type="number" id="edit-product-price" name="price" step="0.01">
                </div>
                <div class="form-group">
                    <label for="edit-product-stock">Наявність</label>
                    <select id="edit-product-stock" name="stock_status">
                        <option value="in_stock">В наявності</option>
                        <option value="out_of_stock">Немає в наявності</option>
                        <option value="preorder">Під замовлення</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-product-brand">Виробник</label>
                    <input type="text" id="edit-product-brand" name="brand">
                </div>
                <div class="form-group">
                    <label for="edit-product-image">Зображення</label>
                    <input type="file" id="edit-product-image" name="image" accept="image/*">
                </div>
            </div>
            <div id="edit-service-fields" style="display:none;">
                <div class="form-group">
                    <label for="edit-service-name">Назва</label>
                    <input type="text" id="edit-service-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="edit-service-category">Категорія</label>
                    <select id="edit-service-category" name="category_id" required>
                        <option value="">Виберіть категорію</option>
                        <optgroup label="Категорії послуг" class="service-categories">
                            @foreach($serviceCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-service-description">Опис</label>
                    <textarea id="edit-service-description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit-service-price">Ціна (грн)</label>
                    <input type="number" id="edit-service-price" name="price" step="0.01">
                </div>
                <div class="form-group">
                    <label for="edit-service-duration">Тривалість (хв)</label>
                    <input type="number" id="edit-service-duration" name="duration_minutes" min="1">
                </div>
                <div class="form-group">
                    <label for="edit-service-image">Зображення</label>
                    <input type="file" id="edit-service-image" name="image" accept="image/*">
                    <div id="edit-service-image-preview"></div>
                </div>
            </div>
            <input type="hidden" id="edit-type" name="type">
            <input type="hidden" id="edit-id" name="id">
            <button type="submit" class="btn" id="edit-save-btn">Зберегти</button>
        </form>
    </div>
</div>
<style>
/* Простые стили для модального окна */
.modal {
    position: fixed; left: 0; top: 0; width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; z-index: 1000;
}
.modal-content {
    background: #fff; padding: 2rem; border-radius: 8px; min-width: 320px; max-width: 90vw; position: relative;
}
.dark-theme .modal-content { background: #222; color: #fff; }
</style>
</body>
</html>
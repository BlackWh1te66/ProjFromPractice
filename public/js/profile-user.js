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
    
    // Завантаження аватара
    const avatarUpload = document.getElementById('avatarUpload');
    const avatarPreview = document.getElementById('avatarPreview');
    const userAvatar = document.getElementById('userAvatar');
    
    const avatarInput = document.getElementById('avatarUpload');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    avatarPreview.src = event.target.result;
                    userAvatar.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Перемикання між розділами
    const menuItems = document.querySelectorAll('.profile-menu a');
    const contentSections = document.querySelectorAll('.content-section');
    
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Видаляємо активний клас у всіх пунктах меню
            menuItems.forEach(i => i.classList.remove('active'));
            // Додаємо активний клас до поточного пункту
            this.classList.add('active');
            
            // Отримуємо ID розділу, який потрібно показати
            const sectionId = this.getAttribute('data-section');
            
            // Приховуємо всі розділи
            contentSections.forEach(section => {
                section.classList.remove('active');
            });
            
            // Показуємо потрібний розділ
            document.getElementById(`${sectionId}-section`).classList.add('active');
        });
    });
    
    // Збереження особистих даних
    const personalForm = document.getElementById('personalForm');
    if (personalForm) {
        personalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Оновлюємо дані в профілі
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            
            document.getElementById('userName').textContent = `${firstName} ${lastName}`;
            document.getElementById('userEmail').textContent = email;
            
            // Відправляємо форму
            this.submit();
        });
    }
    
    // Зміна пароля - ИСПРАВЛЕННАЯ ВЕРСИЯ
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        let isSubmitting = false; // Флаг для предотвращения двойной отправки

        passwordForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Проверяем, не отправляется ли уже запрос
            if (isSubmitting) {
                return;
            }

            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Валидация
            if (newPassword !== confirmPassword) {
                alert('Новий пароль та підтвердження не співпадають!');
                return;
            }

            if (newPassword.length < 6) {
                alert('Пароль повинен містити щонайменше 6 символів!');
                return;
            }

            // Устанавливаем флаг отправки
            isSubmitting = true;
            
            // Показываем индикатор загрузки (опционально)
            const submitBtn = passwordForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Змінюємо...';
            submitBtn.disabled = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                if (!csrfToken) {
                    throw new Error('CSRF токен не найден');
                }

                const response = await fetch('/profile/change-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: currentPassword,
                        new_password: newPassword,
                        new_password_confirmation: confirmPassword
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Пароль успішно змінено!');
                    passwordForm.reset();
                    // Убираем фокус с активного элемента
                    if (document.activeElement) {
                        document.activeElement.blur();
                    }
                } else {
                    // Обрабатываем ошибки валидации
                    if (data.errors) {
                        let errorMessage = '';
                        for (const field in data.errors) {
                            errorMessage += data.errors[field].join('\n') + '\n';
                        }
                        alert('Помилки валідації:\n' + errorMessage);
                    } else {
                        alert(data.message || 'Помилка при зміні пароля!');
                    }
                }
            } catch (error) {
                console.error('Ошибка при смене пароля:', error);
                alert('Помилка мережі при зміні пароля. Спробуйте ще раз.');
            } finally {
                // Сбрасываем состояние кнопки и флаг
                isSubmitting = false;
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
    // Додавання нової адреси
    const addAddressBtn = document.getElementById('addAddressBtn');
    if (addAddressBtn) {
        addAddressBtn.addEventListener('click', function() {
            // Тут буде логіка додавання нової адреси
            alert('Функція додавання нової адреси буде реалізована пізніше');
        });
    }
    
    // --- Отображение избранных товаров в разделе "Обрані товари" ---
    function renderWishlist() {
        const wishlistContainer = document.querySelector('.wishlist');
        if (!wishlistContainer) return;

        // Получаем id избранных товаров из localStorage
        const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
        // Получаем все товары из window.products (загруженные на главной) или из API
        let allProducts = window.products || [];

        // Если window.products нет, попробуем загрузить из API
        function fillWishlist(products) {
            wishlistContainer.innerHTML = '';
            if (!favorites.length) {
                wishlistContainer.innerHTML = '<div style="padding:2rem;text-align:center;color:#888;">У вас ще немає обраних товарів</div>';
                return;
            }
            products.filter(p => favorites.includes(p.id)).forEach(product => {
                const imageUrl = product.image ? product.image : '/img/no-image.png';
                const title = product.title || product.name || '';
                const price = product.price ? product.price + ' грн' : '';
                wishlistContainer.innerHTML += `
                    <div class="wishlist-item">
                        <div class="wishlist-img" style="background-image: url('${imageUrl}');"></div>
                        <div class="wishlist-info">
                            <h4>${title}</h4>
                            <p class="wishlist-price">${price}</p>
                            <div class="wishlist-actions">
                                <button class="btn btn-danger btn-sm remove-wishlist-btn" data-id="${product.id}"><i class="fas fa-trash"></i></button>
                                <button class="btn btn-primary btn-sm"><i class="fas fa-shopping-cart"></i></button>
                            </div>
                        </div>
                    </div>
                `;
            });

            // Обработчик удаления из избранного
            wishlistContainer.querySelectorAll('.remove-wishlist-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.dataset.id);
                    let favs = JSON.parse(localStorage.getItem('favorites') || '[]');
                    favs = favs.filter(fid => fid !== id);
                    localStorage.setItem('favorites', JSON.stringify(favs));
                    renderWishlist();
                });
            });
        }

        if (allProducts.length) {
            fillWishlist(allProducts);
        } else {
            // Если window.products еще не загружен, подгружаем из API
            fetch('/api/products')
                .then(res => res.json())
                .then(products => {
                    window.products = products;
                    fillWishlist(products);
                })
                .catch(error => {
                    console.error('Ошибка загрузки товаров:', error);
                    wishlistContainer.innerHTML = '<div style="padding:2rem;text-align:center;color:#c00;">Помилка завантаження товарів</div>';
                });
        }
    }

    // Показывать wishlist при открытии раздела "Обрані товари"
    const wishlistMenu = document.querySelector('.profile-menu a[data-section="wishlist"]');
    if (wishlistMenu) {
        wishlistMenu.addEventListener('click', function() {
            renderWishlist();
        });
    }

    // --- Показывать заказы при открытии раздела "Історія замовлень" ---
    const ordersMenu = document.querySelector('.profile-menu a[data-section="orders"]');
    if (ordersMenu) {
        ordersMenu.addEventListener('click', function() {
            loadUserOrders();
        });
    }
    // Также отобразить заказы при первой загрузке, если раздел активен
    if (document.getElementById('orders-section')?.classList.contains('active')) {
        loadUserOrders();
    }

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

    // --- Загрузка заказов пользователя ---
    function loadUserOrders() {
        const ordersTable = document.getElementById('ordersTable');
        const ordersLoading = document.getElementById('orders-loading');
        if (!ordersTable) return;
        const tbody = ordersTable.querySelector('tbody');
        if (!tbody) return;

        if (ordersLoading) ordersLoading.style.display = 'block';
        tbody.innerHTML = '';

        fetch('/profile/orders', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            if (ordersLoading) ordersLoading.style.display = 'none';
            if (!Array.isArray(data) || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#888;">У вас ще немає замовлень</td></tr>`;
                return;
            }
            data.forEach(order => {
                tbody.innerHTML += `
                    <tr>
                        <td>#ORD-${order.id}</td>
                        <td>${order.created_at ? new Date(order.created_at).toLocaleDateString('uk-UA') : ''}</td>
                        <td>${order.product_name || order.service_name || ''}</td>
                        <td>${order.product_price ? order.product_price + ' грн' : ''}</td>
                        <td><span class="status">${order.status || ''}</span></td>
                        <td><button type="button" class="btn btn-secondary btn-sm order-details-btn" data-order='${JSON.stringify(order).replace(/'/g, "&apos;")}' >Деталі</button></td>
                    </tr>
                `;
            });

            // Обработчик для кнопок "Деталі"
            tbody.querySelectorAll('.order-details-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let order;
                    try {
                        order = JSON.parse(this.dataset.order.replace(/&apos;/g, "'"));
                    } catch {
                        alert('Помилка при розборі даних замовлення');
                        return;
                    }
                    showOrderDetailsModal(order);
                });
            });
        })
        .catch(error => {
            console.error('Ошибка загрузки заказов:', error);
            if (ordersLoading) ordersLoading.style.display = 'none';
            tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#c00;">Помилка завантаження замовлень</td></tr>`;
        });
    }

    // --- Модальное окно для деталей заказа ---
    function showOrderDetailsModal(order) {
        let modal = document.getElementById('orderDetailsModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'orderDetailsModal';
            modal.className = 'order-details-modal';
            modal.innerHTML = `
                <div class="order-details-modal-content">
                    <span class="order-details-modal-close" style="float:right;cursor:pointer;font-size:1.5rem;">&times;</span>
                    <h3>Деталі замовлення #ORD-${order.id}</h3>
                    <div class="order-details-modal-body"></div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        // Заполнить данными
        const body = modal.querySelector('.order-details-modal-body');
        body.innerHTML = `
            <table class="order-details-table" style="width:100%;margin-bottom:1rem;">
                <tr><td><b>Дата:</b></td><td>${order.created_at ? new Date(order.created_at).toLocaleString('uk-UA') : ''}</td></tr>
                <tr><td><b>Назва:</b></td><td>${order.product_name || order.service_name || ''}</td></tr>
                <tr><td><b>Сума:</b></td><td>${order.product_price ? order.product_price + ' грн' : ''}</td></tr>
                <tr><td><b>Статус:</b></td><td>${order.status || ''}</td></tr>
                <tr><td><b>Ім'я клієнта:</b></td><td>${order.customer_name || ''}</td></tr>
                <tr><td><b>Телефон:</b></td><td>${order.customer_phone || ''}</td></tr>
                <tr><td><b>Email:</b></td><td>${order.customer_email || ''}</td></tr>
                <tr><td><b>Адреса:</b></td><td>${order.customer_address || ''}</td></tr>
            </table>
        `;
        // Показать модалку
        modal.style.display = 'flex';
        // Закрытие
        modal.querySelector('.order-details-modal-close').onclick = function() {
            modal.style.display = 'none';
        };
        modal.onclick = function(e) {
            if (e.target === modal) modal.style.display = 'none';
        };
    }

    // Функция для загрузки заказов (совместимость с inline скриптом)
    window.loadOrders = loadUserOrders;
});
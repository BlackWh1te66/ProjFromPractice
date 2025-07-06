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

    // Set correct icon on load
    const isDark = document.body.classList.contains('dark-theme');
    themeSwitcher.textContent = isDark ? '☀️' : '🌓';

    // --- Вкладки ---
    const tabs = document.querySelectorAll('.admin-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.admin-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(`${tabId}-content`).classList.add('active');
            // Загружаем только нужную таблицу
            if (tabId === 'orders') loadOrders();
            if (tabId === 'solar-applications') loadSolarApplications();
        });
    });

    // --- Загрузка заказов ---
    function loadOrders() {
        fetch('/admin/orders')
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#orders-table tbody');
                if (!tbody) return;
                tbody.innerHTML = '';
                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;">Немає замовлень</td></tr>';
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
                                    <button class="download-pdf-btn" style="padding:0.4rem 1rem;border-radius:4px;background:#28a745;color:#fff;border:none;cursor:pointer;">
                                        Завантажити PDF
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    // Обработчик изменения статуса заказа
                    tbody.querySelectorAll('.order-status-select').forEach(select => {
                        select.addEventListener('change', function() {
                            const orderId = this.closest('tr').dataset.orderId;
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
                                if (data.success) {
                                    loadOrders();
                                } else {
                                    alert('Помилка при оновленні статусу!');
                                }
                            })
                            .catch(() => alert('Помилка з\'єднання з сервером!'));
                        });
                    });

                    // Открытие PDF через сервер TCPDF
                    tbody.querySelectorAll('.download-pdf-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const orderId = this.closest('tr').getAttribute('data-order-id');
                            window.open(`/admin/orders/${orderId}/pdf`, '_blank');
                        });
                    });
                }
            });
    }

    // --- Загрузка заявок на СЕС ---
    function loadSolarApplications() {
        fetch('/admin/solar-applications')
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#solar-applications-table tbody');
                if (!tbody) return;
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
                                <td>${app.email ?? ''}</td>
                                <td>${app.location}</td>
                                <td>${app.system_config ?? ''}</td>
                                <td>${app.message ?? ''}</td>
                                <td>${app.created_at ? new Date(app.created_at).toLocaleString('uk-UA') : ''}</td>
                            </tr>
                        `;
                    });
                }
            });
    }

    // --- Инициализация: только для активной вкладки ---
    if (document.getElementById('orders-content')?.classList.contains('active')) {
        loadOrders();
    }
    if (document.getElementById('solar-applications-content')?.classList.contains('active')) {
        loadSolarApplications();
    }

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

    // Profile button click handler
    const profileBtn = document.getElementById('profileBtn');
    if (profileBtn) {
        profileBtn.addEventListener('click', function() {
            window.location.href = 'admin-profile.html';
        });
    }

    // Product form submission
    document.getElementById('product-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const type = document.getElementById('product-type').value;
        const name = document.getElementById('product-name').value;
        const categoryValue = document.getElementById('product-category').value;
        const description = document.getElementById('product-description').value;

        if (type === 'product') {
            // Для товаров
            let category_id = null;
            if (categoryValue.startsWith('device_')) {
                category_id = categoryValue.replace('device_', '');
            }
            const price = document.getElementById('product-price').value;
            const stock_status = document.getElementById('product-stock').value;
            const brand = document.getElementById('product-brand').value;
            const imageInput = document.getElementById('product-image');
            const formData = new FormData();
            formData.append('name', name);
            formData.append('category_id', category_id);
            formData.append('description', description);
            formData.append('price', price);
            formData.append('stock_status', stock_status);
            formData.append('brand', brand);
            if (imageInput && imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            try {
                const response = await fetch('/admin/products', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        // Не указывайте Content-Type для FormData!
                    },
                    body: formData
                });
                const text = await response.text();
                let data = null;
                try {
                    data = JSON.parse(text);
                } catch {
                    // Если не JSON, возможно HTML
                }
                // Показываем сообщение всегда, если товар добавлен (Laravel возвращает success:true)
                if (data && data.success) {
                    alert('Товар успішно збережено!');
                    this.reset();
                } else if (data && data.message) {
                    alert('Помилка при збереженні товару! ' + data.message);
                } else {
                    // Если не получили JSON, но статус 200, всё равно показываем успех
                    if (response.ok) {
                        alert('Товар успішно збережено!');
                        this.reset();
                    }
                }
            } catch (err) {
                alert('Помилка з\'єднання з сервером!\n' + err);
            }
        } else if (type === 'service') {
            // Для услуг
            let category_id = null;
            if (categoryValue.startsWith('service_')) {
                category_id = parseInt(categoryValue.replace('service_', ''), 10);
            }
            const price = document.getElementById('service-price').value;
            const duration_minutes = document.getElementById('service-duration').value;
            const imageInput = document.getElementById('service-image');
            const formData = new FormData();
            formData.append('name', name);
            formData.append('category_id', category_id);
            formData.append('description', description);
            formData.append('price', price);
            formData.append('duration_minutes', duration_minutes);
            if (imageInput && imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            try {
                const response = await fetch('/admin/services', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch {
                    data = null;
                }
                if (!response.ok) {
                    alert('Помилка сервера: ' + text);
                    return;
                }
                if (data && data.success) {
                    alert('Послугу успішно збережено!');
                    this.reset();
                } else {
                    alert('Помилка при збереженні послуги! ' + (data && data.message ? data.message : ''));
                }
            } catch (err) {
                alert('Помилка з\'єднання з сервером!');
            }
        }
    });

    // Загрузка заявок на СЕС
    function loadSolarApplications() {
        fetch('/admin/solar-applications')
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#solar-applications-table tbody');
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Немає заявок</td></tr>';
                } else {
                    data.forEach(app => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${app.id}</td>
                                <td>${app.name}</td>
                                <td>${app.phone}</td>
                                <td>${app.email ?? ''}</td>
                                <td>${app.location}</td>
                                <td>${app.system_config ?? ''}</td>
                                <td>${app.message ?? ''}</td>
                                <td>${app.created_at ? new Date(app.created_at).toLocaleString('uk-UA') : ''}</td>
                            </tr>
                        `;
                    });
                }
            });
    }

    // Если вкладка "Замовлення" активна по умолчанию, загрузить сразу
    if (document.getElementById('orders-content').classList.contains('active')) {
        loadSolarApplications();
    }

    // Загрузка заказов
    function loadOrders() {
        fetch('/admin/orders')
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#orders-table tbody');
                tbody.innerHTML = '';
                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Немає замовлень</td></tr>';
                } else {
                    data.forEach(order => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${order.id}</td>
                                <td>${order.product_name}</td>
                                <td>${order.product_price || ''}</td>
                                <td>${order.customer_name}</td>
                                <td>${order.customer_phone}</td>
                                <td>${order.customer_email || ''}</td>
                                <td>${order.customer_address || ''}</td>
                                <td>${order.created_at ? new Date(order.created_at).toLocaleString('uk-UA') : ''}</td>
                            </tr>
                        `;
                    });
                }
            });
    }

    function setEditModalRequiredFields(type) {
        // Сброс required у всех полей
        [
            'edit-product-name', 'edit-product-category', 'edit-product-description', 'edit-product-price', 'edit-product-stock',
            'edit-service-name', 'edit-service-category', 'edit-service-description', 'edit-service-price', 'edit-service-duration'
        ].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.required = false;
        });
        // Устанавливаем required только для видимых полей
        if (type === 'product') {
            ['edit-product-name', 'edit-product-category', 'edit-product-description', 'edit-product-price', 'edit-product-stock'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.required = true;
            });
        } else if (type === 'service') {
            ['edit-service-name', 'edit-service-category', 'edit-service-description', 'edit-service-price', 'edit-service-duration'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.required = true;
            });
        }
    }

    // Загрузка при открытии вкладки "Товари та послуги"
    const productsTab = document.querySelector('.admin-tab[data-tab="products"]');
    if (productsTab) {
        productsTab.addEventListener('click', loadProductsAndServices);
    }
    // Загрузка сразу, если вкладка активна
    if (document.getElementById('products-content').classList.contains('active')) {
        loadProductsAndServices();
    }

    // Измените обработчик submit для product-form:
    document.getElementById('product-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const type = document.getElementById('product-type').value;
        const name = document.getElementById('product-name').value;
        const categoryValue = document.getElementById('product-category').value;
        const description = document.getElementById('product-description').value;
        const editId = this.getAttribute('data-edit-id');
        const editType = this.getAttribute('data-edit-type');
        let url = '';
        let method = 'POST';
        if (editId && editType) {
            if (editType === 'product') url = `/admin/products/${editId}`;
            if (editType === 'service') url = `/admin/services/${editId}`;
            method = 'PUT';
        } else {
            if (type === 'product') url = '/admin/products';
            if (type === 'service') url = '/admin/services';
        }
        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name,
                    category_id: categoryValue,
                    description,
                    price: type === 'product' ? document.getElementById('product-price').value : document.getElementById('service-price').value,
                    stock_status: type === 'product' ? document.getElementById('product-stock').value : undefined,
                    duration_minutes: type === 'service' ? document.getElementById('service-duration').value : undefined
                })
            });
            const data = await response.json();
            if (data.success) {
                alert('Збережено успішно!');
                this.reset();
                // loadProductsAndServices(); // Удалить эту строку
            } else {
                alert('Помилка при збереженні! ' + (data.message || ''));
            }
        } catch (err) {
            alert('Помилка з\'єднання з сервером!');
        }
    });

    // Модальное окно
    const editModal = document.getElementById('editModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const editForm = document.getElementById('edit-form');
    const editModalTitle = document.getElementById('editModalTitle');

    // Закрытие модального окна
    closeEditModal.addEventListener('click', () => {
        editModal.style.display = 'none';
        editForm.innerHTML = '';
    });
    window.addEventListener('click', (e) => {
        if (e.target === editModal) {
            editModal.style.display = 'none';
            editForm.innerHTML = '';
        }
    });

    // Обработка отправки формы редактирования
    editForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const type = document.getElementById('edit-type').value;
        const id = document.getElementById('edit-id').value;
        let url = '';
        let body = {};
        if (type === 'product') {
            url = `/admin/products/${id}`;
            body = {
                name: document.getElementById('edit-product-name').value,
                category_id: document.getElementById('edit-product-category').value,
                description: document.getElementById('edit-product-description').value,
                price: document.getElementById('edit-product-price').value,
                stock_status: document.getElementById('edit-product-stock').value,
                // image: document.getElementById('edit-product-image').files[0] // для загрузки файла нужен FormData
            };
        } else if (type === 'service') {
            url = `/admin/services/${id}`;
            body = {
                name: document.getElementById('edit-service-name').value,
                category_id: document.getElementById('edit-service-category').value,
                description: document.getElementById('edit-service-description').value,
                price: document.getElementById('edit-service-price').value,
                duration_minutes: document.getElementById('edit-service-duration').value
            };
        }
        try {
            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(body)
            });
            const data = await response.json();
            if (data.success) {
                alert('Збережено успішно!');
                editModal.style.display = 'none';
                editForm.reset();
                // loadProductsAndServices(); // Удалить эту строку
            } else {
                alert('Помилка при збереженні! ' + (data.message || ''));
            }
        } catch (err) {
            alert('Помилка з\'єднання з сервером!');
        }
    });

    // В обработчике редактирования услуги (edit-btn) добавьте отображение текущей картинки:
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const apiType = row.getAttribute('data-api-type');
            const id = row.getAttribute('data-id');
            const name = row.children[1].textContent;
            const category = row.children[2].textContent;
            const description = row.children[3].textContent;
            const price = row.children[4].textContent;
            let stock = row.children[5]?.textContent;
            if (stock === 'Так') stock = true;
            if (stock === 'Ні') stock = false;
            const duration = row.children[6]?.textContent;

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-type').value = apiType;
            document.getElementById('edit-product-name').value = name;
            document.getElementById('edit-product-category').value = category;
            document.getElementById('edit-product-description').value = description;
            document.getElementById('edit-product-price').value = price;
            document.getElementById('edit-product-stock').checked = stock;
            document.getElementById('edit-service-duration').value = duration || '';

            let formHtml = '';
            if (apiType === 'product') {
                formHtml = `
                    <div class="form-group">
                        <label for="edit-product-image">Зображення</label>
                        <input type="file" id="edit-product-image" name="image" accept="image/*">
                    </div>
                `;
            } else if (apiType === 'service') {
                // Получить текущую картинку из строки таблицы
                const imgCell = row.children[7];
                let imgSrc = '';
                if (imgCell && imgCell.querySelector('img')) {
                    imgSrc = imgCell.querySelector('img').src;
                }
                formHtml += `
                    <div class="form-group">
                        <label for="edit-service-image">Зображення</label>
                        <input type="file" id="edit-service-image" name="image" accept="image/*">
                        <div id="edit-service-image-preview">
                            ${imgSrc ? `<img src="${imgSrc}" alt="img" style="max-width:60px;max-height:60px;">` : ''}
                        </div>
                    </div>
                `;
            }
            editForm.innerHTML = formHtml;
            editModal.style.display = 'block';
        });
    });

    // В обработчике submit для формы редактирования услуги используйте FormData, если выбрано новое изображение:
    document.getElementById('edit-form').onsubmit = async function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const type = document.getElementById('edit-type').value;
        let url = '';
        let method = 'PUT';
        if (type === 'service') {
            url = `/admin/services/${id}`;
            const formData = new FormData();
            formData.append('name', document.getElementById('edit-service-name').value);
            formData.append('category_id', document.getElementById('edit-service-category').value);
            formData.append('description', document.getElementById('edit-service-description').value);
            formData.append('price', document.getElementById('edit-service-price').value);
            formData.append('duration_minutes', document.getElementById('edit-service-duration').value);
            const imageInput = document.getElementById('edit-service-image');
            if (imageInput && imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            try {
                const response = await fetch(url, {
                    method: 'POST', // Для Laravel обновление с файлами через POST + _method=PUT
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: (() => {
                        formData.append('_method', 'PUT');
                        return formData;
                    })()
                });
                const data = await response.json();
                if (data.success) {
                    alert('Збережено успішно!');
                    document.getElementById('editModal').style.display = 'none';
                    location.reload();
                } else {
                    alert('Помилка при збереженні! ' + (data.message || ''));
                }
            } catch (err) {
                alert('Помилка з\'єднання з сервером!');
            }
            return;
        }
        // ...existing code для product...
    };
});
// Поточне замовлення
let currentOrder = null;

// Функція для відображення послуг
function displayServices(servicesToDisplay) {
    const servicesGrid = document.getElementById('servicesGrid');
    
    if (!servicesGrid) {
        console.error('Services grid element not found');
        return;
    }
    
    servicesGrid.innerHTML = '';

    if (servicesToDisplay.length === 0) {
        servicesGrid.innerHTML = `
            <div class="no-results">
                <i class="fas fa-search"></i>
                <p>Послуги за вашим запитом не знайдені</p>
            </div>
        `;
        return;
    }

    servicesToDisplay.forEach(service => {
        const serviceCard = document.createElement('div');
        serviceCard.className = 'service-card';
        serviceCard.dataset.id = service.id;
        serviceCard.dataset.category = service.category;
        serviceCard.dataset.type = service.type;
        serviceCard.dataset.time = service.time;
        serviceCard.dataset.price = service.price;
        serviceCard.dataset.title = service.title.toLowerCase();
        serviceCard.dataset.description = service.description.toLowerCase();

        let badgeHtml = '';
        if (service.badge) {
            badgeHtml = `<span class="service-badge">${service.badge}</span>`;
        }

        let timeText = '';
        switch(service.time) {
            case 'fast':
                timeText = 'Швидкий (до 1 дня)';
                break;
            case 'standard':
                timeText = 'Стандартний (2-3 дні)';
                break;
            case 'long':
                timeText = 'Тривалий (від 3 днів)';
                break;
            default:
                timeText = 'Стандартний (2-3 дні)';
        }

        serviceCard.innerHTML = `
            <div class="service-image" style="background-image: url('${service.image}');">
                ${badgeHtml}
            </div>
            <div class="service-content">
                <h3 class="service-title">${service.title}</h3>
                <p class="service-description">${service.description}</p>
                <div class="service-price">${service.price} грн</div>
                <div class="service-time">
                    <i class="fas fa-clock"></i> ${timeText}
                </div>
                <button class="service-btn" data-id="${service.id}">Замовити послугу</button>
            </div>
        `;

        servicesGrid.appendChild(serviceCard);
    });

    // Додаємо обробники подій для кнопок замовлення
    document.querySelectorAll('.service-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const serviceId = parseInt(this.dataset.id);
            const service = window.services.find(s => s.id === serviceId);
            if (service) {
                openOrderModal(service);
            }
        });
    });
}

// Відкриття модального вікна замовлення
function openOrderModal(service) {
    currentOrder = service;
    const modal = document.getElementById('orderModal');
    const orderInfo = document.getElementById('orderServiceInfo');
    
    if (!modal || !orderInfo) {
        console.error('Modal elements not found');
        return;
    }
    
    // Заповнюємо інформацію про послугу
    let timeText = '';
    switch(service.time) {
        case 'fast':
            timeText = 'Швидкий (до 1 дня)';
            break;
        case 'standard':
            timeText = 'Стандартний (2-3 дні)';
            break;
        case 'long':
            timeText = 'Тривалий (від 3 днів)';
            break;
        default:
            timeText = 'Стандартний (2-3 дні)';
    }
    
    orderInfo.innerHTML = `
        <div class="order-info-item">
            <span class="order-info-label">Послуга:</span>
            <span class="order-info-value">${service.title}</span>
        </div>
        <div class="order-info-item">
            <span class="order-info-label">Ціна:</span>
            <span class="order-info-value">${service.price} грн</span>
        </div>
        <div class="order-info-item">
            <span class="order-info-label">Термін виконання:</span>
            <span class="order-info-value">${timeText}</span>
        </div>
    `;
        
    // Показуємо модальне вікно
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Закриття модального вікна
function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Обробка відправлення форми
function handleOrderSubmit(e) {
    e.preventDefault();

    let name, phone, email, address;
    if (window.authUser) {
        name = window.authUser.first_name || '';
        phone = window.authUser.phone || '';
        email = window.authUser.email || '';
        address = document.getElementById('clientAddress').value;
    } else {
        name = document.getElementById('clientName').value;
        phone = document.getElementById('clientPhone').value;
        email = document.getElementById('clientEmail').value;
        address = document.getElementById('clientAddress').value;
    }

    if (!name.trim() || !phone.trim() || !currentOrder) {
        alert('Будь ласка, заповніть всі обов\'язкові поля!');
        return;
    }

    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    csrfToken = csrfToken ? csrfToken.getAttribute('content') : null;

    fetch('/order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {})
        },
        body: JSON.stringify({
            product_id: currentOrder.id,
            product_name: currentOrder.title,
            product_price: String(currentOrder.price).replace(/[^\d.]/g, ''), // Только число!
            customer_name: name,
            customer_phone: phone,
            customer_email: email,
            customer_address: address
        })
    })
    .then(async res => {
        let data;
        let text;
        try {
            text = await res.text();
            data = JSON.parse(text);
        } catch (e) {
            alert('Помилка при оформленні замовлення!\n' + (text || e));
            return;
        }
        if (data && data.success) {
            alert('Дякуємо за ваше замовлення! Наш менеджер зв\'яжеться з вами для підтвердження.');
            closeOrderModal();
            document.getElementById('orderForm').reset();
        } else {
            alert('Помилка при оформленні замовлення! ' + (data && data.message ? data.message : ''));
        }
    })
    .catch((err) => {
        console.error('Order submission error:', err);
        alert('Помилка при оформленні замовлення!');
    });
}

// Функція для фільтрації послуг
function filterServices() {
    const searchInput = document.getElementById('searchInput');
    const priceRange = document.getElementById('priceRange');
    const maxPriceValue = document.getElementById('maxPriceValue');
    
    if (!searchInput || !priceRange || !maxPriceValue) {
        console.error('Filter elements not found');
        return;
    }
    
    const searchText = searchInput.value.toLowerCase();
    const selectedCategory = document.querySelector('.category-item.active a')?.dataset.category || 'all';
    const maxPrice = parseInt(priceRange.value);

    maxPriceValue.textContent = `${maxPrice}`;

    const filteredServices = window.services.filter(service => {
        // Пошук за текстом
        const matchesSearch = searchText === '' || 
            service.title.toLowerCase().includes(searchText) || 
            service.description.toLowerCase().includes(searchText);
        
        // Фільтр за категорією
        const matchesCategory = selectedCategory === 'all' || String(service.category) === String(selectedCategory);
        
        // Фільтр за ціною
        const matchesPrice = service.price <= maxPrice;
        
        return matchesSearch && matchesCategory && matchesPrice;
    });

    displayServices(filteredServices);
}

// Ініціалізація теми
function initTheme() {
    const themeSwitcher = document.getElementById('themeSwitcher');
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        if (themeSwitcher) themeSwitcher.textContent = '☀️';
    } else {
        document.body.classList.remove('dark-theme');
        if (themeSwitcher) themeSwitcher.textContent = '🌓';
    }
    
    if (themeSwitcher) {
        themeSwitcher.addEventListener('click', function() {
            document.body.classList.toggle('dark-theme');
            const isDark = document.body.classList.contains('dark-theme');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeSwitcher.textContent = isDark ? '☀️' : '🌓';
        });
    }
}

// Ініціалізація сторінки
function initPage() {
    // Ініціалізація теми
    initTheme();

    // Ініціалізація випадаючого меню профілю
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

    console.log('Initializing page...');
    console.log('Available services:', window.services);
    
    // Перевіряємо чи завантажилися послуги
    if (!window.services || window.services.length === 0) {
        console.warn('No services loaded from PHP');
        const servicesGrid = document.getElementById('servicesGrid');
        if (servicesGrid) {
            servicesGrid.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Помилка завантаження послуг</p>
                </div>
            `;
        }
        return;
    }
    
    // Відображаємо всі послуги при завантаженні
    displayServices(window.services);
    
    // Обробник пошуку
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterServices);
    }
    
    // Обробник категорій
    document.querySelectorAll('.category-item a').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.category-item').forEach(el => el.classList.remove('active'));
            this.parentElement.classList.add('active');
            filterServices();
        });
    });
    
    // Обробник повзунка ціни
    const priceRange = document.getElementById('priceRange');
    if (priceRange) {
        priceRange.addEventListener('input', filterServices);
    }
    
    // Обробник закриття модального вікна
    const closeModalBtn = document.getElementById('closeModal');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeOrderModal);
    }
    
    const orderModal = document.getElementById('orderModal');
    if (orderModal) {
        orderModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeOrderModal();
            }
        });
    }

    // Обробник відправлення форми
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', handleOrderSubmit);
    }

    // Автозаповнення форми для авторизованого користувача
    if (window.authUser) {
        if (document.getElementById('clientName')) {
            document.getElementById('clientName').value = window.authUser.first_name || '';
        }
        if (document.getElementById('clientPhone')) {
            document.getElementById('clientPhone').value = window.authUser.phone || '';
        }
        if (document.getElementById('clientEmail')) {
            document.getElementById('clientEmail').value = window.authUser.email || '';
        }
    }
}

// Запускаємо ініціалізацію при завантаженні сторінки
window.addEventListener('DOMContentLoaded', initPage);

// Перевіряємо ще раз після повного завантаження
window.addEventListener('load', function() {
    if (!window.services || window.services.length === 0) {
        console.error('Services still not loaded after page load');
    }
});
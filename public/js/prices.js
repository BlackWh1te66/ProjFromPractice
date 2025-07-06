document.addEventListener('DOMContentLoaded', function() {
    // Перемикач теми
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }

    const themeSwitcher = document.getElementById('themeSwitcher');
    if (themeSwitcher) {
        themeSwitcher.addEventListener('click', function() {
            document.body.classList.toggle('dark-theme');
            const isDark = document.body.classList.contains('dark-theme');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            this.textContent = isDark ? '☀️' : '🌓';
        });

        const isDark = document.body.classList.contains('dark-theme');
        themeSwitcher.textContent = isDark ? '☀️' : '🌓';
    }

    // Функція для відображення товарів
    function displayProducts(productsToDisplay) {
        const productsGrid = document.getElementById('productsGrid');
        productsGrid.innerHTML = '';

        if (productsToDisplay.length === 0) {
            productsGrid.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <p>Товари за вашим запитом не знайдені</p>
                </div>
            `;
            return;
        }

        const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');

        productsToDisplay.forEach(product => {
            const isFavorite = favorites.includes(product.id);
            const likeBtnClass = isFavorite ? 'like-btn liked' : 'like-btn';
            const likeIcon = isFavorite ? 'fas fa-heart' : 'far fa-heart';

            let badgeHtml = '';
            if (product.badge) {
                badgeHtml = `<span class="product-badge">${product.badge}</span>`;
            }

            let oldPriceHtml = '';
            if (product.oldPrice) {
                oldPriceHtml = `<span class="product-old-price">${product.oldPrice} грн</span>`;
            }

            let imageUrl = '/img/no-image.png';
            if (product.image && typeof product.image === 'string') {
                if (product.image.includes('storage/products')) {
                    imageUrl = product.image.startsWith('/') ? product.image : '/' + product.image;
                }
            }

            const title = product.title || product.name || '';
            const description = product.description || '';
            const price = product.price ? product.price + ' грн' : '';

            const stock = product.stock || product.stock_status || '';
            let stockText = '';
            let stockClass = '';
            let canOrder = true;
            if (stock === 'in-stock' || stock === 'in_stock') {
                stockText = 'В наявності';
                stockClass = 'in-stock';
                canOrder = true;
            } else if (stock === 'preorder') {
                stockText = 'Під замовлення';
                stockClass = 'preorder';
                canOrder = true;
            } else {
                stockText = 'Немає в наявності';
                stockClass = 'out-stock';
                canOrder = false;
            }

            const orderBtnAttrs = canOrder
                ? ''
                : 'disabled style="opacity:0.5;pointer-events:none;cursor:not-allowed;"';

            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            productCard.dataset.id = product.id;
            productCard.dataset.category = product.category_id || '';
            productCard.dataset.brand = product.brand || '';
            productCard.dataset.stock = stock;
            productCard.dataset.price = product.price;

            productCard.innerHTML = `
                <div class="product-image" style="background-image: url('${imageUrl}');">
                    ${badgeHtml}
                    <button class="${likeBtnClass}" data-id="${product.id}">
                        <i class="${likeIcon}"></i>
                    </button>
                </div>
                <div class="product-content">
                    <h3 class="product-title">${title}</h3>
                    <p class="product-description">${description}</p>
                    <div class="product-price">${price} ${oldPriceHtml}</div>
                    <div class="product-stock ${stockClass}">
                        <i class="fas ${stockClass === 'in-stock' ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                        ${stockText}
                    </div>
                    <button class="product-btn order-btn" data-id="${product.id}" ${orderBtnAttrs}>Замовити</button>
                </div>
            `;

            productsGrid.appendChild(productCard);
        });

        // Обробник для лайка
        document.querySelectorAll('.like-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const productId = parseInt(this.dataset.id);
                let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                const isFav = favorites.includes(productId);
                if (isFav) {
                    favorites = favorites.filter(id => id !== productId);
                } else {
                    favorites.push(productId);
                }
                localStorage.setItem('favorites', JSON.stringify(favorites));
                displayProducts(productsToDisplay);
            });
        });

        // Обробник для кнопок замовлення
        document.querySelectorAll('.order-btn').forEach(btn => {
            if (btn.disabled) return;
            btn.addEventListener('click', function() {
                const productId = parseInt(this.dataset.id);
                const product = window.products?.find(p => p.id === productId) || 
                               productsToDisplay.find(p => p.id === productId);
                if (product) {
                    openOrderModal(product);
                }
            });
        });
    }

    // Відкриття модального вікна
    function openOrderModal(product) {
        const modal = document.getElementById('orderModal');
        const modalTitle = document.getElementById('modalProductTitle');
        const modalPrice = document.getElementById('modalProductPrice');
        const modalImage = document.getElementById('modalProductImage');
        const modalStock = document.getElementById('modalProductStock');
        
        modalTitle.textContent = product.title || product.name || '';

        if (product.oldPrice) {
            modalPrice.innerHTML = `<span style="color: #dc3545; font-size: 1.3rem;">${product.price} грн</span> 
                                    <span style="text-decoration: line-through; color: #999; font-size: 1rem;">${product.oldPrice} грн</span>`;
        } else {
            modalPrice.textContent = `${product.price} грн`;
        }
        
        modalImage.style.backgroundImage = `url('${product.image || '/img/no-image.png'}')`;
        modalStock.textContent = product.stock === 'in-stock' ? 'В наявності' : 'Під замовлення (2-3 дні)';
        
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Закриття модального вікна
    function closeOrderModal() {
        const modal = document.getElementById('orderModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Фільтрація товарів
    function filterProducts() {
        const searchText = document.getElementById('searchInput').value.toLowerCase();
        const selectedCategory = document.querySelector('.category-item.active a')?.dataset.category || 'all';
        const selectedBrands = Array.from(document.querySelectorAll('.filter-option input[data-brand]:checked')).map(el => el.dataset.brand);
        const selectedStock = Array.from(document.querySelectorAll('.filter-option input[data-stock]:checked')).map(el => el.dataset.stock);
        const maxPrice = parseInt(document.getElementById('priceRange').value);

        document.getElementById('maxPriceValue').textContent = `${maxPrice}`;

        let productsToFilter = window.products || [];

        const filteredProducts = productsToFilter.filter(product => {
            const matchesSearch = searchText === '' ||
                (product.title && product.title.toLowerCase().includes(searchText)) ||
                (product.name && product.name.toLowerCase().includes(searchText)) ||
                (product.description && product.description.toLowerCase().includes(searchText));

            const matchesCategory = selectedCategory === 'all' ||
                String(product.category_id) === String(selectedCategory);

            const matchesBrand = selectedBrands.length === 0 ||
                (product.brand && selectedBrands.includes(product.brand));

            let stockValue = product.stock || product.stock_status || '';
            stockValue = String(stockValue).toLowerCase();
            const matchesStock = selectedStock.length === 0 ||
                selectedStock.includes(stockValue);

            let price = 0;
            if (typeof product.price === 'string') {
                price = parseFloat(product.price.replace(/[^\d.]/g, '')) || 0;
            } else {
                price = Number(product.price) || 0;
            }
            const matchesPrice = price <= maxPrice;

            return matchesSearch && matchesCategory && matchesBrand && matchesStock && matchesPrice;
        });

        displayProducts(filteredProducts);
    }

    // Ініціалізація сторінки
    function initPage() {
        // Обробник пошуку
        document.getElementById('searchInput').addEventListener('input', filterProducts);
        
        // Обробник категорій
        document.querySelectorAll('.category-item a').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.category-item').forEach(el => el.classList.remove('active'));
                this.parentElement.classList.add('active');
                filterProducts();
            });
        });
        
        // Обробник фільтрів брендів
        document.querySelectorAll('.filter-option input[data-brand]').forEach(checkbox => {
            checkbox.addEventListener('change', filterProducts);
        });
        
        // Обробник фільтрів наявності
        document.querySelectorAll('.filter-option input[data-stock]').forEach(checkbox => {
            checkbox.addEventListener('change', filterProducts);
        });
        
        // Обробник повзунка ціни
        document.getElementById('priceRange').addEventListener('input', filterProducts);
        
        // Обробник закриття модального вікна
        document.querySelector('.close-modal').addEventListener('click', closeOrderModal);
        
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('orderModal');
            if (event.target === modal) {
                closeOrderModal();
            }
        });

        // Обробник відправки форми замовлення
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let name, phone, email, address;
            if (window.authUser) {
                name = window.authUser.first_name || '';
                phone = window.authUser.phone || '';
                email = window.authUser.email || '';
                address = document.getElementById('orderAddress').value;
            } else {
                name = document.getElementById('orderName').value;
                phone = document.getElementById('orderPhone').value;
                email = document.getElementById('orderEmail').value;
                address = document.getElementById('orderAddress').value;
            }

            const modalTitle = document.getElementById('modalProductTitle').textContent.trim();
            let modalPrice = '';
            const modalPriceElem = document.getElementById('modalProductPrice');
            if (modalPriceElem) {
                if (modalPriceElem.querySelector('span')) {
                    modalPrice = modalPriceElem.querySelector('span').textContent.replace(/[^\d.]/g, '');
                } else {
                    modalPrice = modalPriceElem.textContent.replace(/[^\d.]/g, '');
                }
            }

            if (!name.trim() || !phone.trim()) {
                alert('Будь ласка, заповніть всі обов\'язкові поля!');
                return;
            }

            fetch('/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    customer_name: name,
                    customer_phone: phone,
                    customer_email: email,
                    customer_address: address,
                    product_name: modalTitle,
                    product_price: modalPrice
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Дякуємо за ваше замовлення! Наш менеджер зв\'яжеться з вами для підтвердження.');
                    closeOrderModal();
                    document.getElementById('orderForm').reset();
                } else {
                    alert('Помилка при оформленні замовлення! ' + (data.message || ''));
                }
            })
            .catch(err => {
                console.error('Помилка:', err);
                alert('Помилка при оформленні замовлення!');
            });
        });
    }

    // Завантаження товарів
    fetch('/api/products')
        .then(res => res.json())
        .then(products => {
            window.products = products;
            initPage();
            filterProducts();
        })
        .catch(err => {
            console.error('Помилка завантаження товарів:', err);
            initPage();
        });
});

document.addEventListener('DOMContentLoaded', function() {
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
});

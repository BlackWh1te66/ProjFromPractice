document.addEventListener('DOMContentLoaded', function() {
    // Тема
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

    // Калькулятор
    if (typeof updatePowerValue === 'function') updatePowerValue();
    if (typeof calculateCost === 'function') calculateCost();

    // Обработчик формы калькулятора (если есть)
    const solarForm = document.getElementById('solarForm');
    if (solarForm) {
        solarForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Дякуємо за вашу заявку! Наш менеджер зв\'яжеться з вами найближчим часом для уточнення деталей.');
            this.reset();
        });
    }

    // Обработчик формы заявки
    const solarApplication = document.getElementById('solar-application');
    if (solarApplication) {
        solarApplication.addEventListener('submit', async function(e) {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const email = document.getElementById('email').value;
            const location = document.getElementById('location').value;
            const system_config = document.getElementById('system-config').value;
            const message = document.getElementById('message').value;

            try {
                const response = await fetch('/solar-application', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ name, phone, email, location, system_config, message })
                });
                const data = await response.json();
                if (data.success) {
                    alert('Дякуємо за вашу заявку! Наш менеджер зв\'яжеться з вами для уточнення деталей протягом 1-2 робочих днів.');
                    this.reset();
                } else {
                    alert('Сталася помилка. Спробуйте ще раз.');
                }
            } catch (err) {
                alert('Помилка з\'єднання з сервером.');
            }
        });
    }
});

// Калькулятор вартості СЕС
function updatePowerValue() {
    const powerSlider = document.getElementById('power');
    const powerValue = document.getElementById('power-value');
    powerValue.textContent = powerSlider.value + ' кВт';
}

function calculateCost() {
    const systemType = document.getElementById('system-type').value;
    const power = parseInt(document.getElementById('power').value);
    const roofType = document.getElementById('roof-type').value;
    const battery = document.getElementById('battery').value;
    const brand = document.getElementById('brand').value;
    
    // Базові ціни (умовні)
    let basePricePerKw = 1000;
    
    // Коефіцієнти для типів систем
    if (systemType === 'hybrid') basePricePerKw *= 1.3;
    if (systemType === 'offgrid') basePricePerKw *= 1.5;
    
    // Коефіцієнти для типів покрівлі
    if (roofType === 'tile') basePricePerKw *= 1.1;
    if (roofType === 'flat') basePricePerKw *= 1.15;
    if (roofType === 'ground') basePricePerKw *= 1.2;
    
    // Коефіцієнти для акумуляторів
    if (battery === 'small') basePricePerKw += 200;
    if (battery === 'medium') basePricePerKw += 400;
    if (battery === 'large') basePricePerKw += 600;
    
    // Коефіцієнти для брендів
    if (brand === 'premium') basePricePerKw *= 1.4;
    if (brand === 'budget') basePricePerKw *= 0.9;
    
    // Розрахунок загальної вартості
    const totalCost = power * basePricePerKw;
    
    // Оновлення результатів
    document.getElementById('total-cost').textContent = '$' + totalCost.toLocaleString();
    
    // Розрахунок терміну окупності
    const paybackYears = Math.round(8 - (power / 10));
    document.getElementById('payback').textContent = paybackYears + '-' + (paybackYears + 2) + ' років';
    
    // Розрахунок економії
    const yearlySavings = power * 200;
    document.getElementById('savings').textContent = '$' + (yearlySavings - 100) + '-$' + (yearlySavings + 200);
    
    // Оновлення конфігурації у формі
    let configText = '';
    if (systemType === 'grid') configText = 'Мережева СЕС';
    if (systemType === 'hybrid') configText = 'Гібридна СЕС';
    if (systemType === 'offgrid') configText = 'Автономна СЕС';
    
    configText += ', ' + power + ' кВт';
    
    if (roofType === 'metal') configText += ', металева покрівль';
    if (roofType === 'tile') configText += ', черепиця';
    if (roofType === 'flat') configText += ', плоска покрівль';
    if (roofType === 'ground') configText += ', грунтове кріплення';
    
    if (brand === 'premium') configText += ', Premium бренд';
    if (brand === 'standard') configText += ', Standard бренд';
    if (brand === 'budget') configText += ', Budget бренд';
    
    document.getElementById('system-config').value = configText;
}

// Ініціалізація калькулятора
updatePowerValue();
calculateCost();
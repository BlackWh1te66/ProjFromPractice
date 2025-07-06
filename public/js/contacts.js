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

    // Обробка форми зворотного зв'язку
    const feedbackForm = document.getElementById('feedbackForm');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0,0,0,0.7)';
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
            modal.style.zIndex = '1000';
            
            const modalContent = document.createElement('div');
            modalContent.style.backgroundColor = document.body.classList.contains('dark-theme') ? '#2a2a2a' : '#fff';
            modalContent.style.color = document.body.classList.contains('dark-theme') ? '#e0e0e0' : '#333';
            modalContent.style.padding = '1.5rem';
            modalContent.style.borderRadius = '8px';
            modalContent.style.maxWidth = '90%';
            modalContent.style.textAlign = 'center';
            
            modalContent.innerHTML = `
                <h3 style="margin-bottom: 1rem;">Дякуємо за ваше повідомлення!</h3>
                <p style="margin-bottom: 1.5rem;">Ми зв'яжемося з вами найближчим часом.</p>
                <button style="
                    background: ${document.body.classList.contains('dark-theme') ? '#4d9eff' : '#0056b3'};
                    color: white;
                    border: none;
                    padding: 0.5rem 1rem;
                    border-radius: 4px;
                    cursor: pointer;
                    width: 100%;
                ">OK</button>
            `;
            
            modal.appendChild(modalContent);
            document.body.appendChild(modal);
            
            modalContent.querySelector('button').addEventListener('click', function() {
                document.body.removeChild(modal);
                feedbackForm.submit();
            });
        });
    }

    // Випадаюче меню профілю
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
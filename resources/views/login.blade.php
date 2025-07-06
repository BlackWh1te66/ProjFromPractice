<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Слава-сервіс | Вхід/Реєстрація</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    
    <div class="container">
        <div class="header">
            <a href="{{ url('/') }}" id="homeLink">Головна</a>
            <button class="theme-switcher" id="themeSwitcher">🌓</button>
        </div>
        
        <div class="form-container active" id="loginFormContainer">
            <h2>Вхід</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Логін:</label>
                    <input type="text" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" required>
                </div>
                <button type="submit" class="btn">Увійти</button>
                <div id="loginErrorMessage" class="error-message"></div>
                <div class="link">
                    <a href="#" id="showRegister">Зареєструватися</a>
                </div>
            </form>
        </div>
        
        <div class="form-container" id="registerFormContainer">
            <h2>Реєстрація</h2>
            <form id="registerForm">
                <div class="form-group">
                    <label for="regUsername">Логін:</label>
                    <input type="text" id="regUsername" required>
                </div>
                <div class="form-group">
                    <label for="regFirstName">Ім'я:</label>
                    <input type="text" id="regFirstName" required>
                </div>
                <div class="form-group">
                    <label for="regLastName">Прізвище:</label>
                    <input type="text" id="regLastName" required>
                </div>
                <div class="form-group">
                    <label for="regBirthday">Дата народження:</label>
                    <input type="date" id="regBirthday" required>
                </div>
                <div class="form-group">
                    <label for="regEmail">Email:</label>
                    <input type="email" id="regEmail" required>
                </div>
                <div class="form-group">
                    <label for="regPhone">Номер телефону:</label>
                    <input type="tel" id="regPhone" required>
                </div>
                <div class="form-group">
                    <label for="regPassword">Пароль:</label>
                    <input type="password" id="regPassword" required>
                </div>
                <button type="submit" class="btn">Зареєструватися</button>
                <div id="registerErrorMessage" class="error-message"></div>
                <div class="link">
                    <a href="#" id="showLogin">Увійти</a>
                </div>
                <div class="link">
                    <a href="#">Забули пароль?</a>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
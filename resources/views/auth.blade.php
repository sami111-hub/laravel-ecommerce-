<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول / التسجيل</title>
    <style>
        body {
            background: #F9F9F9;
            min-height: 100vh;
            margin: 0;
            font-family: 'Tajawal', Arial, sans-serif;
            color: #222;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            background: #fff;
            width: 400px;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 32px 28px 24px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .auth-logo {
            width: 80px;
            margin-bottom: 18px;
        }
        .auth-title {
            font-size: 1.5rem;
            margin-bottom: 18px;
            color: #2BB673;
            font-weight: bold;
        }
        .auth-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .auth-input {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 12px 14px;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            outline: none;
            transition: border 0.2s;
        }
        .auth-input:focus {
            border: 1.5px solid #2BB673;
        }
        .auth-btn {
            background: #2BB673;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px 0;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .auth-btn:hover {
            background: #249e60;
        }
        .auth-small {
            color: #666666;
            font-size: 0.95rem;
            text-align: center;
            margin-top: 10px;
        }
        .switch-link {
            color: #2BB673;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <img src="/images/logo.png" alt="شعار المتجر" class="auth-logo">
        <div class="auth-title">تسجيل الدخول</div>
        <form class="auth-form" method="POST" action="/login">
            <input type="email" name="email" class="auth-input" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" class="auth-input" placeholder="كلمة المرور" required>
            <button type="submit" class="auth-btn">دخول</button>
        </form>
        <div class="auth-small">
            ليس لديك حساب؟ <span class="switch-link" onclick="showRegister()">سجل الآن</span>
        </div>
        <div class="auth-title" style="display:none;" id="register-title">التسجيل</div>
        <form class="auth-form" method="POST" action="/register" style="display:none;" id="register-form">
            <input type="text" name="name" class="auth-input" placeholder="الاسم الكامل" required>
            <input type="email" name="email" class="auth-input" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" class="auth-input" placeholder="كلمة المرور" required>
            <input type="password" name="password_confirmation" class="auth-input" placeholder="تأكيد كلمة المرور" required>
            <button type="submit" class="auth-btn">تسجيل</button>
        </form>
        <div class="auth-small" style="display:none;" id="login-switch">
            لديك حساب؟ <span class="switch-link" onclick="showLogin()">تسجيل الدخول</span>
        </div>
    </div>
    <script>
        function showRegister() {
            document.querySelector('.auth-title').style.display = 'none';
            document.querySelector('.auth-form').style.display = 'none';
            document.getElementById('register-title').style.display = 'block';
            document.getElementById('register-form').style.display = 'flex';
            document.querySelector('.auth-small').style.display = 'none';
            document.getElementById('login-switch').style.display = 'block';
        }
        function showLogin() {
            document.querySelector('.auth-title').style.display = 'block';
            document.querySelector('.auth-form').style.display = 'flex';
            document.getElementById('register-title').style.display = 'none';
            document.getElementById('register-form').style.display = 'none';
            document.querySelector('.auth-small').style.display = 'block';
            document.getElementById('login-switch').style.display = 'none';
        }
    </script>
</body>
</html>

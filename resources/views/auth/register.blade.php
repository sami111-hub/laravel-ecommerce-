@extends('layout')

@section('content')
<div class="auth-page">
    <div class="auth-box">
        <div class="brand">
            <img src="/images/logo.png" alt="شعار المتجر" style="height:56px;" />
        </div>
        <h2 class="text-center mb-4">إنشاء حساب جديد</h2>
        <p class="text-center text-muted mb-4">انضم إلينا واستمتع بتجربة تسوق مميزة</p>
        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">
                    <i class="fas fa-user me-1"></i> الاسم الكامل
                </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="أدخل اسمك الكامل" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-1"></i> البريد الإلكتروني
                </label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-1"></i> كلمة المرور
                </label>
                <div class="password-wrapper">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="أدخل كلمة مرور قوية" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength mt-2" id="passwordStrength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <span class="strength-text" id="strengthText">قوة كلمة المرور</span>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">
                    <i class="fas fa-lock me-1"></i> تأكيد كلمة المرور
                </label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="أعد إدخال كلمة المرور" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-match mt-2" id="passwordMatch"></div>
            </div>
            <button type="submit" class="btn btn-primary-burnt w-100">
                <i class="fas fa-user-plus me-2"></i> إنشاء الحساب
            </button>
        </form>
        
        {{-- Google Sign-In --}}
        <div class="text-center my-3">
            <div class="divider-text">
                <span>أو</span>
            </div>
        </div>
        
        <a href="{{ route('auth.google') }}" class="btn btn-outline-google w-100 mb-3">
            <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" style="margin-left: 8px;">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                <path fill="none" d="M0 0h48v48H0z"/>
            </svg>
            التسجيل عبر Google
        </a>
        
        <div class="text-center mt-3 auth-small">
            <p>لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="auth-link">سجل الدخول</a></p>
        </div>
    </div>
</div>

<style>
/* ============================================
   تصميم صفحة التسجيل الاحترافي - البني الحارق
   ============================================ */

.auth-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #fef7f0 0%, #fdf5ed 50%, #fcf0e3 100%);
    padding: 20px;
}

.auth-box {
    background: white;
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 20px 60px rgba(139, 69, 19, 0.15);
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.auth-box h2 {
    color: #8B4513;
    font-weight: 700;
    margin-bottom: 8px;
}

.auth-box .text-muted {
    color: #a67c52 !important;
    font-size: 14px;
}

.form-label {
    color: #5D3A1A;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.form-label i {
    color: #CD853F;
}

.form-control {
    border: 2px solid #e8ddd4;
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #fefcfa;
}

.form-control:focus {
    border-color: #CD853F;
    box-shadow: 0 0 0 4px rgba(205, 133, 63, 0.15);
    background: white;
}

.form-control::placeholder {
    color: #c4a882;
}

/* Password Toggle */
.password-wrapper {
    position: relative;
}

.password-wrapper .form-control {
    padding-left: 50px;
}

.toggle-password {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #a67c52;
    cursor: pointer;
    padding: 8px;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.toggle-password:hover {
    color: #8B4513;
    background: rgba(139, 69, 19, 0.1);
}

.toggle-password.active {
    color: #CD853F;
}

/* Password Strength */
.password-strength {
    display: flex;
    align-items: center;
    gap: 10px;
}

.strength-bar {
    flex: 1;
    height: 6px;
    background: #e8ddd4;
    border-radius: 3px;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    width: 0;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.strength-fill.weak { width: 33%; background: #ef4444; }
.strength-fill.medium { width: 66%; background: #f59e0b; }
.strength-fill.strong { width: 100%; background: #10b981; }

.strength-text {
    font-size: 12px;
    color: #a67c52;
    min-width: 100px;
}

/* Password Match */
.password-match {
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.password-match.match {
    color: #10b981;
}

.password-match.no-match {
    color: #ef4444;
}

/* Primary Button - Burnt Orange */
.btn-primary-burnt {
    background: linear-gradient(135deg, #CD853F 0%, #8B4513 100%);
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(139, 69, 19, 0.35);
    position: relative;
    overflow: hidden;
}

.btn-primary-burnt::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.btn-primary-burnt:hover::before {
    left: 100%;
}

.btn-primary-burnt:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(139, 69, 19, 0.45);
    background: linear-gradient(135deg, #D4915C 0%, #9B5A23 100%);
}

.btn-primary-burnt:active {
    transform: translateY(-1px);
}

/* Divider */
.divider-text {
    position: relative;
    text-align: center;
    margin: 24px 0;
}

.divider-text::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, #e8ddd4, transparent);
    z-index: 0;
}

.divider-text span {
    background: white;
    padding: 0 20px;
    position: relative;
    z-index: 1;
    color: #a67c52;
    font-size: 14px;
    font-weight: 500;
}

/* Google Button */
.btn-outline-google {
    border: 2px solid #e8ddd4;
    color: #5D3A1A;
    background: white;
    transition: all 0.3s ease;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-outline-google:hover {
    background: #fef7f0;
    border-color: #CD853F;
    color: #8B4513;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(139, 69, 19, 0.15);
}

/* Auth Link */
.auth-link {
    color: #CD853F;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.auth-link:hover {
    color: #8B4513;
    text-decoration: underline;
}

.auth-small p {
    color: #a67c52;
}

/* Brand Animation */
.brand {
    text-align: center;
    margin-bottom: 24px;
}

.brand img {
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

/* Responsive */
@media (max-width: 480px) {
    .auth-box {
        padding: 30px 24px;
        margin: 10px;
    }
}
</style>

<script>
// Toggle Password Visibility
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        button.classList.add('active');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        button.classList.remove('active');
    }
}

// Password Strength Checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    strengthFill.className = 'strength-fill';
    
    if (password.length === 0) {
        strengthFill.style.width = '0';
        strengthText.textContent = 'قوة كلمة المرور';
    } else if (strength <= 1) {
        strengthFill.classList.add('weak');
        strengthText.textContent = 'ضعيفة 😟';
    } else if (strength <= 2) {
        strengthFill.classList.add('medium');
        strengthText.textContent = 'متوسطة 😐';
    } else {
        strengthFill.classList.add('strong');
        strengthText.textContent = 'قوية 💪';
    }
    
    checkPasswordMatch();
});

// Password Match Checker
document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    const matchDiv = document.getElementById('passwordMatch');
    
    if (confirmation.length === 0) {
        matchDiv.innerHTML = '';
        matchDiv.className = 'password-match';
    } else if (password === confirmation) {
        matchDiv.innerHTML = '<i class="fas fa-check-circle"></i> كلمات المرور متطابقة';
        matchDiv.className = 'password-match match';
    } else {
        matchDiv.innerHTML = '<i class="fas fa-times-circle"></i> كلمات المرور غير متطابقة';
        matchDiv.className = 'password-match no-match';
    }
}
</script>

@endsection




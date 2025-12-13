<header class="main-header">
    <div class="header-content">
        <div class="header-left">
            <img src="/images/logo.png" alt="شعار المتجر" class="header-logo">
        </div>
        <div class="header-center">
            <form class="search-form">
                <input type="text" class="search-input" placeholder="ابحث عن منتج...">
                <button type="submit" class="search-btn">🔍</button>
            </form>
        </div>
        <div class="header-right">
            <a href="/login" class="header-icon" title="تسجيل الدخول">👤</a>
            <a href="/cart" class="header-icon" title="السلة">🛒</a>
        </div>
    </div>
</header>
<nav class="main-navbar">
    <button class="nav-btn">الخضروات</button>
    <button class="nav-btn">الفواكه</button>
    <button class="nav-btn">العروض</button>
    <button class="nav-btn">منتجات الألبان</button>
    <button class="nav-btn">المشروبات</button>
</nav>

<style>
body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
.main-header {
    width: 100%;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    padding: 0 0 8px 0;
}
.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 12px 18px 0 18px;
}
.header-left {
    flex: 1;
    display: flex;
    align-items: center;
}
.header-logo {
    width: 60px;
    height: auto;
}
.header-center {
    flex: 2;
    display: flex;
    justify-content: center;
}
.search-form {
    width: 45vw;
    max-width: 480px;
    min-width: 180px;
    display: flex;
    background: #f9f9f9;
    border-radius: 24px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.search-input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 10px 16px;
    font-size: 1rem;
    outline: none;
    color: #444;
}
.search-btn {
    background: #2BB673;
    color: #fff;
    border: none;
    padding: 0 18px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.2s;
}
.search-btn:hover {
    background: #249e60;
}
.header-right {
    flex: 1;
    display: flex;
    justify-content: flex-end;
    gap: 18px;
}
.header-icon {
    font-size: 1.5rem;
    color: #444444;
    text-decoration: none;
    transition: color 0.2s;
}
.header-icon:hover {
    color: #2BB673;
}
.main-navbar {
    width: 100%;
    background: #fff;
    display: flex;
    justify-content: center;
    gap: 18px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    padding: 8px 0 8px 0;
}
.nav-btn {
    background: none;
    border: none;
    color: #444444;
    font-size: 15px;
    font-family: 'Segoe UI', 'Tajawal', Arial, sans-serif;
    padding: 8px 18px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.nav-btn:hover {
    background: #eafaf3;
    color: #2BB673;
}
@media (max-width: 900px) {
    .header-content {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }
    .header-center {
        justify-content: stretch;
    }
    .search-form {
        width: 100%;
        max-width: none;
    }
}
@media (max-width: 600px) {
    .header-content {
        padding: 8px 6px 0 6px;
    }
    .main-navbar {
        gap: 6px;
    }
    .nav-btn {
        padding: 6px 8px;
        font-size: 13px;
    }
    .header-logo {
        width: 40px;
    }
}
</style>

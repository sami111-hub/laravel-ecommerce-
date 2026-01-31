<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Role;
use App\Models\Permission;

class FullApplicationTest extends TestCase
{
    /**
     * اختبار الصفحة الرئيسية
     */
    public function test_home_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * اختبار صفحة المنتجات
     */
    public function test_products_page_loads(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    /**
     * اختبار صفحة العروض
     */
    public function test_offers_page_loads(): void
    {
        $response = $this->get('/offers');
        $response->assertStatus(200);
    }

    /**
     * اختبار صفحة التصنيفات
     */
    public function test_categories_page_loads(): void
    {
        $response = $this->get('/categories');
        $response->assertStatus(200);
    }

    /**
     * اختبار صفحة تسجيل الدخول
     */
    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * اختبار صفحة التسجيل
     */
    public function test_register_page_loads(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * اختبار الصفحات الثابتة
     */
    public function test_static_pages_load(): void
    {
        $pages = ['/faq', '/return-policy', '/terms', '/privacy', '/about', '/contact'];
        
        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200, "Failed to load page: {$page}");
        }
    }

    /**
     * اختبار البحث في المنتجات
     */
    public function test_product_search_works(): void
    {
        $response = $this->get('/products?search=phone');
        $response->assertStatus(200);
    }

    /**
     * اختبار صفحة منتج واحد
     */
    public function test_single_product_page_loads(): void
    {
        $product = Product::first();
        if ($product) {
            $response = $this->get("/products/{$product->id}");
            $response->assertStatus(200);
        } else {
            $this->markTestSkipped('No products in database');
        }
    }

    /**
     * اختبار صفحة تصنيف واحد
     */
    public function test_single_category_page_loads(): void
    {
        $category = Category::first();
        if ($category) {
            $response = $this->get("/category/{$category->id}");
            $response->assertStatus(200);
        } else {
            $this->markTestSkipped('No categories in database');
        }
    }

    /**
     * اختبار أن المستخدم يجب أن يكون مسجل للوصول لسلة التسوق
     */
    public function test_cart_requires_authentication(): void
    {
        $response = $this->get('/cart');
        $response->assertRedirect('/login');
    }

    /**
     * اختبار أن المستخدم يجب أن يكون مسجل للوصول للمفضلة
     */
    public function test_wishlist_requires_authentication(): void
    {
        $response = $this->get('/wishlist');
        $response->assertRedirect('/login');
    }

    /**
     * اختبار أن المستخدم يجب أن يكون مسجل للوصول للطلبات
     */
    public function test_orders_requires_authentication(): void
    {
        $response = $this->get('/orders');
        $response->assertRedirect('/login');
    }

    /**
     * اختبار أن لوحة التحكم تتطلب تسجيل دخول
     */
    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    /**
     * اختبار عرض سلة التسوق للمستخدم المسجل
     */
    public function test_authenticated_user_can_view_cart(): void
    {
        $user = User::first();
        if ($user) {
            $response = $this->actingAs($user)->get('/cart');
            $response->assertStatus(200);
        } else {
            $this->markTestSkipped('No users in database');
        }
    }

    /**
     * اختبار عرض المفضلة للمستخدم المسجل
     */
    public function test_authenticated_user_can_view_wishlist(): void
    {
        $user = User::first();
        if ($user) {
            $response = $this->actingAs($user)->get('/wishlist');
            $response->assertStatus(200);
        } else {
            $this->markTestSkipped('No users in database');
        }
    }

    /**
     * اختبار عرض الطلبات للمستخدم المسجل
     */
    public function test_authenticated_user_can_view_orders(): void
    {
        $user = User::first();
        if ($user) {
            $response = $this->actingAs($user)->get('/orders');
            $response->assertStatus(200);
        } else {
            $this->markTestSkipped('No users in database');
        }
    }

    /**
     * اختبار إضافة منتج للسلة
     */
    public function test_authenticated_user_can_add_to_cart(): void
    {
        $user = User::first();
        $product = Product::where('stock', '>', 0)->first();
        
        if ($user && $product) {
            $response = $this->actingAs($user)
                ->post("/cart/add/{$product->id}", ['quantity' => 1]);
            $response->assertStatus(302); // redirect after add
        } else {
            $this->markTestSkipped('No users or products in database');
        }
    }

    /**
     * اختبار صفحة المقارنة
     */
    public function test_compare_page_loads(): void
    {
        $response = $this->get('/compare');
        $response->assertStatus(200);
    }

    /**
     * اختبار sitemap
     */
    public function test_sitemap_loads(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
    }

    /**
     * اختبار API التوصيات
     */
    public function test_recommendations_api_works(): void
    {
        $response = $this->get('/api/recommendations');
        $response->assertStatus(200);
    }

    /**
     * اختبار حساب عناصر السلة
     */
    public function test_cart_count_api_requires_auth(): void
    {
        $response = $this->get('/cart/count');
        $response->assertRedirect('/login');
    }

    /**
     * اختبار أن المستخدم المسجل يمكنه الحصول على عدد عناصر السلة
     */
    public function test_authenticated_user_can_get_cart_count(): void
    {
        $user = User::first();
        if ($user) {
            $response = $this->actingAs($user)->get('/cart/count');
            $response->assertStatus(200);
            $response->assertJsonStructure(['count']);
        } else {
            $this->markTestSkipped('No users in database');
        }
    }

    /**
     * اختبار وجود الموديلات
     */
    public function test_models_exist(): void
    {
        $this->assertTrue(class_exists(User::class));
        $this->assertTrue(class_exists(Product::class));
        $this->assertTrue(class_exists(Category::class));
        $this->assertTrue(class_exists(Brand::class));
        $this->assertTrue(class_exists(Cart::class));
        $this->assertTrue(class_exists(Order::class));
        $this->assertTrue(class_exists(Role::class));
        $this->assertTrue(class_exists(Permission::class));
    }

    /**
     * اختبار البيانات في قاعدة البيانات
     */
    public function test_database_has_data(): void
    {
        $this->assertGreaterThan(0, User::count(), 'No users found');
        $this->assertGreaterThan(0, Product::count(), 'No products found');
        $this->assertGreaterThan(0, Category::count(), 'No categories found');
    }

    /**
     * اختبار تسجيل الخروج
     */
    public function test_user_can_logout(): void
    {
        $user = User::first();
        if ($user) {
            $response = $this->actingAs($user)->post('/logout');
            $response->assertRedirect('/');
        } else {
            $this->markTestSkipped('No users in database');
        }
    }
}

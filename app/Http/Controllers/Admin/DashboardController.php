<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Order;
use App\Models\Offer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // إحصائيات شاملة مع معالجة الأخطاء
            $stats = [
                'users_count' => User::count() ?? 0,
                'roles_count' => Role::where('is_active', true)->count() ?? 0,
                'permissions_count' => Permission::where('is_active', true)->count() ?? 0,
                'products_count' => Product::count() ?? 0,
                'orders_count' => Order::count() ?? 0,
                'pending_orders' => Order::where('status', 'pending')->count() ?? 0,
                'processing_orders' => Order::where('status', 'processing')->count() ?? 0,
                'completed_orders' => Order::where('status', 'completed')->count() ?? 0,
                'cancelled_orders' => Order::where('status', 'cancelled')->count() ?? 0,
                'total_revenue' => Order::where('status', 'completed')->sum('total') ?? 0,
                'low_stock_products' => Product::where('stock', '<', 10)->count() ?? 0,
                'active_offers' => Offer::where('is_active', true)->count() ?? 0,
            ];

            // المستخدمين الجدد
            $recent_users = User::with('role')
                ->latest()
                ->take(5)
                ->get();

            // آخر الطلبات
            $recent_orders = Order::with(['user', 'items'])
                ->latest()
                ->take(5)
                ->get();

            // إحصائيات الطلبات حسب الحالة
            $orders_by_status = [
                'pending' => Order::where('status', 'pending')->count() ?? 0,
                'processing' => Order::where('status', 'processing')->count() ?? 0,
                'completed' => Order::where('status', 'completed')->count() ?? 0,
                'cancelled' => Order::where('status', 'cancelled')->count() ?? 0,
            ];

            // إحصائيات المبيعات (آخر 7 أيام) - مع معالجة الأخطاء
            try {
                $sales_last_7_days = Order::where('status', 'completed')
                    ->where('created_at', '>=', now()->subDays(7))
                    ->selectRaw('DATE(created_at) as date, SUM(total) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
            } catch (\Exception $e) {
                $sales_last_7_days = collect();
            }

            return view('admin.dashboard', compact(
                'stats', 
                'recent_users', 
                'recent_orders',
                'orders_by_status',
                'sales_last_7_days'
            ));
        } catch (\Exception $e) {
            // في حالة حدوث خطأ، إرجاع بيانات افتراضية
            $stats = [
                'users_count' => 0,
                'roles_count' => 0,
                'permissions_count' => 0,
                'products_count' => 0,
                'orders_count' => 0,
                'pending_orders' => 0,
                'processing_orders' => 0,
                'completed_orders' => 0,
                'cancelled_orders' => 0,
                'total_revenue' => 0,
                'low_stock_products' => 0,
                'active_offers' => 0,
            ];

            $recent_users = collect();
            $recent_orders = collect();
            $orders_by_status = [
                'pending' => 0,
                'processing' => 0,
                'completed' => 0,
                'cancelled' => 0,
            ];
            $sales_last_7_days = collect();

            return view('admin.dashboard', compact(
                'stats', 
                'recent_users', 
                'recent_orders',
                'orders_by_status',
                'sales_last_7_days'
            ))->with('error', 'حدث خطأ في تحميل البيانات: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        // مشاركة الإحصائيات مع جميع صفحات الأدمن
        view()->composer('admin.*', function ($view) {
            $stats = [
                'users_count' => \App\Models\User::count(),
                'products_count' => \App\Models\Product::count(),
                'orders_count' => \App\Models\Order::count(),
                'pending_orders' => \App\Models\Order::where('status', 'pending')->count(),
            ];
            
            $view->with('stats', $stats);
        });
    }
}

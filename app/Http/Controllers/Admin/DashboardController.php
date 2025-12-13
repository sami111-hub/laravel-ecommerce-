<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'roles_count' => Role::count(),
            'permissions_count' => Permission::count(),
            'products_count' => Product::count(),
            'orders_count' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_orders = Order::latest()->take(5)->with('user')->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_orders'));
    }
}

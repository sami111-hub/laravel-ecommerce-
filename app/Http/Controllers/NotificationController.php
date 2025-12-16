<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function notifyWhenAvailable(Request $request, Product $product)
    {
        // يمكن إضافة جدول notifications لاحقاً
        // حالياً نستخدم session أو cache
        
        $notifications = session('product_notifications', []);
        
        if (!in_array($product->id, $notifications)) {
            $notifications[] = $product->id;
            session(['product_notifications' => $notifications]);
            
            return response()->json([
                'success' => true,
                'message' => 'سيتم إشعارك عند توفر المنتج'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'أنت مسجل بالفعل للإشعارات لهذا المنتج'
        ]);
    }
}



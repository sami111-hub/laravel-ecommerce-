<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        // جلب جميع المنتجات النشطة
        $products = Product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->select('id', 'slug', 'name', 'image', 'updated_at')
            ->get();

        // جلب جميع التصنيفات النشطة التي تحتوي على منتجات
        $categories = Category::has('products')
            ->select('id', 'slug', 'name', 'updated_at')
            ->get();

        return response()->view('sitemap', compact('products', 'categories'))
            ->header('Content-Type', 'text/xml');
    }
}

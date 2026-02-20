@extends('layout')

@section('title', 'الهواتف الذكية')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">الهواتف الذكية</h1>
        <p class="text-gray-600">اكتشف أحدث الهواتف الذكية بأفضل الأسعار</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                <h3 class="text-lg font-semibold mb-4">تصفية النتائج</h3>

                <form method="GET" action="{{ route('phones.index') }}" class="space-y-6">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="ابحث عن هاتف..." 
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Brand Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">العلامة التجارية</label>
                        <select name="brand" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">كل العلامات</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }} ({{ $brand->phones_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نطاق السعر</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                   placeholder="من" 
                                   class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                   placeholder="إلى" 
                                   class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الترتيب</label>
                        <select name="sort" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>الأحدث</option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>السعر</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>الأكثر مشاهدة</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        تطبيق الفلتر
                    </button>
                    
                    @if(request()->hasAny(['search', 'brand', 'min_price', 'max_price', 'sort']))
                        <a href="{{ route('phones.index') }}" class="block w-full text-center text-blue-600 hover:text-blue-800">
                            إعادة تعيين
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <!-- Results Count -->
            <div class="mb-6 flex justify-between items-center">
                <p class="text-gray-600">
                    عرض {{ $phones->count() }} من {{ $phones->total() }} هاتف
                </p>
            </div>

            @if($phones->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($phones as $phone)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition overflow-hidden group">
                            <a href="{{ route('phones.show', $phone->slug) }}">
                                <!-- Image -->
                                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                    <img src="{{ $phone->thumbnail_url }}" 
                                         alt="{{ $phone->name }}" 
                                         class="w-full h-full object-contain group-hover:scale-110 transition duration-300">
                                </div>

                                <!-- Content -->
                                <div class="p-4">
                                    <!-- Brand -->
                                    <p class="text-xs text-gray-500 mb-1">{{ $phone->brand->name }}</p>
                                    
                                    <!-- Name -->
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $phone->name }}</h3>
                                    
                                    <!-- Specs -->
                                    <div class="text-xs text-gray-600 space-y-1 mb-3">
                                        @if($phone->ram)
                                            <p>• {{ $phone->ram }} رام</p>
                                        @endif
                                        @if($phone->storage)
                                            <p>• {{ $phone->storage }} تخزين</p>
                                        @endif
                                        @if($phone->chipset)
                                            <p>• {{ $phone->chipset }}</p>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    @if($phone->prices->count() > 0)
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($phone->prices->first()->price) }} ريال
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $phones->links() }}
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-12 text-center">
                    <p class="text-gray-600 text-lg">لا توجد هواتف تطابق معايير البحث</p>
                    <a href="{{ route('phones.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                        عرض جميع الهواتف
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection



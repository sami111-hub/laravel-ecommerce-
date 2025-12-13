<nav class="main-navbar py-2">
    <div class="container d-flex justify-content-center gap-2 gap-md-4">
        @php
            try {
                $navCategories = isset($categories) && $categories->count() ? $categories->whereNull('parent_id')->take(5) : \App\Models\Category::whereNull('parent_id')->take(5)->get();
            } catch (\Throwable $e) {
                $navCategories = collect();
            }
        @endphp

        @foreach($navCategories as $cat)
            <a href="{{ route('products.category', $cat) }}" class="nav-link {{ request()->is('category/'.$cat->id) ? 'active' : '' }}">{{ $cat->name }}</a>
        @endforeach
        <a href="/offers" class="nav-link {{ request()->is('offers') ? 'active' : '' }}">العروض</a>
    </div>
</nav>
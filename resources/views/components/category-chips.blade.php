@if(isset($categories) && $categories && $categories->count())
<div class="chips" dir="rtl">
    <a href="{{ route('products.index') }}" class="chip {{ request()->routeIs('products.index') ? 'active' : '' }}">الكل</a>
    @foreach($categories->whereNull('parent_id') as $cat)
        <a href="{{ route('products.category', $cat) }}" class="chip {{ request()->is('category/'.$cat->id) ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
    @endforeach
    
</div>
@endif

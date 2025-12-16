@extends('layout')

@section('title', $product->name . ' - Update Aden')
@section('description', Str::limit($product->description, 150) . ' - اشتري الآن بسعر ' . number_format($product->price, 2) . ' $ مع التوصيل السريع')

@section('content')
<div class="row">
    <div class="col-md-6 mb-3">
        @if($product->image)
            <img src="{{ $product->image }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" style="height: 400px;">
                <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <h1>{{ $product->name }}</h1>
        <p class="text-muted lead">{{ $product->description }}</p>
        <h3 class="text-primary mb-3">${{ number_format($product->price, 2) }}</h3>
        
        <div class="mb-3">
            <strong>المخزون:</strong> {{ $product->stock }}
        </div>

        <div class="mb-3">
            <div class="d-flex gap-2 mb-2">
                @auth
                    @if(\App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists())
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-heart-fill"></i> من المفضلة
                            </button>
                        </form>
                    @else
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-heart"></i> أضف للمفضلة
                            </button>
                        </form>
                    @endif
                @endauth
                
                @php
                    $compareList = session('compare', []);
                    $inCompare = in_array($product->id, $compareList);
                @endphp
                @if($inCompare)
                    <form action="{{ route('compare.remove', $product) }}" method="POST" class="flex-fill">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-arrow-left-right"></i> من المقارنة
                        </button>
                    </form>
                @else
                    <form action="{{ route('compare.add', $product) }}" method="POST" class="flex-fill">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-arrow-left-right"></i> أضف للمقارنة
                        </button>
                    </form>
                @endif
            </div>
            
            @auth
            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-3">
                @csrf
                <div class="mb-3">
                    <label for="quantity" class="form-label">الكمية</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-cart-plus"></i> أضف إلى السلة
                </button>
            </form>
            @else
            <div class="alert alert-info">
                <a href="{{ route('login') }}" class="btn btn-primary">تسجيل الدخول لإضافة المنتج للسلة</a>
            </div>
            @endauth
        </div>

        {{-- Rating Summary --}}
        @php
            $reviews = $product->reviews->where('is_approved', true);
            $averageRating = $reviews->avg('rating') ?? 0;
            $reviewCount = $reviews->count();
        @endphp
        
        @if($reviewCount > 0)
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-star-fill"></i> التقييمات ({{ $reviewCount }})</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <div class="display-4 text-warning">{{ number_format($averageRating, 1) }}</div>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating))
                                    <i class="bi bi-star-fill text-warning"></i>
                                @elseif($i - $averageRating < 1 && $i - $averageRating > 0)
                                    <i class="bi bi-star-half text-warning"></i>
                                @else
                                    <i class="bi bi-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <small class="text-muted">من {{ $reviewCount }} تقييم</small>
                    </div>
                    <div class="col-md-8">
                        @for($rating = 5; $rating >= 1; $rating--)
                            @php
                                $count = $reviews->where('rating', $rating)->count();
                                $percentage = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
                            @endphp
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="me-2" style="width: 20px;">{{ $rating }}</span>
                                    <i class="bi bi-star-fill text-warning me-2"></i>
                                    <div class="progress flex-fill me-2" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-muted small">{{ $count }}</span>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <hr>
                
                {{-- Add Review Form --}}
                @auth
                    @php
                        $userReview = $reviews->where('user_id', Auth::id())->first();
                    @endphp
                    @if(!$userReview)
                    <div class="mb-4">
                        <h6>أضف تقييمك</h6>
                        <form action="{{ route('reviews.store', $product) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">التقييم</label>
                                <div class="rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" required>
                                        <label for="rating{{ $i }}" class="star-label">
                                            <i class="bi bi-star-fill"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">تعليقك (اختياري)</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" 
                                          placeholder="شاركنا رأيك في هذا المنتج..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                        </form>
                    </div>
                    @endif
                @endauth
                
                {{-- Reviews List --}}
                <div class="reviews-list">
                    @foreach($reviews->sortByDesc('created_at') as $review)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>{{ $review->user->name }}</strong>
                                <div class="text-muted small">{{ $review->created_at->diffForHumans() }}</div>
                            </div>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="mb-0">{{ $review->comment }}</p>
                        @endif
                        @auth
                            @if($review->user_id === Auth::id() || Auth::user()->isAdmin())
                            <div class="mt-2">
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </div>
                            @endif
                        @endauth
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        @auth
        <div class="card mt-3">
            <div class="card-body">
                <h6>كن أول من يقيم هذا المنتج</h6>
                <form action="{{ route('reviews.store', $product) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">التقييم</label>
                        <div class="rating-input">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" required>
                                <label for="rating{{ $i }}" class="star-label">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">تعليقك (اختياري)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" 
                                  placeholder="شاركنا رأيك في هذا المنتج..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                </form>
            </div>
        </div>
        @endauth
        @endif

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> رجوع إلى المنتجات
        </a>
    </div>
</div>
@endsection


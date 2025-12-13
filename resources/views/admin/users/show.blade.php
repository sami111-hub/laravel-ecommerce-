@extends('admin.layout')

@section('title', 'تفاصيل المستخدم')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5>تفاصيل المستخدم: {{ $user->name }}</h5>
    </div>
    <div class="card-body">
        <p><strong>الاسم:</strong> {{ $user->name }}</p>
        <p><strong>البريد:</strong> {{ $user->email }}</p>
        <p><strong>الدور الرئيسي:</strong> 
            @if($user->role)
                <span class="badge bg-primary">{{ $user->role->name }}</span>
            @else
                <span class="text-muted">-</span>
            @endif
        </p>
        <p><strong>الأدوار الإضافية:</strong>
            @foreach($user->roles as $role)
                <span class="badge bg-secondary">{{ $role->name }}</span>
            @endforeach
            @if($user->roles->isEmpty())
                <span class="text-muted">-</span>
            @endif
        </p>
        <p><strong>تاريخ التسجيل:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</p>
        <p><strong>عدد الطلبات:</strong> {{ $user->orders->count() }}</p>
        <p><strong>عدد المنتجات في السلة:</strong> {{ $user->carts->count() }}</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>ترقية المستخدم</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.promote', $user) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="promote_role_id" class="form-label">ترقية إلى دور</label>
                <select name="role_id" id="promote_role_id" class="form-select" required>
                    <option value="">اختر الدور</option>
                    @php
                        $roles = \App\Models\Role::where('is_active', true)->get();
                    @endphp
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">ترقية المستخدم</button>
        </form>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">تعديل</a>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">رجوع</a>
</div>
@endsection


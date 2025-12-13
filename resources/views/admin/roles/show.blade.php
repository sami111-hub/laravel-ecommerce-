@extends('admin.layout')

@section('title', 'تفاصيل الدور')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5>تفاصيل الدور: {{ $role->name }}</h5>
    </div>
    <div class="card-body">
        <p><strong>الاسم:</strong> {{ $role->name }}</p>
        <p><strong>الوصف:</strong> {{ $role->description ?? '-' }}</p>
        <p><strong>الحالة:</strong> 
            @if($role->is_active)
                <span class="badge bg-success">نشط</span>
            @else
                <span class="badge bg-danger">غير نشط</span>
            @endif
        </p>
        <p><strong>عدد المستخدمين:</strong> {{ $role->users->count() }}</p>
        <p><strong>عدد الصلاحيات:</strong> {{ $role->permissions->count() }}</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>المستخدمين</h5>
    </div>
    <div class="card-body">
        @if($role->users->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>البريد</th>
                    <th>تاريخ التسجيل</th>
                </tr>
            </thead>
            <tbody>
                @foreach($role->users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-muted">لا يوجد مستخدمين بهذا الدور</p>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>الصلاحيات</h5>
    </div>
    <div class="card-body">
        @if($role->permissions->count() > 0)
        <div class="row">
            @foreach($role->permissions->groupBy('group') as $group => $permissions)
            <div class="col-md-6 mb-3">
                <h6>{{ $group }}</h6>
                <ul>
                    @foreach($permissions as $permission)
                    <li>{{ $permission->name }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">لا توجد صلاحيات لهذا الدور</p>
        @endif
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">تعديل</a>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">رجوع</a>
</div>
@endsection


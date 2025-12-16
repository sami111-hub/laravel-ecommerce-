@extends('admin.layout')

@section('title', 'تفاصيل الدور')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-shield-check"></i> تفاصيل الدور: {{ $role->name }}</h4>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للقائمة
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">
    <!-- معلومات الدور -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> معلومات الدور</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">الاسم:</th>
                        <td><strong>{{ $role->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $role->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>الوصف:</th>
                        <td>{{ $role->description ?? 'لا يوجد وصف' }}</td>
                    </tr>
                    <tr>
                        <th>الحالة:</th>
                        <td>
                            @if($role->is_active)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> نشط
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle"></i> غير نشط
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>عدد المستخدمين:</th>
                        <td>
                            <span class="badge bg-info">
                                <i class="bi bi-people-fill"></i> {{ $role->users->count() }} مستخدم
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>عدد الصلاحيات:</th>
                        <td>
                            <span class="badge bg-success">
                                <i class="bi bi-shield-check"></i> {{ $role->permissions->count() }} صلاحية
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>تاريخ الإنشاء:</th>
                        <td>{{ $role->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>آخر تحديث:</th>
                        <td>{{ $role->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> إحصائيات</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-people-fill" style="font-size: 3rem; color: #17a2b8;"></i>
                    <h3 class="mt-2">{{ $role->users->count() }}</h3>
                    <p class="text-muted mb-0">مستخدم</p>
                </div>
                <hr>
                <div>
                    <i class="bi bi-shield-check" style="font-size: 3rem; color: #28a745;"></i>
                    <h3 class="mt-2">{{ $role->permissions->count() }}</h3>
                    <p class="text-muted mb-0">صلاحية</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- المستخدمين -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="bi bi-people-fill text-primary"></i> المستخدمين
            <span class="badge bg-primary">{{ $role->users->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($role->users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($role->users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <i class="bi bi-calendar3"></i>
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> عرض
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
            <p class="text-muted mt-2 mb-0">لا يوجد مستخدمين بهذا الدور</p>
        </div>
        @endif
    </div>
</div>

<!-- الصلاحيات -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="bi bi-shield-check text-success"></i> الصلاحيات
            <span class="badge bg-success">{{ $role->permissions->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($role->permissions->count() > 0)
        <div class="row">
            @foreach($role->permissions->groupBy('group') as $group => $permissions)
            <div class="col-md-6 mb-4">
                <div class="card border">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-folder-fill"></i> {{ $group }}</strong>
                        <span class="badge bg-secondary float-end">{{ $permissions->count() }}</span>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($permissions as $permission)
                            <li class="mb-2">
                                <i class="bi bi-shield-fill-check text-success"></i>
                                <strong>{{ $permission->name }}</strong>
                                @if($permission->description)
                                <br><small class="text-muted ms-3">{{ $permission->description }}</small>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-4">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
            <p class="text-muted mt-2 mb-0">لا توجد صلاحيات لهذا الدور</p>
        </div>
        @endif
    </div>
</div>

<!-- أزرار الإجراءات -->
<div class="mt-4">
    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
        <i class="bi bi-pencil"></i> تعديل الدور
    </a>
    @if($role->slug !== 'super-admin')
    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" 
          onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟ سيتم إزالة جميع الصلاحيات المرتبطة به.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash"></i> حذف الدور
        </button>
    </form>
    @endif
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>
@endsection

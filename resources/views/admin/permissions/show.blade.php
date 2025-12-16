@extends('admin.layout')

@section('title', 'تفاصيل الصلاحية')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-shield-check"></i> تفاصيل الصلاحية: {{ $permission->name }}</h4>
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> العودة للقائمة
    </a>
</div>

<div class="row g-4">
    <!-- معلومات الصلاحية -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> معلومات الصلاحية</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">الاسم:</th>
                        <td><strong>{{ $permission->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $permission->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>المجموعة:</th>
                        <td>
                            <span class="badge bg-info">
                                <i class="bi bi-folder"></i> {{ $permission->group }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>الوصف:</th>
                        <td>{{ $permission->description ?? 'لا يوجد وصف' }}</td>
                    </tr>
                    <tr>
                        <th>الحالة:</th>
                        <td>
                            @if($permission->is_active)
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
                        <th>تاريخ الإنشاء:</th>
                        <td>{{ $permission->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>آخر تحديث:</th>
                        <td>{{ $permission->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- الأدوار المرتبطة -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill"></i> الأدوار المرتبطة
                    <span class="badge bg-light text-dark">{{ $permission->roles->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($permission->roles->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($permission->roles as $role)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $role->name }}</strong>
                                @if($role->is_active)
                                    <span class="badge bg-success ms-2">نشط</span>
                                @else
                                    <span class="badge bg-danger ms-2">غير نشط</span>
                                @endif
                            </div>
                            <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
                        <p class="text-muted mt-2 mb-0">لا توجد أدوار مرتبطة بهذه الصلاحية</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- أزرار الإجراءات -->
<div class="mt-4">
    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning">
        <i class="bi bi-pencil"></i> تعديل الصلاحية
    </a>
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>
@endsection



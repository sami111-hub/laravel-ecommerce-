@extends('admin.layout')

@section('title', 'إدارة الصلاحيات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-shield-check"></i> إدارة الصلاحيات</h4>
    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> إضافة صلاحية جديدة
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

@forelse($permissions as $group => $groupPermissions)
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-folder-fill text-primary"></i> {{ $group }}
            </h5>
            <span class="badge bg-primary">{{ $groupPermissions->count() }} صلاحية</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>الأدوار</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupPermissions as $permission)
                    <tr>
                        <td>
                            <strong>{{ $permission->name }}</strong>
                            <br><small class="text-muted">Slug: {{ $permission->slug }}</small>
                        </td>
                        <td>{{ $permission->description ?? '-' }}</td>
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
                        <td>
                            <span class="badge bg-info">{{ $permission->roles->count() }} دور</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.permissions.show', $permission) }}" 
                                   class="btn btn-sm btn-info" title="عرض التفاصيل">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                   class="btn btn-sm btn-warning" title="تعديل">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.permissions.destroy', $permission) }}" 
                                      method="POST" class="d-inline" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الصلاحية؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@empty
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
        <h5 class="mt-3 text-muted">لا توجد صلاحيات</h5>
        <p class="text-muted">ابدأ بإضافة صلاحية جديدة</p>
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> إضافة صلاحية جديدة
        </a>
    </div>
</div>
@endforelse
@endsection


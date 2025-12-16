@extends('admin.layout')

@section('title', 'إدارة الأدوار')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>الأدوار</h4>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> إضافة دور جديد
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

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>المستخدمين</th>
                        <th>الصلاحيات</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>
                            <strong>{{ $role->name }}</strong>
                            <br><small class="text-muted">Slug: <code>{{ $role->slug }}</code></small>
                        </td>
                        <td>{{ $role->description ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">
                                <i class="bi bi-people-fill"></i> {{ $role->users_count }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success">
                                <i class="bi bi-shield-check"></i> {{ $role->permissions_count }}
                            </span>
                        </td>
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
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.roles.show', $role) }}" 
                                   class="btn btn-sm btn-info" title="عرض التفاصيل">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                   class="btn btn-sm btn-warning" title="تعديل">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($role->slug !== 'super-admin')
                                <form action="{{ route('admin.roles.destroy', $role) }}" 
                                      method="POST" class="d-inline" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟ سيتم إزالة جميع الصلاحيات المرتبطة به.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
                            <p class="text-muted mt-2 mb-0">لا توجد أدوار</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($roles->hasPages())
        <div class="mt-3">
            {{ $roles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


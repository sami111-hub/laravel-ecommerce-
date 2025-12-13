@extends('admin.layout')

@section('title', 'إدارة الصلاحيات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>الصلاحيات</h4>
    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> إضافة صلاحية جديدة
    </a>
</div>

@foreach($permissions as $group => $groupPermissions)
<div class="card mb-4">
    <div class="card-header">
        <h5>{{ $group }}</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الوصف</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupPermissions as $permission)
                <tr>
                    <td><strong>{{ $permission->name }}</strong></td>
                    <td>{{ $permission->description ?? '-' }}</td>
                    <td>
                        @if($permission->is_active)
                            <span class="badge bg-success">نشط</span>
                        @else
                            <span class="badge bg-danger">غير نشط</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endsection


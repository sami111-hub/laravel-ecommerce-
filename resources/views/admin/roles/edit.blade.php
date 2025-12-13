@extends('admin.layout')

@section('title', 'تعديل الدور')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>تعديل الدور: {{ $role->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">اسم الدور</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $role->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">نشط</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الصلاحيات</label>
                @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>{{ $group }}</strong>
                    </div>
                    <div class="card-body">
                        @foreach($groupPermissions as $permission)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}" 
                                {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection


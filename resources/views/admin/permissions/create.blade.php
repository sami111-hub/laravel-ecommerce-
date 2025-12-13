@extends('admin.layout')

@section('title', 'إضافة صلاحية جديدة')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>إضافة صلاحية جديدة</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">اسم الصلاحية</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="group" class="form-label">المجموعة</label>
                <select class="form-select @error('group') is-invalid @enderror" id="group" name="group" required>
                    <option value="">اختر المجموعة</option>
                    @foreach($groups as $key => $label)
                    <option value="{{ $key }}" {{ old('group') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('group')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">حفظ</button>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection


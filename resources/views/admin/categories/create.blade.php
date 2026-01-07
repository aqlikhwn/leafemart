@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add New Category</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card" style="max-width: 500px;">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label">Category Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Icon (Emoji)</label>
            <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="e.g., 🍔">
            <small style="color: var(--gray-400);">Enter an emoji or leave blank</small>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Create Category
        </button>
    </form>
</div>
@endsection

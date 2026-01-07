@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
<div class="page-header">
    <h1 class="page-title">Manage Categories</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Category
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
    </div>
</div>

<div class="card">
    <div class="grid grid-3">
        @foreach($categories as $category)
        <div style="background: var(--gray-100); border-radius: 12px; padding: 24px; text-align: center;">
            <div style="font-size: 48px; margin-bottom: 15px;">{{ $category->icon ?? '📁' }}</div>
            <h3 style="color: var(--primary-dark);">{{ $category->name }}</h3>
            <p style="color: var(--gray-400); margin: 10px 0;">{{ $category->products_count }} products</p>
            <p style="color: var(--gray-600); font-size: 13px;">{{ $category->description ?? 'No description' }}</p>
            <div style="display: flex; justify-content: center; gap: 10px; margin-top: 15px;">
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-secondary" style="padding: 8px 16px;">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @if($category->products_count == 0)
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding: 8px 16px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add New Product</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Product Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Category *</label>
            <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->icon }} {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Main Product Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" style="padding: 8px;">
        </div>

        <div class="form-group">
            <label class="form-label">Additional Images (Optional)</label>
            <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="padding: 8px;">
            <small style="color: var(--gray-400);">You can select multiple images. Max 2MB each.</small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Base Price (RM) *</label>
                <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stock *</label>
                <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', 0) }}" required>
                <small style="color: var(--gray-400);">Set to 0 if using variations</small>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="6" placeholder="Enter detailed product description.">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Description Image (optional)</label>
            <input type="file" name="description_image" class="form-control" accept="image/*">
        </div>

        <!-- Variations Notice -->
        <div class="alert alert-info" style="margin-bottom: 20px;">
            <i class="fas fa-info-circle"></i>
            <strong>Want to add variations (colors, sizes)?</strong><br>
            Create the product first, then you'll be redirected to add variations like Blue, Black, Red, etc.
        </div>

        <!-- Add Initial Variations -->
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-list"></i> Initial Variations (Optional)
            </label>
            <p style="color: var(--gray-400); font-size: 13px; margin-bottom: 10px;">
                Enter variation names separated by commas. Each will start with the stock quantity you specify.
            </p>
            <input type="text" name="variations" class="form-control" value="{{ old('variations') }}" 
                   placeholder="e.g., Blue, Black, Red, Green">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px;">
                <div>
                    <label class="form-label" style="font-size: 13px;">Stock per variation</label>
                    <input type="number" name="variation_stock" class="form-control" min="0" value="{{ old('variation_stock', 10) }}">
                </div>
                <div>
                    <label class="form-label" style="font-size: 13px;">Price per variation (RM)</label>
                    <input type="number" name="variation_price" class="form-control" step="0.01" min="0" value="{{ old('variation_price') }}" placeholder="Leave blank to use base price">
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary);">
                <span>Featured Product</span>
            </label>
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="active" value="1" checked style="width: 18px; height: 18px; accent-color: var(--primary);">
                <span>Active</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Create Product
        </button>
    </form>
</div>
@endsection

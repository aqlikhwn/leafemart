@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
<div class="page-header">
    <h1 class="page-title">Manage Products</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="card" style="margin-bottom: 20px;">
    <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label class="form-label">Search Products</label>
            <div style="position: relative;">
                <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}" style="padding-left: 40px;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray-400);"></i>
            </div>
        </div>
        
        <div class="form-group" style="min-width: 150px; margin-bottom: 0;">
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->icon }} {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="min-width: 120px; margin-bottom: 0;">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="form-group" style="min-width: 120px; margin-bottom: 0;">
            <label class="form-label">Stock</label>
            <select name="stock" class="form-control">
                <option value="">All Stock</option>
                <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Low Stock (&lt;10)</option>
                <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                <option value="in" {{ request('stock') == 'in' ? 'selected' : '' }}>In Stock</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'category', 'status', 'stock']))
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Clear
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Results Count -->
@if(request()->hasAny(['search', 'category', 'status', 'stock']))
<div style="margin-bottom: 15px; color: var(--gray-600);">
    <i class="fas fa-info-circle"></i> Found <strong>{{ $products->total() }}</strong> products
    @if(request('search')) matching "<strong>{{ request('search') }}</strong>"@endif
</div>
@endif

<div class="card">
    @if($products->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Variations</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 45px; height: 45px; background: var(--primary-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            {{ $product->category->icon ?? '📦' }}
                            @endif
                        </div>
                        <div>
                            <strong>{{ $product->name }}</strong>
                            @if($product->featured)<span class="badge badge-primary" style="margin-left: 5px;">Featured</span>@endif
                        </div>
                    </div>
                </td>
                <td>{{ $product->category->name }}</td>
                <td>RM {{ number_format($product->price, 2) }}</td>
                <td>
                    @if($product->hasVariations())
                        @php $totalStock = $product->variations->sum('stock'); @endphp
                        <span class="badge {{ $totalStock < 10 ? 'badge-warning' : 'badge-success' }}">
                            {{ $totalStock }} (variations)
                        </span>
                    @else
                        @if($product->stock == 0)
                        <span class="badge badge-danger">Out of Stock</span>
                        @elseif($product->stock < 10)
                        <span class="badge badge-warning">{{ $product->stock }}</span>
                        @else
                        <span class="badge badge-success">{{ $product->stock }}</span>
                        @endif
                    @endif
                </td>
                <td>
                    @if($product->variations_count > 0)
                    <span class="badge badge-info">{{ $product->variations_count }} variations</span>
                    @else
                    <span style="color: var(--gray-400);">-</span>
                    @endif
                </td>
                <td>
                    @if($product->active)
                    <span class="badge badge-success">Active</span>
                    @else
                    <span class="badge badge-warning">Inactive</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary" style="padding: 8px 12px;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 8px 12px;" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">{{ $products->withQueryString()->links() }}</div>
    @else
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        @if(request()->hasAny(['search', 'category', 'status', 'stock']))
        <h3>No Products Found</h3>
        <p>Try adjusting your search or filters.</p>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="margin-top: 15px;">Clear Filters</a>
        @else
        <h3>No Products</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="margin-top: 15px;">Add First Product</a>
        @endif
    </div>
    @endif
</div>
@endsection

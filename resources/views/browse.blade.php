@extends('layouts.app')

@section('title', 'Browse Products')

@section('content')
<div class="page-header">
    <h1 class="page-title">Browse Products</h1>
    <form action="{{ route('search') }}" method="GET" style="display: flex; gap: 10px;">
        <input type="text" name="q" class="form-control" placeholder="Search products..." value="{{ request('q') }}" style="width: 300px;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<!-- Category Filter -->
<div class="category-pills">
    <a href="{{ route('browse') }}" class="category-pill {{ !$categoryId ? 'active' : '' }}">
        All Products <span style="opacity: 0.7;">({{ $categories->sum('products_count') }})</span>
    </a>
    @foreach($categories as $category)
    <a href="{{ route('browse', ['category' => $category->id]) }}" 
       class="category-pill {{ $categoryId == $category->id ? 'active' : '' }}">
        {{ $category->icon }} {{ $category->name }} <span style="opacity: 0.7;">({{ $category->products_count }})</span>
    </a>
    @endforeach
</div>

<!-- Products Grid -->
@if($products->count() > 0)
<style>
    .grid-5 {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
    }
    @media (max-width: 1200px) {
        .grid-5 {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    @media (max-width: 900px) {
        .grid-5 {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 600px) {
        .grid-5 {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
<div class="grid-5">
    @foreach($products as $product)
    <a href="{{ route('product.show', $product->id) }}?from=browse" class="product-card" style="text-decoration: none; position: relative;">
        @if($product->featured)
        <!-- Featured Badge -->
        <div style="position: absolute; top: 10px; left: 10px; z-index: 2;">
            <div style="background: linear-gradient(135deg, #FFD700, #FFA500); color: #1E3A5F; padding: 4px 10px; border-radius: 15px; font-size: 9px; font-weight: 700; display: flex; align-items: center; gap: 4px; box-shadow: 0 2px 10px rgba(255,215,0,0.4);">
                <i class="fas fa-star"></i> FEATURED
            </div>
        </div>
        @endif
        <div class="product-image">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            @else
            {{ $product->category->icon ?? '📦' }}
            @endif
        </div>
        <div class="product-info">
            <div class="product-name">{{ $product->name }}</div>
            <div style="color: var(--gray-400); font-size: 12px; margin-bottom: 5px;">{{ $product->category->name }}</div>
            <div class="product-price">
                @if($product->price_range)
                    {{ $product->price_range }}
                @else
                    RM {{ number_format($product->price, 2) }}
                @endif
            </div>
            @if($product->hasVariations())
                @if($product->total_stock < 10 && $product->total_stock > 0)
                <span class="badge badge-warning" style="margin-top: 8px;">Low Stock</span>
                @elseif($product->total_stock == 0)
                <span class="badge badge-danger" style="margin-top: 8px;">Out of Stock</span>
                @endif
            @elseif($product->stock < 10 && $product->stock > 0)
            <span class="badge badge-warning" style="margin-top: 8px;">Only {{ $product->stock }} left</span>
            @elseif($product->stock == 0)
            <span class="badge badge-danger" style="margin-top: 8px;">Out of Stock</span>
            @endif
        </div>
    </a>
    @endforeach
</div>

<div style="margin-top: 30px;">
    {{ $products->appends(request()->query())->links() }}
</div>
@else
<div class="card">
    <div class="empty-state">
        <i class="fas fa-search"></i>
        <h3>No Products Found</h3>
        <p>Try adjusting your search or browse all products.</p>
        <a href="{{ route('browse') }}" class="btn btn-primary" style="margin-top: 15px;">View All Products</a>
    </div>
</div>
@endif
@endsection

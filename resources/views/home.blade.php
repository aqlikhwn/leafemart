@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="page-header">
    <h1 class="page-title">Welcome to Leafé Mart</h1>
</div>

<!-- Introduction Hero -->
<div class="card" style="margin-bottom: 30px; background: linear-gradient(135deg, #E3F2FD, #BBDEFB); border: 2px solid var(--primary-light);">
    <div style="display: flex; align-items: center; gap: 30px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 280px;">
            <h2 style="color: var(--primary-dark); margin-bottom: 15px; font-size: 24px;">
                <i class="fas fa-leaf" style="color: var(--primary);"></i> Your Campus Mini Market
            </h2>
            <p style="color: var(--gray-600); line-height: 1.7; margin-bottom: 15px;">
                Leafé Mart is the official online store for <strong>Mahallah Bilal</strong> residents and IIUM students. 
                Shop for everyday essentials, snacks, drinks, and more — all from the comfort of your room!
            </p>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 8px; color: var(--primary-dark);">
                    <i class="fas fa-clock" style="color: var(--primary);"></i>
                    <span style="font-size: 14px;">24/7 Online Ordering</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; color: var(--primary-dark);">
                    <i class="fas fa-store" style="color: var(--primary);"></i>
                    <span style="font-size: 14px;">Easy Pickup</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; color: var(--primary-dark);">
                    <i class="fas fa-motorcycle" style="color: var(--primary);"></i>
                    <span style="font-size: 14px;">Delivery Available</span>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('browse') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
            <a href="{{ route('about') }}" class="btn" style="background: white; color: var(--primary); border: 2px solid var(--primary);">
                <i class="fas fa-info-circle"></i> Learn More
            </a>
        </div>
    </div>
</div>

<!-- Announcements -->
@if($announcements->count() > 0)
<div class="card" style="margin-bottom: 30px; background: linear-gradient(135deg, #4A90D9, #1E3A5F); color: white;">
    <h3 style="margin-bottom: 15px;"><i class="fas fa-bullhorn"></i> Announcements</h3>
    @foreach($announcements as $announcement)
    <div style="padding: 10px 0; {{ !$loop->last ? 'border-bottom: 1px solid rgba(255,255,255,0.2);' : '' }}">
        <strong>{{ $announcement->title }}</strong>
        <p style="margin-top: 5px; opacity: 0.9;">{{ $announcement->content }}</p>
    </div>
    @endforeach
</div>
@endif

<!-- Categories -->
<div class="card" style="margin-bottom: 30px;">
    <h3 style="margin-bottom: 20px; color: var(--primary-dark);">Browse Categories</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
        @php
            $categoryImages = [
                'Food' => 'food.png',
                'Drink' => 'drink.png',
                'Toiletries' => 'toiletries.png',
                'Stationery' => 'stationery.png',
                'Medication' => 'medication.png',
            ];
        @endphp
        @foreach($categories as $category)
        <a href="{{ route('browse', ['category' => $category->id]) }}" class="category-card" style="text-decoration: none; display: block; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 2px solid transparent;">
            <div style="height: 100px; background: var(--primary-light); overflow: hidden;">
                @if(isset($categoryImages[$category->name]))
                <img src="{{ asset('images/categories/' . $categoryImages[$category->name]) }}" alt="{{ $category->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 40px;">
                    {{ $category->icon }}
                </div>
                @endif
            </div>
            <div style="padding: 12px; text-align: center;">
                <div style="font-weight: 600; color: var(--primary-dark); margin-bottom: 4px;">{{ $category->name }}</div>
                <div style="font-size: 12px; color: var(--gray-400);">{{ $category->products_count }} products</div>
            </div>
        </a>
        @endforeach
    </div>
    <div style="margin-top: 15px; text-align: center;">
        <a href="{{ route('browse') }}" class="btn btn-secondary">
            <i class="fas fa-th-large"></i> View All Products ({{ $categories->sum('products_count') }})
        </a>
    </div>
</div>
<style>
    /* Prevent horizontal overflow from carousel */
    body, html {
        overflow-x: hidden !important;
    }
    .main-content {
        overflow-x: hidden !important;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        border-color: var(--primary);
    }
    .featured-view-btn:hover {
        background: #E3F2FD !important;
        color: var(--primary) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    @media (max-width: 600px) {
        .featured-section {
            padding: 12px !important;
            border-radius: 16px !important;
            margin-left: -5px;
            margin-right: -5px;
        }
    }
</style>

<!-- Featured Products -->
<div class="featured-section" style="background: linear-gradient(135deg, #1E3A5F 0%, #2D5A87 50%, #1E3A5F 100%); border-radius: 24px; padding: 30px; margin-bottom: 30px; overflow: hidden;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h3 style="color: white; margin-bottom: 8px; font-size: 24px;">
                <i class="fas fa-star" style="color: #FFD700;"></i> Featured Products
            </h3>
            <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 0;">⭐ Handpicked products just for you</p>
        </div>
        <a href="{{ route('browse') }}" class="featured-view-btn" style="background: white; color: #1E3A5F; padding: 8px 16px; border-radius: 20px; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.15);">
            View All <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    @if($featuredProducts->count() > 0)
    <div class="featured-carousel-wrapper">
        <div class="featured-carousel">
            <!-- First set of products -->
            @foreach($featuredProducts as $product)
            <a href="{{ route('product.show', ['id' => $product->id, 'from' => 'home']) }}" class="featured-product-card">
                <!-- Featured Badge -->
                <div class="featured-badge">
                    <i class="fas fa-star"></i> FEATURED
                </div>
                <div class="featured-product-image">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    @else
                    <span class="product-icon">{{ $product->category->icon ?? '📦' }}</span>
                    @endif
                </div>
                <div class="featured-product-info">
                    <div class="featured-product-name">{{ $product->name }}</div>
                    <div class="featured-product-category">{{ $product->category->name }}</div>
                    <div class="featured-product-price">
                        @if($product->price_range)
                            {{ $product->price_range }}
                        @else
                            RM {{ number_format($product->price, 2) }}
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
            <!-- Duplicate set for seamless loop -->
            @foreach($featuredProducts as $product)
            <a href="{{ route('product.show', ['id' => $product->id, 'from' => 'home']) }}" class="featured-product-card">
                <div class="featured-badge">
                    <i class="fas fa-star"></i> FEATURED
                </div>
                <div class="featured-product-image">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    @else
                    <span class="product-icon">{{ $product->category->icon ?? '📦' }}</span>
                    @endif
                </div>
                <div class="featured-product-info">
                    <div class="featured-product-name">{{ $product->name }}</div>
                    <div class="featured-product-category">{{ $product->category->name }}</div>
                    <div class="featured-product-price">
                        @if($product->price_range)
                            {{ $product->price_range }}
                        @else
                            RM {{ number_format($product->price, 2) }}
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <div style="text-align: center; padding: 40px; color: rgba(255,255,255,0.8);">
        <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 15px;"></i>
        <p>No featured products available.</p>
    </div>
    @endif
</div>

<style>
    .featured-carousel-wrapper {
        position: relative;
        width: 100%;
        overflow: hidden;
    }
    
    .featured-carousel {
        display: flex;
        gap: 20px;
        animation: scroll-carousel 25s linear infinite;
        width: max-content;
        padding: 5px 0;
    }
    
    .featured-carousel:hover {
        animation-play-state: paused;
    }
    
    @keyframes scroll-carousel {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    
    .featured-product-card {
        flex-shrink: 0;
        width: 270px;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .featured-product-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    }
    
    .featured-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        background: linear-gradient(135deg, #FFD700, #FFA500);
        color: #1E3A5F;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 9px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 2px 10px rgba(255,215,0,0.4);
    }
    
    .featured-product-image {
        height: 170px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .featured-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .featured-product-card:hover .featured-product-image img {
        transform: scale(1.1);
    }
    
    .featured-product-image .product-icon {
        font-size: 48px;
    }
    
    .featured-product-info {
        padding: 15px;
    }
    
    .featured-product-name {
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 4px;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .featured-product-category {
        color: var(--gray-400);
        font-size: 12px;
        margin-bottom: 8px;
    }
    
    .featured-product-price {
        color: var(--primary);
        font-weight: 700;
        font-size: 15px;
    }
    
    @media (max-width: 768px) {
        .featured-product-card {
            width: 160px;
        }
        
        .featured-product-image {
            height: 110px;
        }
        
        .featured-carousel {
            animation-duration: 20s;
        }
    }
</style>

@endsection



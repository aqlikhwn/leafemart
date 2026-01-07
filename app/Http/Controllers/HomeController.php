<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Announcement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->active()
            ->featured()
            ->inStock()
            ->take(8)
            ->get();

        $categories = Category::withCount(['products' => function($q) {
            $q->active()->inStock();
        }])->get();
        
        $announcements = Announcement::active()
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('featuredProducts', 'categories', 'announcements'));
    }

    public function about()
    {
        return view('about');
    }

    public function faq()
    {
        $faqs = \App\Models\Faq::active()->ordered()->get()->groupBy('category');
        return view('faq', compact('faqs'));
    }
}

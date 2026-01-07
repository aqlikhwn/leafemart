<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        
        $faqs = Faq::ordered()->get()->groupBy('category');
        
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $this->checkAdmin();
        
        $categories = Faq::categories();
        
        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Faq::create([
            'category' => $request->category,
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('faq')->with('success', 'FAQ added successfully!');
    }

    public function edit($id)
    {
        $this->checkAdmin();
        
        $faq = Faq::findOrFail($id);
        $categories = Faq::categories();
        
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $faq = Faq::findOrFail($id);

        $faq->update([
            'category' => $request->category,
            'question' => $request->question,
            'answer' => $request->answer,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('faq')->with('success', 'FAQ updated successfully!');
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faq')->with('success', 'FAQ deleted successfully!');
    }
}

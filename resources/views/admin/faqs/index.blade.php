@extends('layouts.app')

@section('title', 'Manage FAQs')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-question-circle"></i> Manage FAQs</h1>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add FAQ
    </a>
</div>

@if($faqs->count() > 0)
    @foreach($faqs as $category => $categoryFaqs)
    <div class="card" style="margin-bottom: 20px;">
        <h3 style="color: var(--primary-dark); margin-bottom: 15px;">
            @switch($category)
                @case('Orders') <i class="fas fa-shopping-bag"></i> @break
                @case('Payment') <i class="fas fa-credit-card"></i> @break
                @case('Delivery') <i class="fas fa-truck"></i> @break
                @case('Account') <i class="fas fa-user"></i> @break
                @default <i class="fas fa-info-circle"></i>
            @endswitch
            {{ $category }}
        </h3>
        
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">Order</th>
                    <th>Question</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryFaqs as $faq)
                <tr>
                    <td>{{ $faq->sort_order }}</td>
                    <td>
                        <strong>{{ $faq->question }}</strong>
                        <div style="color: var(--gray-400); font-size: 13px; margin-top: 5px;">
                            {{ Str::limit($faq->answer, 100) }}
                        </div>
                    </td>
                    <td>
                        @if($faq->is_active)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-secondary">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-primary" style="padding: 8px 12px;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Delete this FAQ?');">
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
    </div>
    @endforeach
@else
<div class="card">
    <div class="empty-state">
        <i class="fas fa-question-circle"></i>
        <h3>No FAQs Yet</h3>
        <p>Add your first FAQ to help customers find answers.</p>
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary" style="margin-top: 15px;">
            <i class="fas fa-plus"></i> Add FAQ
        </a>
    </div>
</div>
@endif
@endsection

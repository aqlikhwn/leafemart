@extends('layouts.app')

@section('title', 'FAQ - Frequently Asked Questions')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-question-circle" style="color: var(--primary);"></i> Frequently Asked Questions</h1>
    @auth
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add FAQ
        </a>
        @endif
    @endauth
</div>

<div class="card">
    <p style="color: var(--gray-600); margin-bottom: 30px;">
        Find answers to common questions about shopping at Leafé Mart. If you can't find what you're looking for, feel free to <a href="{{ route('about') }}#contact" style="color: var(--primary);">contact us</a>.
    </p>

    @if($faqs->count() > 0)
        @foreach($faqs as $category => $categoryFaqs)
        <h3 style="color: var(--primary-dark); margin-bottom: 15px; {{ !$loop->first ? 'margin-top: 30px;' : '' }}">
            @switch($category)
                @case('Orders') <i class="fas fa-shopping-bag"></i> @break
                @case('Payment') <i class="fas fa-credit-card"></i> @break
                @case('Delivery') <i class="fas fa-truck"></i> @break
                @case('Account') <i class="fas fa-user"></i> @break
                @default <i class="fas fa-info-circle"></i>
            @endswitch
            {{ $category }}
        </h3>
        
        <div class="faq-accordion">
            @foreach($categoryFaqs as $faq)
            <div class="faq-item" style="margin-bottom: 15px;">
                <button class="faq-question" onclick="toggleFaq(this)" style="width: 100%; text-align: left; padding: 15px 20px; background: var(--gray-100); border: none; border-radius: 10px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: 600; color: var(--primary-dark);">
                    <span>{{ $faq->question }}</span>
                    <i class="fas fa-chevron-down" style="transition: transform 0.3s;"></i>
                </button>
                <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease; padding: 0 20px;">
                    <p style="padding: 15px 0; color: var(--gray-600); line-height: 1.8;">{!! nl2br(e($faq->answer)) !!}</p>
                    
                    @auth
                        @if(auth()->user()->isAdmin())
                        <div style="display: flex; gap: 10px; padding-bottom: 15px;">
                            <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Delete this FAQ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                        @endif
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    @else
    <div class="empty-state" style="padding: 40px;">
        <i class="fas fa-question-circle" style="font-size: 50px;"></i>
        <h3>No FAQs Available</h3>
        @auth
            @if(auth()->user()->isAdmin())
            <p>Add your first FAQ to help customers.</p>
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary" style="margin-top: 15px;">
                <i class="fas fa-plus"></i> Add FAQ
            </a>
            @else
            <p>FAQs will be added soon. Please check back later!</p>
            @endif
        @else
        <p>FAQs will be added soon. Please check back later!</p>
        @endauth
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleFaq(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('i');
    const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';
    
    // Close all other FAQs
    document.querySelectorAll('.faq-answer').forEach(a => {
        a.style.maxHeight = '0px';
    });
    document.querySelectorAll('.faq-question i').forEach(i => {
        i.style.transform = 'rotate(0deg)';
    });
    
    if (!isOpen) {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>
@endpush
@endsection

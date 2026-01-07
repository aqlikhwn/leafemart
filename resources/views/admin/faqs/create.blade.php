@extends('layouts.app')

@section('title', 'Add FAQ')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add FAQ</h1>
    <a href="{{ route('faq') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to FAQ
    </a>
</div>

<div class="card" style="max-width: 700px;">
    <form action="{{ route('admin.faqs.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category" class="form-control" required>
                @foreach($categories as $key => $value)
                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @error('category')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Question</label>
            <input type="text" name="question" class="form-control" value="{{ old('question') }}" placeholder="e.g., How do I place an order?" required>
            @error('question')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Answer</label>
            <textarea name="answer" class="form-control" rows="5" placeholder="Provide a detailed answer..." required>{{ old('answer') }}</textarea>
            @error('answer')
            <small style="color: var(--danger);">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                <small style="color: var(--gray-400);">Lower numbers appear first</small>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                    <label for="is_active" style="margin: 0;">Active (visible to users)</label>
                </div>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save FAQ
            </button>
        </div>
    </form>
</div>
@endsection

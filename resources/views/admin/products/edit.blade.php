@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Product</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="responsive-grid-2">
    <!-- Product Details -->
    <div class="card">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;">Product Details</h3>
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->icon }} {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Main Product Image</label>
                @if($product->image)
                <div style="margin-bottom: 10px; position: relative; display: inline-block;">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 150px; border-radius: 8px;">
                    <label style="position: absolute; top: -6px; right: -6px; width: 18px; height: 18px; background: var(--danger); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" title="Remove image">
                        <input type="checkbox" name="remove_image" value="1" style="display: none;" onchange="this.parentElement.parentElement.style.opacity = this.checked ? '0.3' : '1'">
                        <i class="fas fa-times"></i>
                    </label>
                </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*" style="padding: 8px;">
            </div>

            <div class="form-group">
                <label class="form-label">Additional Images</label>
                @if($product->images)
                @php $additionalImages = json_decode($product->images, true); @endphp
                @if(is_array($additionalImages) && count($additionalImages) > 0)
                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px;">
                    @foreach($additionalImages as $index => $img)
                    <div style="position: relative; display: inline-block;">
                        <img src="{{ asset('storage/' . $img) }}" alt="Additional Image" style="height: 80px; border-radius: 6px;">
                        <label style="position: absolute; top: -6px; right: -6px; width: 18px; height: 18px; background: var(--danger); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" title="Remove this image">
                            <input type="checkbox" name="remove_images[]" value="{{ $index }}" style="display: none;" onchange="this.parentElement.parentElement.style.opacity = this.checked ? '0.3' : '1'">
                            <i class="fas fa-times"></i>
                        </label>
                    </div>
                    @endforeach
                </div>
                @endif
                @endif
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="padding: 8px;">
                <small style="color: var(--gray-400);">Select new images to add. Max 2MB each.</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Base Price (RM) *</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Stock (for non-variation products)</label>
                    <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', $product->stock) }}">
                    @if($product->hasVariations())
                    <small style="color: var(--warning);">* Stock is managed per variation</small>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="6" placeholder="Enter detailed product description.">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Description Image (optional)</label>
                @if($product->description_image)
                <div style="position: relative; display: inline-block; margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $product->description_image) }}" alt="Description Image" style="max-width: 200px; max-height: 150px; border-radius: 8px; object-fit: cover;">
                    <label style="position: absolute; top: 5px; right: 5px; width: 18px; height: 18px; background: rgba(239,68,68,0.9); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px;">
                        <input type="checkbox" name="remove_description_image" value="1" style="display: none;" onchange="this.parentElement.parentElement.style.opacity = this.checked ? '0.4' : '1'">
                        <i class="fas fa-times"></i>
                    </label>
                </div>
                @endif
                <input type="file" name="description_image" class="form-control" accept="image/*">
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="featured" value="1" {{ $product->featured ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <span>Featured Product</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="active" value="1" {{ $product->active ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary);">
                    <span>Active</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Product
            </button>
        </form>
    </div>

    <!-- Product Variations -->
    <div>
        <div class="card" style="margin-bottom: 20px;">
            <h3 style="color: var(--primary-dark); margin-bottom: 20px;">
                <i class="fas fa-list"></i> Product Variations
            </h3>
            <p style="color: var(--gray-400); margin-bottom: 20px;">
                Add variations like colors, sizes, etc. Each variation has its own stock.
            </p>

            <!-- Existing Variations -->
            @if($product->variations->count() > 0)
            <div style="margin-bottom: 20px;">
                @foreach($product->variations as $variation)
                <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: var(--gray-100); border-radius: 10px; margin-bottom: 10px;">
                    @if($variation->image)
                    <img src="{{ asset('storage/' . $variation->image) }}" alt="{{ $variation->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                    @endif
                    <div style="flex: 1;">
                        <strong>{{ $variation->name }}</strong>
                        @if($variation->price)
                        <span style="color: var(--primary); margin-left: 8px;">
                            RM {{ number_format($variation->price, 2) }}
                        </span>
                        @endif
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span class="badge {{ $variation->stock > 10 ? 'badge-success' : ($variation->stock > 0 ? 'badge-warning' : 'badge-danger') }}">
                            {{ $variation->stock }} in stock
                        </span>
                        <span class="badge {{ $variation->active ? 'badge-success' : 'badge-danger' }}">
                            {{ $variation->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div style="display: flex; gap: 5px;">
                        <button type="button" class="btn btn-secondary" style="padding: 6px 10px;" 
                                onclick="editVariation({{ $variation->id }}, '{{ $variation->name }}', {{ $variation->price ?? 0 }}, {{ $variation->stock }}, {{ $variation->active ? 'true' : 'false' }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.variations.destroy', $variation->id) }}" method="POST" style="margin: 0;" 
                              onsubmit="return confirm('Delete this variation?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 6px 10px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-info" style="margin-bottom: 20px;">
                <i class="fas fa-info-circle"></i> No variations yet. Add one below!
            </div>
            @endif

            <!-- Add/Edit Variation Form -->
            <div style="background: var(--primary-light); padding: 20px; border-radius: 12px;">
                <h4 style="color: var(--primary-dark); margin-bottom: 15px;" id="variation-form-title">
                    <i class="fas fa-plus"></i> Add Variation
                </h4>
                <form action="{{ route('admin.variations.store') }}" method="POST" id="variation-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variation_id" id="variation_id" value="">

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label class="form-label">Variation Name *</label>
                            <input type="text" name="name" id="variation_name" class="form-control" placeholder="e.g., Blue, Large, 500ml" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label class="form-label">Stock *</label>
                            <input type="number" name="stock" id="variation_stock" class="form-control" min="0" value="0" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label class="form-label">Price (RM) *</label>
                            <input type="number" name="price" id="variation_price" class="form-control" step="0.01" min="0" value="{{ number_format($product->price, 2, '.', '') }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label class="form-label">Status</label>
                            <select name="active" id="variation_active" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label class="form-label">Variation Image (Optional)</label>
                        <input type="file" name="image" id="variation_image" class="form-control" accept="image/*" style="padding: 8px;">
                        <div id="current-variation-image" style="display: none; margin-top: 8px;"></div>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary" id="variation-submit-btn">
                            <i class="fas fa-plus"></i> Add Variation
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetVariationForm()" style="display: none;" id="cancel-edit-btn">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const updateRouteBase = '{{ url("admin/variations") }}';

function editVariation(id, name, priceAdjustment, stock, active) {
    document.getElementById('variation_id').value = id;
    document.getElementById('variation_name').value = name;
    document.getElementById('variation_price').value = priceAdjustment;
    document.getElementById('variation_stock').value = stock;
    document.getElementById('variation_active').value = active ? '1' : '0';
    
    document.getElementById('variation-form-title').innerHTML = '<i class="fas fa-edit"></i> Edit Variation';
    document.getElementById('variation-submit-btn').innerHTML = '<i class="fas fa-save"></i> Update Variation';
    document.getElementById('cancel-edit-btn').style.display = 'inline-flex';
    document.getElementById('variation-form').action = updateRouteBase + '/' + id;
    
    // Add PUT method
    let methodInput = document.getElementById('variation-method');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.id = 'variation-method';
        document.getElementById('variation-form').appendChild(methodInput);
    }
    methodInput.value = 'PUT';
    
    document.getElementById('variation_name').focus();
}

function resetVariationForm() {
    document.getElementById('variation_id').value = '';
    document.getElementById('variation_name').value = '';
    document.getElementById('variation_price').value = '0';
    document.getElementById('variation_stock').value = '0';
    document.getElementById('variation_active').value = '1';
    
    document.getElementById('variation-form-title').innerHTML = '<i class="fas fa-plus"></i> Add Variation';
    document.getElementById('variation-submit-btn').innerHTML = '<i class="fas fa-plus"></i> Add Variation';
    document.getElementById('cancel-edit-btn').style.display = 'none';
    document.getElementById('variation-form').action = '{{ route("admin.variations.store") }}';
    
    // Remove PUT method
    const methodInput = document.getElementById('variation-method');
    if (methodInput) {
        methodInput.remove();
    }
}
</script>
@endpush
@endsection

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VariationController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'active' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('variations', 'public');
        }

        ProductVariation::create([
            'product_id' => $request->product_id,
            'name' => $request->name,
            'image' => $imagePath,
            'price' => $request->price,
            'stock' => $request->stock,
            'active' => $request->active,
        ]);

        return back()->with('success', "Variation '{$request->name}' added successfully!");
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'active' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $variation = ProductVariation::findOrFail($id);

        $updateData = [
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
            'active' => $request->active,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($variation->image) {
                Storage::disk('public')->delete($variation->image);
            }
            $updateData['image'] = $request->file('image')->store('variations', 'public');
        }

        $variation->update($updateData);

        return back()->with('success', "Variation '{$request->name}' updated successfully!");
    }

    public function destroy($id)
    {
        $this->checkAdmin();

        $variation = ProductVariation::findOrFail($id);
        $name = $variation->name;
        
        // Delete image if exists
        if ($variation->image) {
            Storage::disk('public')->delete($variation->image);
        }
        
        $variation->delete();

        return back()->with('success', "Variation '{$name}' deleted successfully!");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('order')->get();
        return view('admin.offers.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Ensure the offers directory exists
                if (!Storage::disk('public')->exists('offers')) {
                    Storage::disk('public')->makeDirectory('offers');
                }
                $imagePath = $request->file('image')->store('offers', 'public');
                $validated['image'] = $imagePath;
            }

            $validated['is_active'] = $request->has('is_active') ? true : false;

            Offer::create($validated);

            return redirect()->route('admin.offers.index')
                ->with('success', 'تم إضافة العرض بنجاح');
        } catch (\Exception $e) {
            Log::error('Error creating offer: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة العرض: ' . $e->getMessage());
        }
    }

    public function show(Offer $offer)
    {
        return view('admin.offers.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('order')->get();
        return view('admin.offers.edit', compact('offer', 'categories'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category_slug' => 'nullable|string|max:100',
            'original_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Delete old image
                if ($offer->image) {
                    Storage::disk('public')->delete($offer->image);
                }
                // Ensure the offers directory exists
                if (!Storage::disk('public')->exists('offers')) {
                    Storage::disk('public')->makeDirectory('offers');
                }
                $imagePath = $request->file('image')->store('offers', 'public');
                $validated['image'] = $imagePath;
            }

            $validated['is_active'] = $request->has('is_active') ? true : false;

            // معالجة المواصفات
            $specifications = [];
            if ($request->has('specifications')) {
                foreach ($request->input('specifications', []) as $key => $value) {
                    if (!empty(trim($value))) {
                        $specifications[$key] = trim($value);
                    }
                }
            }
            $validated['specifications'] = !empty($specifications) ? $specifications : null;

            // معالجة المواصفات المخصصة
            $customSpecs = [];
            $customKeys = $request->input('custom_spec_keys', []);
            $customValues = $request->input('custom_spec_values', []);
            for ($i = 0; $i < count($customKeys); $i++) {
                if (!empty(trim($customKeys[$i])) && !empty(trim($customValues[$i]))) {
                    $customSpecs[trim($customKeys[$i])] = trim($customValues[$i]);
                }
            }
            $validated['custom_specifications'] = !empty($customSpecs) ? $customSpecs : null;

            $offer->update($validated);

            return redirect()->route('admin.offers.index')
                ->with('success', 'تم تحديث العرض بنجاح');
        } catch (\Exception $e) {
            Log::error('Error updating offer: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث العرض: ' . $e->getMessage());
        }
    }

    public function destroy(Offer $offer)
    {
        try {
            // Delete image if exists
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }

            $offer->delete();

            return redirect()->route('admin.offers.index')
                ->with('success', 'تم حذف العرض بنجاح');
        } catch (\Exception $e) {
            Log::error('Error deleting offer: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف العرض: ' . $e->getMessage());
        }
    }
}

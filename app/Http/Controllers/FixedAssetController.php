<?php

namespace App\Http\Controllers;

use App\Models\FixedAsset;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FixedAssetController extends Controller
{
    public function index()
    {
        $assets = FixedAsset::with('user')->latest()->get();
        return Inertia::render('FixedAssets/Index', [
            'assets' => $assets,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_name' => 'required|string|max:1024',
            'kst_symbol' => 'nullable|string|max:3',
            'initial_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'acquisition_document_type' => 'nullable|string|max:255',
            'depreciation_rate' => 'nullable|numeric',
            'status' => 'nullable|string|max:255',
            'acquisition_date' => 'nullable|date',
            'commissioning_date' => 'nullable|date',
            'liquidation_date' => 'nullable|date',
            'liquidation_reason' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);
        $asset = FixedAsset::create($validated);
        return redirect()->back()->with('success', 'Asset created successfully.');
    }

    public function update(Request $request, FixedAsset $fixedAsset)
    {
        $validated = $request->validate([
            'asset_name' => 'required|string|max:1024',
            'kst_symbol' => 'nullable|string|max:3',
            'initial_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'acquisition_document_type' => 'nullable|string|max:255',
            'depreciation_rate' => 'nullable|numeric',
            'status' => 'nullable|string|max:255',
            'acquisition_date' => 'nullable|date',
            'commissioning_date' => 'nullable|date',
            'liquidation_date' => 'nullable|date',
            'liquidation_reason' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);
        $fixedAsset->update($validated);
        return redirect()->back()->with('success', 'Asset updated successfully.');
    }

    public function destroy(FixedAsset $fixedAsset)
    {
        $fixedAsset->delete();
        return redirect()->back()->with('success', 'Asset deleted successfully.');
    }
}

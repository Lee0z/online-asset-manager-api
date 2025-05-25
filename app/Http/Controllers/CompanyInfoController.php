<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyInfoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
        ]);
        $company = CompanyInfo::create($validated);
        return redirect()->route('company-info.show', ['id' => $company->id]);
    }

    public function show($id)
    {
        $company = CompanyInfo::findOrFail($id);
        return Inertia::render('CompanyInfoDetails', ['company' => $company]);
    }

    public function updateDetails(Request $request, $id)
    {
        $validated = $request->validate([
            'company_tax_number' => 'nullable|string|max:10',
            'company_street' => 'nullable|string|max:255',
            'company_street_number' => 'nullable|string|max:255',
            'company_zip_code' => 'nullable|string|max:5',
            'company_city' => 'nullable|string|max:255',
            'company_state' => 'nullable|string|max:255',
            'company_country' => 'nullable|string|max:255',
        ]);
        $company = CompanyInfo::findOrFail($id);
        $company->update($validated);
        return redirect()->route('admin.setup');
    }
}

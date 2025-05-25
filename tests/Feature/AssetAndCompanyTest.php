<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use App\Models\User;
use App\Models\CompanyInfo;
use App\Models\FixedAsset;

it('can create and show company info', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->create(['is_admin' => true]);
    $this->actingAs($user);
    $response = $this->post('/company-info', [
        'company_name' => 'Test Company',
    ]);
    $response->assertRedirect();
    $company = CompanyInfo::first();
    expect($company)->not->toBeNull();
    expect($company->company_name)->toBe('Test Company');
    $show = $this->get('/company-info/' . $company->id);
    $show->assertInertia(fn (Assert $page) => $page->has('company'));
});

it('can update company details', function () {
    $user = User::factory()->create(['is_admin' => true]);
    $company = CompanyInfo::factory()->create();
    $this->actingAs($user);
    $response = $this->post('/company-info/' . $company->id . '/details', [
        'company_tax_number' => '1234567890',
        'company_street' => 'Testowa',
        'company_street_number' => '1',
        'company_zip_code' => '12345',
        'company_city' => 'Miasto',
        'company_state' => 'Województwo',
        'company_country' => 'Polska',
    ]);
    $response->assertRedirect();
    $company->refresh();
    expect($company->company_tax_number)->toBe('1234567890');
});

it('can create, update, and delete a fixed asset', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $create = $this->post('/fixed-assets', [
        'asset_name' => 'Komputer',
        'kst_symbol' => '123',
        'initial_value' => 1000,
        'current_value' => 900,
        'acquisition_document_type' => 'Faktura',
        'depreciation_rate' => 20,
        'status' => 'nabyty',
        'acquisition_date' => '2024-01-01',
        'commissioning_date' => '2024-01-02',
        'user_id' => $user->id,
    ]);
    $create->assertRedirect();
    $asset = FixedAsset::first();
    expect($asset)->not->toBeNull();
    expect($asset->asset_name)->toBe('Komputer');
    $update = $this->put('/fixed-assets/' . $asset->id, [
        'asset_name' => 'Laptop',
        'kst_symbol' => '123',
        'initial_value' => 1000,
        'current_value' => 800,
        'acquisition_document_type' => 'Faktura',
        'depreciation_rate' => 20,
        'status' => 'w użyciu',
        'acquisition_date' => '2024-01-01',
        'commissioning_date' => '2024-01-02',
        'user_id' => $user->id,
    ]);
    $update->assertRedirect();
    $asset->refresh();
    expect($asset->asset_name)->toBe('Laptop');
    $delete = $this->delete('/fixed-assets/' . $asset->id);
    $delete->assertRedirect();
    expect(FixedAsset::count())->toBe(0);
});

it('admin can create and delete users', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($admin);
    $create = $this->post('/users', [
        'name' => 'Jan Kowalski',
        'email' => 'jan@example.com',
        'password' => 'password123',
        'is_admin' => false,
    ]);
    $create->assertRedirect();
    $user = User::where('email', 'jan@example.com')->first();
    expect($user)->not->toBeNull();
    $delete = $this->delete('/users/' . $user->id);
    $delete->assertRedirect();
    expect(User::where('email', 'jan@example.com')->exists())->toBeFalse();
});

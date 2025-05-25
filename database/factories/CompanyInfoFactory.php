<?php

namespace Database\Factories;

use App\Models\CompanyInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyInfoFactory extends Factory
{
    protected $model = CompanyInfo::class;

    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company,
            'company_tax_number' => $this->faker->numerify('##########'),
            'company_street' => $this->faker->streetName,
            'company_street_number' => $this->faker->buildingNumber,
            'company_zip_code' => $this->faker->postcode,
            'company_city' => $this->faker->city,
            'company_state' => $this->faker->state,
            'company_country' => $this->faker->country,
        ];
    }
}

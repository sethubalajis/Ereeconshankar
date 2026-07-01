<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'code' => strtoupper(fake()->bothify('CMP###??')),
            'gst_number' => null,
            'pan_number' => null,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('9#########'),
            'website' => fake()->url(),
            'logo' => null,
            'address' => fake()->address(),
            'city_id' => null,
            'state_id' => null,
            'country_id' => null,
            'pin_code' => fake()->postcode(),
            'status' => true,
        ];
    }
}

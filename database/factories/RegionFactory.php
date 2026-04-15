<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RegionFactory extends Factory
{
    public function definition(): array
    {
        $regions = [
            'Jakarta', 'Bandung', 'Surabaya',
            'Makassar', 'Medan', 'Yogyakarta',
            'Semarang', 'Denpasar', 'Palembang',
        ];

        $name = \fake()->unique()->randomElement($regions);

        return [
            'name' => $name,
            'domain' => Str::slug($name) . '.darnusnews.com',
            'is_active' => true,
        ];
    }
}

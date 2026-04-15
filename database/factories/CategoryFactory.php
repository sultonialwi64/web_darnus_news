<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'Politik', 'Ekonomi', 'Olahraga', 'Kriminal',
            'Pendidikan', 'Kesehatan', 'Teknologi', 'Hiburan',
        ];

        $name = \fake()->unique()->randomElement($categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}

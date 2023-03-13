<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Structure>
 */
class StructureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Club de ' .$this->faker->words(2, true);
        $slug = Str::slug($name);

        return [
            'structuretype_id' => $this->faker->numberBetween(1, 4),
            'user_id' => $this->faker->numberBetween(1, 20),
            'category_id' => $this->faker->numberBetween(1, 10),
            'name' => ucwords($name),
            'firstname' => $this->faker->firstNameFemale(),
            'lastname' => $this->faker->lastName(),
            'slug' => $slug .'-'.$this->faker->numberBetween(1, 99),
            'description' => 'Notre ' . ucwords($name). ' spécialisé ' . $this->faker->realText(300),
            'website' => 'www.' .$slug. '.com',
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'zip_code' => $this->faker->postcode(),
            'country' => 'France',
            'address_lat' => $this->faker->latitude(41.59101, 51.03457),
            'address_lng' => $this->faker->longitude(-4.65, 9.45),
            // 'start_at' => $this->faker->date(),
            // 'end_at' => $this->faker->date(),
            // 'hour_start_at' => $this->faker->time(),
            // 'hour_end_at' => $this->faker->time(),
        ];
    }
}

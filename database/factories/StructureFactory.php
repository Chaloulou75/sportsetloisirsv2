<?php

namespace Database\Factories;

use App\Models\City;
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
            'name' => ucwords($name),
            'slug' => $slug .'-'.$this->faker->numberBetween(1, 99),
            // 'famille_id' => $this->faker->numberBetween(1, 10),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->randomElement(City::all())['ville'],
            'zip_code' => $this->faker->postcode(),
            'departement_id' => $this->faker->numberBetween(1, 83),
            'country' => 'France',
            'address_lat' => $this->faker->latitude(41.59101, 51.03457),
            'address_lng' => $this->faker->longitude(-4.65, 9.45),
            'website' => 'www.' .$slug. '.com',
            'email' => $this->faker->companyEmail(),
            'phone1' => $this->faker->phoneNumber(),
            'phone2' => $this->faker->phoneNumber(),
            'instagram' => $this->faker->word(),
            'facebook' => $this->faker->word(),
            'youtube' => $this->faker->word(),
            'presentation_courte' => 'Notre ' . ucwords($name). ' spécialisé ' . $this->faker->realText(300),
            // 'start_at' => $this->faker->date(),
            // 'end_at' => $this->faker->date(),
            // 'hour_start_at' => $this->faker->time(),
            // 'hour_end_at' => $this->faker->time(),
        ];
    }
}

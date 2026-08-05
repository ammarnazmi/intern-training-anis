<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static string|null $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = str(fake()->unique()->firstName())->append(' ', fake()->lastName())->value();

        return [
            'name' => $name,
            'email' => str($name)->slug()->append('@', fake()->safeEmailDomain())->value(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('abcd1234'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model's email address should be randomly verified.
     */
    public function randomlyVerified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => fake()->randomElement([null, now()]),
        ]);
    }
}

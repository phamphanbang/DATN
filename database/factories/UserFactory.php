<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => config('enum.user_roles.USER')
        ];
    }

    public function super_admin()
    {
        return $this->state(function () {
            return [
                'name' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'role' => config('enum.user_roles.SUPERADMIN')
            ];
        });
    }

    public function admin()
    {
        return $this->state(function () {
            return [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => config('enum.user_roles.ADMIN')
            ];
        });
    }
}

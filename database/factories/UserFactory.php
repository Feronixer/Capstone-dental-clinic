<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
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
    protected static ?string $password;
    protected $model = User::class;

    public function definition(): array
    {
        $firstName  = $this->faker->firstName();
        $middleName = $this->faker->optional()->firstName();
        $lastName   = $this->faker->lastName();

        return [
            'name'              => trim("{$firstName} {$middleName} {$lastName}"),
            'username'          => $this->faker->unique()->userName(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => null,
            'password'          => bcrypt('1234asdf'), // default
            'role_id'           => $this->faker->randomElement([2, 3]), // Staff or Patient
            'remember_token'    => Str::random(10),
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }

    /**
     * After creating a User, automatically create UserInfo.
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $nameParts = explode(' ', $user->name);

            $user->info()->create([
                'first_name'  => $nameParts[0] ?? $this->faker->firstName(),
                'middle_name' => $nameParts[1] ?? null,
                'last_name'   => $nameParts[2] ?? $this->faker->lastName(),
                'phone'       => $user->phone ?? '09' . $this->faker->numerify('#########'),
                'address'     => $this->faker->address(),
                'age'         => $this->faker->numberBetween(18, 60),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        });
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
     * Create a patient user.
     */
    public function patient(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => 3,
        ]);
    }

    /**
     * Create a staff user.
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => 2,
        ]);
    }
}

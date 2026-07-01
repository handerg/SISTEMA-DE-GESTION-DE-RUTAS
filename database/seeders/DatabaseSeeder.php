<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\ExampleDataSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate([
            'nombre_rol' => 'administrador',
        ]);

        $supervisorRole = Role::firstOrCreate([
            'nombre_rol' => 'supervisor',
        ]);

        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id_rol,
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Supervisor',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'role_id' => $supervisorRole->id_rol,
            'is_active' => true,
        ]);

        $this->call(ExampleDataSeeder::class);
        $this->call(AutoConductorSeeder::class);
    }
}

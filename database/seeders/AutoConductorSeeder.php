<?php

namespace Database\Seeders;

use App\Models\Auto;
use App\Models\Conductor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AutoConductorSeeder extends Seeder
{
    public function run(): void
    {
        // Limpia datos antiguos para evitar duplicados y deja espacio para la nueva generación.
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        Conductor::truncate();
        Auto::truncate();

        if ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $colors = ['Blanco', 'Negro', 'Rojo', 'Azul', 'Gris', 'Plateado', 'Verde', 'Amarillo', 'Naranja', 'Beige'];
        $brands = [
            ['Toyota', 'Hilux'],
            ['Nissan', 'Frontier'],
            ['Mitsubishi', 'L200'],
            ['Chevrolet', 'D-Max'],
            ['Ford', 'Ranger'],
            ['Volkswagen', 'Amarok'],
            ['Kia', 'Seltos'],
            ['Hyundai', 'Tucson'],
            ['Mazda', 'CX-5'],
            ['Renault', 'Duster'],
        ];
        $licenses = ['2da', '3ra', '4ta', '5ta'];
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $genders = ['M', 'F'];

        $autos = [];

        for ($i = 1; $i <= 50; $i++) {
            $brand = $brands[array_rand($brands)];
            $anio = rand(2015, 2025);
            $kilometraje = rand(10000, 250000);
            $license = $licenses[array_rand($licenses)];
            $placa = sprintf('AVT-%04d', 1000 + $i);
            $color = $colors[array_rand($colors)];

            $autos[] = Auto::create([
                'placa' => $placa,
                'marca' => $brand[0],
                'modelo' => $brand[1],
                'anio' => (string) $anio,
                'kilometraje' => number_format($kilometraje, 0, ',', '.'),
                'color' => $color,
                'required_license' => $license,
                'foto_perfil' => null,
            ]);
        }

        for ($i = 1; $i <= 50; $i++) {
            $auto = $autos[array_rand($autos)];
            $gender = $genders[array_rand($genders)];
            $name = $gender === 'M' ? 'Conductor ' . $i : 'Conductora ' . $i;
            $cedula = 'V' . rand(10000000, 99999999);

            Conductor::create([
                'nombre' => $name,
                'email' => "driver{$i}@example.com",
                'cedula' => $cedula,
                'telefono' => '+58 ' . rand(400, 499) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'edad' => rand(23, 60),
                'sexo' => $gender,
                'tipo_sangre' => $bloodTypes[array_rand($bloodTypes)],
                'tipo_licencia' => $licenses[array_rand($licenses)],
                'auto_id' => $auto->id,
                'is_active' => (bool) rand(0, 1),
                'foto_perfil' => null,
            ]);
        }
    }
}

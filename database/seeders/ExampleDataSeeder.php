<?php

namespace Database\Seeders;

use App\Models\Auto;
use App\Models\Conductor;
use App\Models\HistorialRuta;
use App\Models\HistorialServicio;
use Illuminate\Database\Seeder;

class ExampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $autos = [
            Auto::firstOrCreate([
                'placa' => 'AVT-1001',
            ], [
                'marca' => 'Toyota',
                'modelo' => 'Hilux',
                'anio' => '2021',
                'foto_perfil' => null,
                'required_license' => '3ra',
            ]),
            Auto::firstOrCreate([
                'placa' => 'AVT-1002',
            ], [
                'marca' => 'Nissan',
                'modelo' => 'Frontier',
                'anio' => '2022',
                'foto_perfil' => null,
                'required_license' => '3ra',
            ]),
            Auto::firstOrCreate([
                'placa' => 'AVT-1003',
            ], [
                'marca' => 'Mitsubishi',
                'modelo' => 'L200',
                'anio' => '2020',
                'foto_perfil' => null,
                'required_license' => '3ra',
            ]),
        ];

        $conductores = [
            Conductor::firstOrCreate([
                'cedula' => 'V12345678',
            ], [
                'nombre' => 'Carlos Rojas',
                'telefono' => '+34 612 345 678',
                'edad' => 38,
                'sexo' => 'Masculino',
                'tipo_sangre' => 'O+',
                'tipo_licencia' => 'B',
                'auto_id' => $autos[0]->id,
                'is_active' => true,
                'foto_perfil' => null,
            ]),
            Conductor::firstOrCreate([
                'cedula' => 'V87654321',
            ], [
                'nombre' => 'Lucía Fernández',
                'telefono' => '+34 698 765 432',
                'edad' => 31,
                'sexo' => 'Femenino',
                'tipo_sangre' => 'A-',
                'tipo_licencia' => 'B',
                'auto_id' => $autos[1]->id,
                'is_active' => true,
                'foto_perfil' => null,
            ]),
            Conductor::firstOrCreate([
                'cedula' => 'V23456789',
            ], [
                'nombre' => 'Miguel Sánchez',
                'telefono' => '+34 622 478 901',
                'edad' => 44,
                'sexo' => 'Masculino',
                'tipo_sangre' => 'B+',
                'tipo_licencia' => 'C',
                'auto_id' => null,
                'is_active' => false,
                'foto_perfil' => null,
            ]),
        ];

        HistorialRuta::firstOrCreate([
            'conductor_id' => $conductores[0]->id,
            'titulo' => 'Ruta puerto-centro',
        ], [
            'detalle' => 'Entrega de documentación en el centro de operaciones.',
            'distancia' => '18 km',
        ]);

        HistorialRuta::firstOrCreate([
            'conductor_id' => $conductores[0]->id,
            'titulo' => 'Ruta almacén',
        ], [
            'detalle' => 'Traslado de carga ligera hacia almacén sur.',
            'distancia' => '26 km',
        ]);

        HistorialRuta::firstOrCreate([
            'conductor_id' => $conductores[1]->id,
            'titulo' => 'Ruta inspección',
        ], [
            'detalle' => 'Verificación de condiciones del vehículo y ruta segura.',
            'distancia' => '12 km',
        ]);

        HistorialServicio::firstOrCreate([
            'conductor_id' => $conductores[0]->id,
            'titulo' => 'Servicio de emergencia',
        ], [
            'detalle' => 'Activo en apoyo a maniobras de descarga urgente.',
        ]);

        HistorialServicio::firstOrCreate([
            'conductor_id' => $conductores[1]->id,
            'titulo' => 'Mantenimiento preventivo',
        ], [
            'detalle' => 'Parada para revisión de frenos y aceite.',
        ]);
    }
}

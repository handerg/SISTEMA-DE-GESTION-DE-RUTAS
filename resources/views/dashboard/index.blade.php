@extends('layouts.app')

@section('content')
    <section class="panel">
        <article class="card">
            <h2>Resumen</h2>
            <p>Este es el panel inicial del sistema. Aprovecha las secciones del menú para navegar a los diferentes módulos.</p>
        </article>
        <article class="card">
            <h2>Acceso rápido</h2>
            <p>Selecciona desde la barra lateral para ver autos, conductores, reportes o ajustes de configuración.</p>
        </article>
        <article class="card">
            <h2>Estado</h2>
            <div class="stats">
                <div class="stat-box">
                    <strong>24</strong>
                    <span>Autos registrados</span>
                </div>
                <div class="stat-box">
                    <strong>15</strong>
                    <span>Conductores activos</span>
                </div>
                <div class="stat-box">
                    <strong>3</strong>
                    <span>Reportes pendientes</span>
                </div>
            </div>
        </article>
    </section>
@endsection

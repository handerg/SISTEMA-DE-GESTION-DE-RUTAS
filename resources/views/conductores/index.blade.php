@extends('layouts.app')

@section('content')
    <div class="section-card">
        <div class="section-header">
            <div>
                <h2>Lista de Conductores</h2>
                <p>Administre los conductores registrados junto con su información.</p>
            </div>
            <button type="button" class="button-primary" id="toggle-new-conductor">Agregar conductor</button>
        </div>

        @if(session('success'))
            <div class="card" style="margin-top:1rem; background: #ecfdf5; border-color: #22c55e; color: #166534;">
                {{ session('success') }}
            </div>
        @endif

        <div class="filter-panel card">
            <form id="conductor-filter-form" method="GET" action="{{ route('conductores', [], false) }}" class="filter-form">
                <div class="input-group filter-group">
                    <label for="search">Buscar</label>
                    <input id="search" name="search" type="text" value="{{ $search ?? '' }}" placeholder="Nombre, cédula, teléfono o placa" autocomplete="off" class="filter-input" />
                </div>
                <div class="input-group filter-group">
                    <label for="status">Estado</label>
                    <select id="status" name="status" class="filter-input">
                        <option value="" {{ empty($status) ? 'selected' : '' }}>Todos</option>
                        <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Bloqueados</option>
                    </select>
                </div>
                <div class="input-group filter-group">
                    <label for="tipo_licencia">Tipo de licencia</label>
                    <select id="tipo_licencia" name="tipo_licencia" class="filter-input">
                        <option value="" {{ empty($tipo_licencia) ? 'selected' : '' }}>Todos</option>
                        <option value="2da" {{ ($tipo_licencia ?? '') === '2da' ? 'selected' : '' }}>2da</option>
                        <option value="3ra" {{ ($tipo_licencia ?? '') === '3ra' ? 'selected' : '' }}>3ra</option>
                        <option value="4ta" {{ ($tipo_licencia ?? '') === '4ta' ? 'selected' : '' }}>4ta</option>
                        <option value="5ta" {{ ($tipo_licencia ?? '') === '5ta' ? 'selected' : '' }}>5ta</option>
                    </select>
                </div>
                <div class="input-group filter-group">
                    <label for="auto_id">Vehículo</label>
                    <select id="auto_id" name="auto_id" class="filter-input">
                        <option value="" {{ empty($auto_id) ? 'selected' : '' }}>Todos</option>
                        @foreach($autos as $auto)
                            <option value="{{ $auto->id }}" {{ ($auto_id ?? '') == $auto->id ? 'selected' : '' }}>{{ $auto->placa ?? 'Auto '.$auto->id }} - {{ $auto->marca ?? '' }} {{ $auto->modelo ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="button-secondary filter-button" id="clear-filters">Limpiar filtros</button>
            </form>
        </div>

        <div class="table-wrapper" style="margin-top:1rem;">
            <div class="table-shell">
                <table class="table-list conductores-table">
                    <thead>
                        <tr>
                            <th>Perfil</th>
                            <th>Nombre</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th>Vehículo asignado</th>
                            <th>Placa</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conductores as $conductor)
                            @php
                                $historialRutas = $conductor->historialRutas->map(function ($item) {
                                    return [
                                        'titulo' => $item->titulo ?? 'Ruta #' . $item->id,
                                        'fecha' => $item->created_at?->format('Y-m-d') ?? 'Sin fecha',
                                        'detalle' => $item->detalle ?? 'No registra detalles',
                                        'distancia' => $item->distancia ?? '-',
                                    ];
                                });

                                $historialServicios = $conductor->historialServicios->map(function ($item) {
                                    return [
                                        'titulo' => $item->titulo ?? 'Servicio #' . $item->id,
                                        'fecha' => $item->created_at?->format('Y-m-d') ?? 'Sin fecha',
                                        'detalle' => $item->detalle ?? 'No registra detalles',
                                    ];
                                });
                            @endphp
                            <tr class="clickable-row" data-id="{{ $conductor->id }}" data-update-url="{{ route('conductores.update', $conductor, [], false) }}" data-delete-url="{{ route('conductores.destroy', $conductor, [], false) }}"
                                data-nombre="{{ $conductor->nombre }}"
                                data-cedula="{{ $conductor->cedula }}"
                                data-telefono="{{ $conductor->telefono }}"
                                data-edad="{{ $conductor->edad ?? '' }}"
                                data-sexo="{{ $conductor->sexo ?? '' }}"
                                data-tipo-sangre="{{ $conductor->tipo_sangre ?? '' }}"
                                data-tipo-licencia="{{ $conductor->tipo_licencia ?? '' }}"
                                data-auto="{{ $conductor->auto?->marca ? $conductor->auto->marca . ' ' . $conductor->auto->modelo : 'Sin vehículo' }}"
                                data-status="{{ $conductor->is_active ? 'Activo' : 'Bloqueado' }}"
                                data-foto="{{ $conductor->foto_perfil ? \Illuminate\Support\Facades\Storage::url($conductor->foto_perfil) : '' }}"
                                data-auto-id="{{ $conductor->auto_id }}"
                                data-is-active="{{ $conductor->is_active ? '1' : '0' }}"
                                data-historial-rutas='@json($historialRutas)'
                                data-historial-servicios='@json($historialServicios)'
                            >
                                <td>
                                    <div class="avatar-cell">
                                        @if($conductor->foto_perfil)
                                            <img src="{{ Storage::url($conductor->foto_perfil) }}" alt="Foto de perfil">
                                        @else
                                            <span>Sin foto</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $conductor->nombre ?? 'Sin nombre' }}</td>
                                <td>{{ $conductor->cedula ?? 'Sin cédula' }}</td>
                                <td>{{ $conductor->telefono ?? 'Sin teléfono' }}</td>
                                <td>{{ $conductor->auto?->marca ? $conductor->auto->marca . ' ' . $conductor->auto->modelo : 'Sin vehículo' }}</td>
                                <td>{{ $conductor->auto?->placa ?? 'Sin placa' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('conductores.update', $conductor, [], false) }}" class="status-form" style="margin:0;">
                                        @csrf
                                        @method('PUT')
                                        <select name="is_active" class="status-select" style="border:1px solid #cbd5e1; border-radius:0.75rem; padding:0.65rem 0.85rem; width:100%; background:#fff; color:#0f172a;">
                                            <option value="1" {{ $conductor->is_active ? 'selected' : '' }}>Activo</option>
                                            <option value="0" {{ !$conductor->is_active ? 'selected' : '' }}>Bloqueado</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding:1.5rem; text-align:center; color:#334155;">No hay conductores registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="new-conductor-modal" style="display:none;">
        <div class="modal-card modal-card-form bg-white shadow-2xl rounded-[1.75rem] overflow-hidden max-w-4xl w-full">
            <button type="button" class="modal-close" id="close-new-modal">×</button>

            <form method="POST" action="{{ route('conductores.store', [], false) }}" enctype="multipart/form-data" id="new-conductor-form" class="new-conductor-form">
                @csrf

                <div class="p-8 new-modal-grid">
                    <div class="photo-panel">
                        <div>
                            <h3 class="text-2xl font-semibold text-slate-900">Agregar conductor</h3>
                            <p class="mt-2 text-sm text-slate-500">Completa la información personal y asigna un vehículo.</p>
                        </div>

                        <div>
                            <label for="new-foto_perfil" class="block text-sm font-medium text-slate-700 mb-2">Foto de perfil</label>
                            <label for="new-foto_perfil" id="new-dropzone" class="photo-dropzone border-2 border-dashed border-slate-300 rounded-[1.5rem] flex flex-col items-center justify-center gap-3 text-slate-400 hover:border-blue-500 hover:text-blue-500 transition cursor-pointer bg-slate-50">
                                <svg id="dropzone-icon" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div class="photo-preview" id="new-photo-preview">
                                    <span>Arrastra la imagen aquí o haz click</span>
                                </div>
                                <video id="camera-video" autoplay playsinline style="display:none; position:absolute; inset:0; width:100%; height:100%; object-fit:cover; border-radius:1.5rem; z-index:10;"></video>
                                <input id="new-foto_perfil" name="foto_perfil" type="file" accept="image/*" class="photo-input sr-only" />
                            </label>
                            <button type="button" id="start-camera" class="button-secondary" style="margin-top:0.75rem; width:100%;">Tomar foto con cámara</button>
                            <div id="camera-panel" class="camera-panel" style="display:none;">
                                <div class="camera-controls">
                                    <span id="camera-countdown" class="countdown-text"></span>
                                    <div class="camera-button-row">
                                        <button type="button" id="capture-photo" class="button-primary">Tomar</button>
                                        <button type="button" id="accept-photo" class="button-secondary" disabled>Aceptar</button>
                                        <button type="button" id="cancel-camera" class="button-secondary">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-slate-500">La imagen será usada en la lista de conductores.</p>
                    </div>

                    <div class="fields-panel">
                        <div class="field-row">
                            <div class="input-group">
                                <label for="new-nombre">Nombre completo</label>
                                <input id="new-nombre" name="nombre" type="text" class="new-input" placeholder="Ej. Juan Pérez" required />
                            </div>
                            <div class="input-group">
                                <label for="new-cedula">Cédula de identidad</label>
                                <input id="new-cedula" name="cedula" type="text" class="new-input" placeholder="V-00000000" required />
                            </div>
                        </div>

                        <div class="field-row-three">
                            <div class="input-group">
                                <label for="new-edad">Edad</label>
                                <input id="new-edad" name="edad" type="number" min="18" class="new-input" />
                            </div>
                            <div class="input-group">
                                <label for="new-sexo">Sexo</label>
                                <select id="new-sexo" name="sexo" class="new-input">
                                    <option value="">Seleccionar</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label for="new-tipo_sangre">Sangre</label>
                                <select id="new-tipo_sangre" name="tipo_sangre" class="new-input">
                                    <option value="">Seleccionar</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="input-group">
                                <label for="new-tipo_licencia">Tipo de licencia</label>
                                <select id="new-tipo_licencia" name="tipo_licencia" class="new-input">
                                    <option value="">Seleccionar</option>
                                    <option value="2da">2da</option>
                                    <option value="3ra">3ra</option>
                                    <option value="4ta">4ta</option>
                                    <option value="5ta">5ta</option>
                                </select>
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="input-group">
                                <label for="new-telefono">Teléfono</label>
                                <input id="new-telefono" name="telefono" type="tel" class="new-input" placeholder="Ej. +58 412 345 6789" />
                            </div>
                            <div class="input-group">
                                <label for="new-auto_id">Vehículo asignado</label>
                                <select id="new-auto_id" name="auto_id" class="new-input">
                                    <option value="">Sin asignar</option>
                                    @foreach($autos as $auto)
                                        @php
                                            $requiredLicense = $auto->required_license ?? '2da';
                                        @endphp
                                        <option value="{{ $auto->id }}" data-required-license="{{ $requiredLicense }}">{{ $auto->placa ?? 'Auto '.$auto->id }} {{ $auto->marca ?? '' }} {{ $auto->modelo ?? '' }} (Licencia mínima: {{ strtoupper($requiredLicense) }})</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-slate-500 mt-2" id="new-vehicle-warning">Selecciona una licencia para ver vehículos compatibles.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 border-t border-slate-200 flex justify-end gap-3 bg-slate-50">
                    <button type="button" class="px-6 py-2.5 text-slate-600 font-medium rounded-xl hover:bg-slate-100 transition" id="cancel-new-modal">Cancelar</button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">Guardar Conductor</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="conductores-modal" style="display:none;">
        <div class="modal-card detail-modal-card">
            <button type="button" class="modal-close" id="close-modal">×</button>

            <div class="detail-header">
                <div class="detail-profile">
                    <div class="detail-avatar" id="modal-photo"></div>
                    <div>
                        <h2 class="detail-name" id="modal-name"></h2>
                        <span class="status-badge" id="modal-status"></span>
                    </div>
                </div>
                <div class="detail-actions">
                    <button type="button" class="button-secondary" id="toggle-edit">Editar</button>
                    <button type="submit" class="button-primary" id="save-changes" style="display:none;">Guardar cambios</button>
                    <button type="button" class="button-danger" id="delete-conductor">Eliminar</button>
                </div>
            </div>

            <div class="detail-grid">
                <form method="POST" action="" enctype="multipart/form-data" class="modal-summary edit-summary" id="modal-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="info-grid">
                        <div class="info-card">
                            <p class="info-label">Edad</p>
                            <p class="info-value field-value" id="modal-age">--</p>
                            <input id="edit-edad" name="edad" type="number" min="18" class="inline-input" placeholder="Ingresar edad" />
                        </div>
                        <div class="info-card">
                            <p class="info-label">Sexo</p>
                            <p class="info-value field-value" id="modal-sex">--</p>
                            <select id="edit-sexo" name="sexo" class="inline-input">
                                <option value="">Seleccionar</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="O">Otro</option>
                            </select>
                        </div>
                        <div class="info-card">
                            <p class="info-label">Tipo de Sangre</p>
                            <p class="info-value field-value" id="modal-blood">--</p>
                            <select id="edit-tipo_sangre" name="tipo_sangre" class="inline-input">
                                <option value="">Seleccionar</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="info-card">
                            <p class="info-label">Licencia</p>
                            <p class="info-value field-value" id="modal-license">--</p>
                            <select id="edit-tipo_licencia" name="tipo_licencia" class="inline-input">
                                <option value="">Seleccionar</option>
                                <option value="2da">2da</option>
                                <option value="3ra">3ra</option>
                                <option value="4ta">4ta</option>
                                <option value="5ta">5ta</option>
                            </select>
                        </div>
                    </div>

                    <div class="info-grid info-grid-compact">
                        <div class="info-card">
                            <p class="info-label">Cédula</p>
                            <p class="info-value field-value" id="modal-cedula">--</p>
                            <input id="edit-cedula" name="cedula" type="text" class="inline-input" placeholder="Ingresar cédula" />
                        </div>
                        <div class="info-card">
                            <p class="info-label">Teléfono</p>
                            <p class="info-value field-value" id="modal-phone">--</p>
                            <input id="edit-telefono" name="telefono" type="text" class="inline-input" placeholder="Ingresar teléfono" />
                        </div>
                        <div class="info-card info-card-vehicle">
                            <p class="info-label">Vehículo Asignado</p>
                            <p class="info-value field-value" id="modal-auto">--</p>
                            <select id="edit-auto_id" name="auto_id" class="inline-input">
                                <option value="">Sin asignar</option>
                                @foreach($autos as $auto)
                                    <option value="{{ $auto->id }}">{{ $auto->placa ?? 'Auto '.$auto->id }} {{ $auto->marca ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </form>
            </div>

            <div class="activity-panel">
                <h4>Actividad reciente</h4>
                <div class="activity-empty">
                    <p id="modal-history">No hay actividad registrada.</p>
                </div>
                <div class="history-table-shell" style="display:none;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Detalle</th>
                                <th>Distancia</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="modal-history-table"></tbody>
                    </table>
                </div>
            </div>

            <form id="modal-delete-form" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <style>
        .detail-modal-card { max-width: 820px; padding: 0; border: none; }
        .detail-header { display:flex; justify-content:space-between; gap:1rem; align-items:center; padding:1.5rem 1.75rem; border-bottom:1px solid rgba(22,128,167,0.12); background:#f8fbff; }
        .detail-profile { display:flex; gap:1rem; align-items:center; }
        .detail-avatar { width:88px; height:88px; border-radius:1rem; background:#dbeafe; display:grid; place-items:center; color:#1d4ed8; font-weight:700; font-size:1.25rem; overflow:hidden; }
        .detail-name { margin:0; font-size:1.5rem; color:#0f172a; }
        .status-badge { display:inline-flex; align-items:center; gap:0.45rem; border-radius:9999px; padding:0.45rem 0.85rem; font-size:0.8rem; font-weight:600; color:#166534; background:#dcfce7; }
        .detail-actions { display:flex; gap:0.75rem; flex-wrap:wrap; }
        .detail-actions .button-secondary, .detail-actions .button-danger, .detail-actions .button-primary { min-width:100px; }
        .detail-grid { padding:1.75rem; background:#ffffff; }
        .info-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:1rem; }
        .info-grid-compact { grid-template-columns:repeat(3,minmax(0,1fr)); margin-top:1rem; }
        .info-card { padding:1.15rem; border-radius:1.25rem; background:#f8fbff; border:1px solid #a7d8f2; }
        .info-card-vehicle { grid-column: span 3 / span 3; }
        .info-label { margin:0 0 0.5rem; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:#475569; }
        .info-value { margin:0; font-size:1.2rem; font-weight:600; color:#0f172a; }
        .detail-actions .button-primary { background:#2563eb; color:#fff; }
        .detail-actions .button-secondary { background:#eaf4ff; color:#071933; border:1px solid #1680A7; }
        .detail-actions .button-danger { background:#fee2e2; color:#b91c1c; }
        .activity-panel { padding:1.5rem 1.75rem 2rem; background:#f8fbff; border-top:1px solid rgba(22,128,167,0.12); }
        .activity-panel h4 { margin:0 0 1rem; font-size:1rem; color:#0f172a; }
        .activity-empty { padding:2rem; border:1px dashed #cbd5e1; border-radius:1.25rem; text-align:center; background:#ffffff; color:#64748b; }
        .history-table-shell { overflow-x:auto; margin-top:1rem; }
        .history-table { width:100%; border-collapse:collapse; min-width:640px; background:#ffffff; border-radius:1.25rem; overflow:hidden; }
        .history-table th, .history-table td { padding:1rem 1.25rem; text-align:left; border-bottom:1px solid rgba(22,128,167,0.12); color:#071933; }
        .history-table th { color:#165a8a; font-weight:600; background:#f8fbff; position:sticky; top:0; z-index:10; }
        .history-table tbody tr:hover { background: rgba(59,130,246,0.06); }

        @media (max-width: 880px) {
            .detail-header { flex-direction:column; align-items:flex-start; }
            .detail-actions { width:100%; justify-content:flex-start; }
            .info-grid, .info-grid-compact { grid-template-columns:1fr; }
            .info-card-vehicle { grid-column: auto; }
        }

        .table-shell { max-height: 560px; overflow-y: auto; overflow-x: auto; background: transparent; border-radius: 1.75rem; position: relative; box-shadow: none; padding: 0; }
        .table-shell::-webkit-scrollbar { width: 0; height: 0; }
        .table-shell { -ms-overflow-style: none; scrollbar-width: none; }
        .conductores-table { width: 100%; border-collapse: separate; border-spacing: 0 0.85rem; min-width: 720px; margin-top: 0; }
        .conductores-table thead { display: table-header-group; }
        .conductores-table thead tr { position: sticky; top: 0 !important; z-index: 20; }
        .conductores-table thead th { position: sticky; top: 0 !important; z-index: 21 !important; background: #ffffff !important; background-clip: padding-box; color:#0f172a !important; font-weight:700; text-transform: uppercase; letter-spacing:0.08em; border-bottom:2px solid rgba(56, 189, 248, 0.35); padding: 1.25rem 1.4rem; }
        .conductores-table thead th:first-child { border-top-left-radius: 1.25rem; }
        .conductores-table thead th:last-child { border-top-right-radius: 1.25rem; }
        .conductores-table th, .conductores-table td { padding: 1.25rem 1.4rem; border: none; text-align:left; color:#0f172a; }
        .conductores-table tbody tr { background: #ffffff; box-shadow: 0 10px 20px rgba(15, 23, 42, 0.06); border-radius: 1.25rem; transition: transform .2s ease, box-shadow .2s ease; }
        .conductores-table tbody tr:hover { background: rgba(59,130,246,0.08); transform: translateY(-1px); }
        .conductores-table tbody tr:nth-child(odd) { background: rgba(248, 250, 252, 0.92); }
        .conductores-table tbody tr:last-child td { border-bottom: none; }
        .conductores-table td:first-child, .conductores-table th:first-child { padding-left: 1.6rem; }
        .conductores-table td:last-child, .conductores-table th:last-child { padding-right: 1.6rem; }
        .conductores-table th { vertical-align: middle; }
        .conductores-table .clickable-row { cursor:pointer; }
        .conductores-table .avatar-cell { width:64px; height:64px; border-radius:0.75rem; overflow:hidden; display:grid; place-items:center; background:#dbeafe; border:1px solid #60a5fa; color:#1d4ed8; }
        .conductores-table .avatar-cell img { width:100%; height:100%; object-fit:cover; }
        .modal-overlay { position: fixed; inset:0; z-index:50; background: rgba(0,0,0,0.65); display:grid; place-items:center; padding:1.5rem; }
        .modal-card { width:min(900px,100%); max-height:calc(100vh - 3rem); overflow-y:auto; background:#ffffff; border:1px solid #1680A7; border-radius:1.5rem; padding:1.75rem; position:relative; }
        .modal-card-form { max-width: 920px; }
        .modal-close { position:absolute; top:1rem; right:1rem; border:none; background:transparent; color:#071933; font-size:1.5rem; cursor:pointer; }
        .new-conductor-form { display:grid; gap:1.25rem; }
        .modal-form-top { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; }
        .modal-form-top h3 { margin:0; font-size:1.5rem; color:#071933; }
        .modal-description { margin:0.25rem 0 0; color:#334155; font-size:0.95rem; }
        .new-modal-grid { display:grid; gap:1.25rem; grid-template-columns: 1fr 1.2fr; }
        .photo-panel { display:grid; gap:0.75rem; }
        .photo-panel label { color:#334155; font-weight:600; }
        .photo-help { margin:0; color:#64748b; font-size:0.9rem; }
        .section-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; padding-bottom:1rem; border-bottom:1px solid rgba(22,128,167,0.12); margin-bottom:1rem; }
        .section-header h2 { margin:0; font-size:1.75rem; color:#0f172a; }
        .section-header p { margin:0.35rem 0 0; color:#475569; }
        .filter-panel { margin-top:0rem; display:grid; gap:0.75rem; width:100%; padding:0; border-radius:0; background:transparent; border:none; }
        .filter-form { display:grid; gap:0.75rem; grid-template-columns: minmax(400px, 1.8fr) minmax(90px, 0.95fr) minmax(90px, 0.95fr) minmax(90px, 0.95fr) minmax(90px, 1fr); align-items:end; width:100%; }
        .filter-group { padding:0; border-radius:0; background:transparent; border:none; }
        .filter-form label { margin-bottom:0.35rem; color:#334155; display:block; font-size:0.95rem; }
        .filter-input { width:100%; height:3rem; padding:0 0.85rem; border:1px solid #cbd5e1; border-radius:1rem; background:#fff; color:#0f172a; }
        .filter-button { min-width:110px; padding:0 1rem; height:3rem; align-self:center; }
        .photo-dropzone { height: 280px; max-width: 100%; border: 2px dashed #1680A7; border-radius: 1.5rem; display:grid; place-items:center; position:relative; cursor:pointer; background: #f8fbff; overflow: hidden;}
        .photo-dropzone.dragover { background: rgba(22,128,167,0.08); }
        .photo-preview { width: 100%; height: 100%; display:grid; place-items:center; padding: 1rem; text-align:center; color:#334155; }
        .photo-preview img { max-width: 100%; max-height: 100%; object-fit:contain; border-radius: 1rem; }
        .photo-input { position:absolute; inset:0; width:100%; height:100%; opacity:0; cursor:pointer; }
        .camera-panel { margin-top: 1rem; display: grid; gap: 0.75rem; border-radius: 1rem; border: 1px solid #cbd5e1; background: #f8fafc; padding: 0.75rem; }
        .camera-video { width: 100%; min-height: 240px; border-radius: 1rem; background: #0f172a; object-fit: cover; }
        .camera-controls { display: grid; gap:0.75rem; }
        .camera-button-row { display:flex; flex-wrap:wrap; gap:0.75rem; }
        .camera-countdown { display:block; text-align:center; font-size:1.1rem; color:#1d4ed8; font-weight:700; min-height:1.35rem; }
        .fields-panel { display:grid; gap:1.25rem; }
        .field-row { display:grid; grid-template-columns: 1fr 1fr; gap:1rem; }
        .field-row-three { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:1rem; }
        .input-group label { display:block; margin-bottom:0.5rem; color:#334155; font-size:0.95rem; }
        .input-group input, .input-group select { width:100%; padding:0.95rem 1rem; border:1px solid #cbd5e1; border-radius:1rem; background:#fff; color:#0f172a; }
        .new-input { width:100%; min-height:3rem; border-radius:1rem; }
        .new-modal-grid { display:grid; gap:2rem; grid-template-columns: minmax(240px, 1fr) minmax(420px, 1.5fr); align-items:start; }
        @media (max-width: 980px) {
            .new-modal-grid { grid-template-columns: 1fr; }
            .field-row, .field-row-three { grid-template-columns: 1fr; }
        }
        .profile-grid { display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap:1rem; }
        .contact-grid { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:1rem; }

        .profile-grid div, .contact-grid div { background:#f8fbff; padding:0.95rem 1rem; border-radius:1.25rem; border:1px solid #a7d8f2; }
        .profile-grid label, .contact-grid label { display:block; color:#334155; margin-bottom:0.5rem; font-size:0.95rem; }
        .new-input { width:100%; min-height:3rem; padding:0.9rem 1rem; border:1px solid #1680A7; border-radius:1rem; background:#ffffff; color:#071933; }
        .new-input select { appearance:none; }
        .vehicle-warning { margin:0.75rem 0 0; font-size:0.9rem; color:#334155; }
        .button-primary, .button-secondary, .button-danger { border:none; border-radius:1rem; padding:.95rem 1.25rem; cursor:pointer; }
        .button-primary { background:#2563eb; color:#fff; }
        .button-primary:hover { background:#1d4ed8; }
        .button-secondary { background:#eaf4ff; color:#071933; border:1px solid #1680A7; }
        .button-secondary:hover { background:#d7eefd; }
        .button-danger { background:#dc2626; color:#fff; }
        .button-danger:hover { background:#b91c1c; }
        @media (max-width: 940px) {
            .new-modal-grid { grid-template-columns: 1fr; }
            .profile-grid, .contact-grid { grid-template-columns: 1fr; }
            .modal-form-top { align-items:flex-start; }
        }
        @media (max-width: 620px) {
            .modal-card { padding:1.2rem; }
            .new-input { padding:0.8rem 0.9rem; }
        }
        .history-table { width:100%; border-collapse:collapse; min-width:640px; }
        .history-table th, .history-table td { padding:1rem; text-align:left; border-bottom:1px solid rgba(22,128,167,0.18); color:#071933; }
        .history-table th { color:#165a8a; font-weight:600; }
        .history-table tbody tr:last-child td { border-bottom:none; }
        .modal-body { display:grid; gap:1.5rem; }
        .modal-section h4 { margin:0 0 .75rem; font-size:1rem; color:#071933; }
        .modal-section p, .modal-section div { margin:0; color:#334155; line-height:1.7; }
        .summary-name { display:grid; gap:0.5rem; }
        .summary-name h3 { margin:0; font-size:1.75rem; color:#071933; }
        .summary-name .inline-name { display:none; width:100%; font-size:1.75rem; padding:.75rem 1rem; border-radius:1rem; border:1px solid #1680A7; background:#ffffff; color:#071933; }
        .field-value { display:block; opacity:.72; transition: opacity .2s ease; }
        .inline-input { display:none !important; width:100%; padding:.85rem 1rem; border:1px solid #1680A7; border-radius:1rem; background:#ffffff; color:#071933; }
        .new-input { width:100%; padding:.85rem 1rem; border:1px solid #1680A7; border-radius:1rem; background:#ffffff; color:#071933; }
        .photo-dropzone { min-height: 200px; border: 2px dashed #1680A7; border-radius: 1.25rem; display:grid; place-items:center; position:relative; cursor:pointer; background: #f8fbff; }
        .photo-dropzone.dragover { background: rgba(22,128,167,0.08); }
        .photo-preview { width: 100%; height: 100%; display:grid; place-items:center; padding: 1rem; text-align:center; color:#334155; }
        .photo-preview img { max-width: 100%; max-height: 100%; object-fit:contain; border-radius: 1rem; }
        .photo-input { position:absolute; inset:0; width:100%; height:100%; opacity:0; cursor:pointer; }
        .vehicle-warning { margin:0.5rem 0 0; font-size:0.9rem; color:#334155; }
        .modal-summary.editing .field-value { display:none !important; }
        .modal-summary.editing .inline-input { display:block !important; }
        .modal-summary.editing .summary-name h3 { display:none !important; }
        .modal-summary.editing .summary-name .inline-name { display:block !important; }
        .modal-summary.editing input, .modal-summary.editing select { opacity:1; }
        .input-group label { display:block; margin-bottom:.5rem; color:#334155; }
        .input-group input, .input-group select { width:100%; padding:.85rem 1rem; border:1px solid #1680A7; border-radius:1rem; background:#ffffff; color:#071933; }
        .button-primary, .button-secondary, .button-danger { border:none; border-radius:1rem; padding:.95rem 1.25rem; cursor:pointer; }
        .button-primary { background:#2563eb; color:#fff; }
        .button-primary:hover { background:#1d4ed8; }
        .button-secondary { background:#eaf4ff; color:#071933; border:1px solid #1680A7; }
        .button-secondary:hover { background:#d7eefd; }
        .button-danger { background:#dc2626; color:#fff; }
        .button-danger:hover { background:#b91c1c; }
        @media (max-width: 760px) {
            .modal-card { padding:1rem; }
            .modal-header { grid-template-columns: 1fr; }
            .profile-grid, .contact-grid { grid-template-columns: 1fr; }
            .summary-actions { justify-content:flex-start; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const newModal = document.getElementById('new-conductor-modal');
            const toggleButton = document.getElementById('toggle-new-conductor');
            const closeNewModal = document.getElementById('close-new-modal');
            const cancelNewModal = document.getElementById('cancel-new-modal');
            const newPhotoInput = document.getElementById('new-foto_perfil');
            const newPhotoPreview = document.getElementById('new-photo-preview');
            const newDropzone = document.getElementById('new-dropzone');
            const newLicense = document.getElementById('new-tipo_licencia');
            const newAutoSelect = document.getElementById('new-auto_id');
            const newVehicleWarning = document.getElementById('new-vehicle-warning');
            const modal = document.getElementById('conductores-modal');
            const closeModal = document.getElementById('close-modal');
            const toggleEdit = document.getElementById('toggle-edit');
            const deleteButton = document.getElementById('delete-conductor');
            const editForm = document.getElementById('modal-edit-form');
            const deleteForm = document.getElementById('modal-delete-form');

            const modalPhoto = document.getElementById('modal-photo');
            const modalName = document.getElementById('modal-name');
            const modalStatus = document.getElementById('modal-status');
            const modalCedula = document.getElementById('modal-cedula');
            const modalPhone = document.getElementById('modal-phone');
            const modalAuto = document.getElementById('modal-auto');
            const modalAge = document.getElementById('modal-age');
            const modalSex = document.getElementById('modal-sex');
            const modalBlood = document.getElementById('modal-blood');
            const modalLicense = document.getElementById('modal-license');
            const modalHistory = document.getElementById('modal-history');
            const modalHistoryTable = document.getElementById('modal-history-table');
            const historyTableShell = document.querySelector('.history-table-shell');
            const activityEmpty = document.querySelector('.activity-empty');
            const modalSummary = document.getElementById('modal-edit-form');
            const editNombre = document.getElementById('edit-nombre');
            const editCedula = document.getElementById('edit-cedula');
            const editTelefono = document.getElementById('edit-telefono');
            const editEdad = document.getElementById('edit-edad');
            const editSexo = document.getElementById('edit-sexo');
            const editTipoSangre = document.getElementById('edit-tipo_sangre');
            const editTipoLicencia = document.getElementById('edit-tipo_licencia');
            const editAuto = document.getElementById('edit-auto_id');
            const saveChanges = document.getElementById('save-changes');

            toggleButton.addEventListener('click', function () {
                newModal.style.display = 'grid';
            });

            closeNewModal.addEventListener('click', function () {
                newModal.style.display = 'none';
            });

            if (cancelNewModal) {
                cancelNewModal.addEventListener('click', function () {
                    newModal.style.display = 'none';
                });
            }

           function showNewPhoto(src) {
                newPhotoPreview.innerHTML = '<img src="' + src + '" alt="Foto" />';
                newPhotoPreview.style.display = 'grid'; // Asegurar que la imagen sea visible
                
                const icon = document.querySelector('#new-dropzone svg');
                if (icon) {
                    icon.style.display = 'none';
                }
            }
            
            function resetNewPhoto() {
                // Restaurar el texto por defecto
                if (newPhotoPreview) {
                    newPhotoPreview.innerHTML = '<span>Arrastra la imagen aquí o haz click</span>';
                    newPhotoPreview.style.display = 'grid';
                }
                
                // Volver a mostrar el ícono
                const icon = document.getElementById('dropzone-icon');
                if (icon) {
                    icon.style.display = 'block';
                }
            }

            function licenseRank(value) {
                const rank = ['2da', '3ra', '4ta', '5ta'];
                const index = rank.indexOf(value);
                return index === -1 ? -1 : index;
            }

            function canUseVehicle(driverLicense, requiredLicense) {
                const driverRank = licenseRank(driverLicense);
                const requiredRank = licenseRank(requiredLicense);
                if (requiredRank === -1) {
                    return true;
                }
                if (driverRank === -1) {
                    return false;
                }
                return driverRank >= requiredRank;
            }

            function updateVehicleOptions() {
                if (!newAutoSelect) return;
                const selectedLicense = newLicense ? newLicense.value : '';
                let visibleCount = 0;

                Array.from(newAutoSelect.options).forEach(function (option) {
                    const requiredLicense = option.dataset.requiredLicense || '2da';
                    if (!selectedLicense) {
                        option.hidden = false;
                        option.style.display = 'block';
                        if (option.value) visibleCount++;
                        return;
                    }
                    const isVisible = canUseVehicle(selectedLicense, requiredLicense);
                    option.hidden = !isVisible;
                    option.style.display = isVisible ? 'block' : 'none';
                    if (isVisible && option.value) visibleCount++;
                });

                if (newVehicleWarning) {
                    if (!selectedLicense) {
                        newVehicleWarning.textContent = 'Selecciona una licencia para filtrar vehículos compatibles.';
                    } else if (!visibleCount) {
                        newVehicleWarning.textContent = 'No hay vehículos compatibles con esta licencia.';
                    } else {
                        newVehicleWarning.textContent = 'Vehículos compatibles mostrados según la licencia seleccionada.';
                    }
                }

                if (newAutoSelect.value && newAutoSelect.selectedOptions.length && newAutoSelect.selectedOptions[0].hidden) {
                    newAutoSelect.value = '';
                }
            }

            if (newPhotoInput) {
                newPhotoInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            showNewPhoto(event.target.result);
                        };
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        resetNewPhoto();
                    }
                });
            }

            if (newDropzone) {
                newDropzone.addEventListener('dragover', function (event) {
                    event.preventDefault();
                    newDropzone.classList.add('dragover');
                });
                newDropzone.addEventListener('dragleave', function () {
                    newDropzone.classList.remove('dragover');
                });
                newDropzone.addEventListener('drop', function (event) {
                    event.preventDefault();
                    newDropzone.classList.remove('dragover');
                    const files = event.dataTransfer.files;
                    if (files && files[0]) {
                        newPhotoInput.files = files;
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            showNewPhoto(event.target.result);
                        };
                        reader.readAsDataURL(files[0]);
                    }
                });
            }

            const startCameraButton = document.getElementById('start-camera');
            const cameraPanel = document.getElementById('camera-panel');
            const cameraVideo = document.getElementById('camera-video');
            const cameraCountdown = document.getElementById('camera-countdown');
            const captureButton = document.getElementById('capture-photo');
            const acceptButton = document.getElementById('accept-photo');
            const cancelCameraButton = document.getElementById('cancel-camera');
            let cameraStream = null;
            let capturedBlob = null;

            function showCameraPanel() {
                if (!cameraPanel) return;
                cameraPanel.style.display = 'block'; // Muestra solo los botones abajo

                // Ocultar ícono y texto del dropzone
                const icon = document.getElementById('dropzone-icon');
                if (icon) icon.style.display = 'none';
                if (newPhotoPreview) newPhotoPreview.style.display = 'none';

                // Mostrar el video sobre el dropzone
                if (cameraVideo) {
                    cameraVideo.style.display = 'block';
                }
            }

            function hideCameraPanel() {
                if (!cameraPanel) return;
                cameraPanel.style.display = 'none';
                cameraCountdown.textContent = '';
                acceptButton.disabled = true;

                // Ocultar el video
                if (cameraVideo) {
                    cameraVideo.style.display = 'none';
                }

                // Si NO se capturó ninguna foto y el input de archivo está vacío, restauramos el ícono original
                if (!capturedBlob && (!newPhotoInput.files || newPhotoInput.files.length === 0)) {
                    resetNewPhoto();
                }
            }

            function stopCamera() {
                if (cameraStream) {
                    cameraStream.getTracks().forEach(track => track.stop());
                    cameraStream = null;
                }
                if (cameraVideo) {
                    cameraVideo.srcObject = null;
                }
            }

            async function openCamera() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('La cámara no está disponible en este navegador.');
                    return;
                }
                try {
                    cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                    cameraVideo.srcObject = cameraStream;
                    showCameraPanel();
                } catch (error) {
                    console.error('Error al acceder a la cámara:', error);
                    alert('No se pudo acceder a la cámara. Revisa los permisos e inténtalo de nuevo.');
                }
            }

            function updateInputFromBlob(blob) {
                const fileName = 'perfil-' + Date.now() + '.jpg';
                const file = new File([blob], fileName, { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                newPhotoInput.files = dataTransfer.files;
                capturedBlob = blob;
            }

            function capturePhoto() {
                if (!cameraVideo || !cameraStream) return;
                let countdown = 3;
                captureButton.disabled = true;
                cameraCountdown.textContent = countdown;

                const interval = setInterval(() => {
                    countdown -= 1;
                    if (countdown > 0) {
                        cameraCountdown.textContent = countdown;
                        return;
                    }

                    clearInterval(interval);
                    cameraCountdown.textContent = '¡Foto tomada!';
                    const canvas = document.createElement('canvas');
                    canvas.width = cameraVideo.videoWidth;
                    canvas.height = cameraVideo.videoHeight;
                    const context = canvas.getContext('2d');
                    if (context) {
                        context.drawImage(cameraVideo, 0, 0, canvas.width, canvas.height);
                        canvas.toBlob(function (blob) {
                            if (blob) {
                                capturedBlob = blob;
                                updateInputFromBlob(blob);
                                const url = URL.createObjectURL(blob);
                                showNewPhoto(url);
                                
                                // Agregar esta línea para ocultar el video tras tomar la foto
                                if (cameraVideo) cameraVideo.style.display = 'none'; 
                                
                                acceptButton.disabled = false;
                            }
                        }, 'image/jpeg', 0.95);
                    }
                    captureButton.disabled = false;
                }, 1000);
            }

            if (startCameraButton) {
                startCameraButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    openCamera();
                });
            }

            if (captureButton) {
                captureButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    capturePhoto();
                });
            }

            if (acceptButton) {
                acceptButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    hideCameraPanel();
                    stopCamera();
                });
            }

            if (cancelCameraButton) {
                cancelCameraButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    hideCameraPanel();
                    stopCamera();
                    if (!capturedBlob) {
                        resetNewPhoto();
                    }
                });
            }

            if (newLicense) {
                newLicense.addEventListener('change', updateVehicleOptions);
                updateVehicleOptions();
            }

            const filterForm = document.getElementById('conductor-filter-form');
            const searchInput = document.getElementById('search');
            const clearFiltersButton = document.getElementById('clear-filters');
            const filterInputs = ['status', 'tipo_sangre', 'tipo_licencia', 'auto_id'];

            if (searchInput) {
                let timeoutId;
                searchInput.addEventListener('input', function () {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(function () {
                        filterForm.submit();
                    }, 500);
                });
            }

            filterInputs.forEach(function (inputId) {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('change', function () {
                        filterForm.submit();
                    });
                }
            });

            if (clearFiltersButton && filterForm) {
                clearFiltersButton.addEventListener('click', function () {
                    searchInput.value = '';
                    filterInputs.forEach(function (inputId) {
                        const input = document.getElementById(inputId);
                        if (input) {
                            input.value = '';
                        }
                    });
                    filterForm.submit();
                });
            }

            document.querySelectorAll('.clickable-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    const photo = row.dataset.foto;
                    const name = row.dataset.nombre;
                    const status = row.dataset.status;
                    const cedula = row.dataset.cedula;
                    const telefono = row.dataset.telefono;
                    const edad = row.dataset.edad || '--';
                    const sexo = row.dataset.sexo || '--';
                    const tipoSangre = row.dataset.tipoSangre || '--';
                    const tipoLicencia = row.dataset.tipoLicencia || '--';
                    const auto = row.dataset.auto || 'Sin vehículo';
                    const updateUrl = row.dataset.updateUrl;
                    const deleteUrl = row.dataset.deleteUrl;
                    const autoId = row.dataset.autoId;
                    const isActive = row.dataset.isActive === '1';
                    const rutas = JSON.parse(row.dataset.historialRutas || '[]');
                    const servicios = JSON.parse(row.dataset.historialServicios || '[]');

                    modalPhoto.innerHTML = photo ? '<img src="' + photo + '" alt="' + name + '" />' : '<span style="color:#334155;">Sin foto</span>';
                    modalName.textContent = name || 'Sin nombre';
                    modalStatus.textContent = status;
                    modalCedula.textContent = cedula || 'Sin cédula';
                    modalPhone.textContent = telefono || 'Sin teléfono';
                    modalAuto.textContent = auto;
                    modalAge.textContent = edad;
                    modalSex.textContent = sexo;
                    modalBlood.textContent = tipoSangre;
                    modalLicense.textContent = tipoLicencia;
                    editForm.action = updateUrl;
                    deleteForm.action = deleteUrl;
                    if (editNombre) {
                        editNombre.value = name || '';
                    }
                    if (editCedula) {
                        editCedula.value = cedula || '';
                    }
                    if (editTelefono) {
                        editTelefono.value = telefono || '';
                    }
                    if (editEdad) {
                        editEdad.value = row.dataset.edad || '';
                    }
                    editSexo.value = row.dataset.sexo || '';
                    editTipoSangre.value = row.dataset.tipoSangre || '';
                    editTipoLicencia.value = row.dataset.tipoLicencia || '';
                    editAuto.value = autoId || '';
                    modalSummary.classList.remove('editing');
                    toggleEdit.style.display = 'inline-flex';
                    saveChanges.style.display = 'none';

                    const historyRows = [];
                    rutas.forEach(function(item) {
                        historyRows.push('<tr><td>Ruta</td><td><strong>' + item.titulo + '</strong><br>' + item.detalle + '</td><td>' + (item.distancia || '-') + '</td><td>' + item.fecha + '</td></tr>');
                    });
                    servicios.forEach(function(item) {
                        historyRows.push('<tr><td>Servicio</td><td><strong>' + item.titulo + '</strong><br>' + item.detalle + '</td><td>-</td><td>' + item.fecha + '</td></tr>');
                    });

                    if (historyRows.length) {
                        modalHistoryTable.innerHTML = historyRows.join('');
                        activityEmpty.style.display = 'none';
                        historyTableShell.style.display = 'block';
                    } else {
                        modalHistoryTable.innerHTML = '';
                        activityEmpty.style.display = 'block';
                        historyTableShell.style.display = 'none';
                    }
                    modal.style.display = 'grid';
                });
            });

            document.querySelectorAll('.status-select').forEach(function (select) {
                select.addEventListener('click', function (event) {
                    event.stopPropagation();
                });
                select.addEventListener('change', function (event) {
                    event.stopPropagation();
                    this.closest('form')?.submit();
                });
            });

            if (newModal) {
                newModal.addEventListener('click', function (event) {
                    if (event.target === newModal) {
                        newModal.style.display = 'none';
                    }
                });
            }

            if (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            }

            deleteButton.addEventListener('click', function () {
                if (!deleteForm.action) {
                    return;
                }

                if (confirm('¿Deseas eliminar este conductor? Esta acción no se puede deshacer.')) {
                    deleteForm.submit();
                }
            });

            closeModal.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            toggleEdit.addEventListener('click', function () {
                modalSummary.classList.add('editing');
                toggleEdit.style.display = 'none';
                saveChanges.style.display = 'inline-flex';
                if (editCheckboxGrid) {
                    editCheckboxGrid.style.display = 'grid';
                }
            });

            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
@endsection

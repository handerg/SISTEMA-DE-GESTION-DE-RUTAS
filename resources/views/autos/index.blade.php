@extends('layouts.app')

@section('content')
    <div class="section-card">
        <div class="section-header">
            <div>
                <h2>Autos</h2>
                <p>Administre los autos registrando la foto de perfil de cada vehículo.</p>
            </div>
            <button type="button" class="button-primary" id="toggle-new-auto">Agregar auto</button>
        </div>

        @if(session('success'))
            <div class="card" style="margin-top:1rem; background: rgba(52,211,153,0.12); border-color: rgba(52,211,153,0.2); color: #166534;">
                {{ session('success') }}
            </div>
        @endif

        <div class="card" style="margin-top:1rem;">
            <div class="autos-grid">
                @forelse($autos as $auto)
                    <div class="auto-card clickable-card"
                         data-id="{{ $auto->id }}"
                         data-photo="{{ $auto->foto_perfil ? asset('storage/'.$auto->foto_perfil) : '' }}"
                         data-placa="{{ $auto->placa }}"
                         data-marca="{{ $auto->marca }}"
                         data-modelo="{{ $auto->modelo }}"
                         data-anio="{{ $auto->anio }}"
                         data-kilometraje="{{ $auto->kilometraje }}"
                         data-color="{{ $auto->color }}"
                         data-required-license="{{ $auto->required_license }}"
                    >
                        <div class="auto-photo">
                            @if($auto->foto_perfil)
                                <img src="{{ asset('storage/'.$auto->foto_perfil) }}" alt="Auto {{ $auto->id }}" />
                            @else
                                <span>Sin foto</span>
                            @endif
                        </div>
                        <div class="auto-meta">
                            <strong>{{ $auto->placa ? 'Placa: '.$auto->placa : 'Vehículo #'.$auto->id }}</strong>
                            <p>{{ $auto->marca ?? 'Marca' }} {{ $auto->modelo ?? '' }} · {{ $auto->anio ?? '' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-card">
                        <p>No hay autos registrados aún.</p>
                        <p>Haz clic en "Agregar auto" para registrar el primero.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="new-auto-modal" style="display:none;">
        <div class="modal-card modal-card-form">
            <button type="button" class="modal-close" id="close-new-auto-modal">×</button>
            <form method="POST" action="{{ route('autos.store', [], false) }}" enctype="multipart/form-data" id="new-auto-form">
                @csrf
                <div class="modal-form-top">
                    <h3>Registrar vehículo</h3>
                    <p>Completa los datos principales del vehículo y sube una imagen para identificarlo rápidamente en el sistema.</p>
                </div>
                <div class="p-8 new-modal-grid">
                    <div class="photo-panel">
                        <div class="upload-card">
                            <span class="upload-label">Foto de perfil</span>
                            <label for="foto_perfil" class="file-dropzone">
                                <div class="dropzone-content">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/><polyline points="7 9 12 4 17 9"/><line x1="12" y1="4" x2="12" y2="16"/></svg>
                                    <div>
                                        <strong>Selecciona una imagen</strong>
                                        <p>Max 2MB. JPG, PNG o WEBP.</p>
                                    </div>
                                </div>
                            </label>
                            <input id="foto_perfil" name="foto_perfil" type="file" accept="image/*" class="photo-input" required />
                            @error('foto_perfil')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="preview-panel">
                            <span class="preview-title">Vista previa</span>
                            <div class="preview-box" id="auto-image-preview">
                                <span>Selecciona una imagen</span>
                            </div>
                        </div>
                    </div>

                    <div class="fields-panel">
                        <div class="field-grid">
                            <div class="input-group">
                                <label for="placa">Placa</label>
                                <input id="placa" name="placa" type="text" value="{{ old('placa') }}" class="new-input" placeholder="AVT-1234" required />
                                @error('placa')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="input-group">
                                <label for="marca">Marca</label>
                                <input id="marca" name="marca" type="text" value="{{ old('marca') }}" class="new-input" placeholder="Toyota" required />
                                @error('marca')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="input-group">
                                <label for="modelo">Modelo</label>
                                <input id="modelo" name="modelo" type="text" value="{{ old('modelo') }}" class="new-input" placeholder="Hilux" required />
                                @error('modelo')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="input-group">
                                <label for="anio">Año</label>
                                <input id="anio" name="anio" type="number" min="1900" max="2099" value="{{ old('anio') }}" class="new-input" placeholder="2024" required />
                                @error('anio')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="input-group">
                                <label for="kilometraje">Kilometraje</label>
                                <input id="kilometraje" name="kilometraje" type="text" value="{{ old('kilometraje') }}" class="new-input" placeholder="120,000 km" />
                                @error('kilometraje')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="input-group">
                                <label for="color">Color</label>
                                <input id="color" name="color" type="text" value="{{ old('color') }}" class="new-input" placeholder="Blanco" />
                                @error('color')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="input-group">
                                <label for="required_license">Licencia requerida</label>
                                <select id="required_license" name="required_license" class="new-input">
                                    <option value="">Seleccionar</option>
                                    <option value="2da" {{ old('required_license') == '2da' ? 'selected' : '' }}>2da</option>
                                    <option value="3ra" {{ old('required_license') == '3ra' ? 'selected' : '' }}>3ra</option>
                                    <option value="4ta" {{ old('required_license') == '4ta' ? 'selected' : '' }}>4ta</option>
                                </select>
                                @error('required_license')<span class="field-error">{{ $message }}</span>@enderror
                                <p class="field-note">Elige el tipo de licencia mínimo que debe tener el conductor.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="button-secondary" id="cancel-new-auto-modal">Cancelar</button>
                    <button type="submit" class="button-primary">Guardar vehículo</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="autos-modal" style="display:none;">
        <div class="modal-card detail-modal-card">
            <button type="button" class="modal-close" id="close-auto-modal">×</button>
            <div class="detail-header">
                <div class="detail-profile">
                    <div class="detail-avatar" id="auto-avatar">A</div>
                    <div>
                        <h2 class="detail-name" id="modal-auto-title"></h2>
                        <div class="badge-row">
                            <span class="status-badge" id="modal-auto-license">Licencia: --</span>
                            <span class="status-badge" id="modal-auto-color">Color: --</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="detail-grid">
                <div class="info-grid info-grid-auto">
                    <div class="info-card">
                        <p class="info-label">ID del vehículo</p>
                        <p class="info-value" id="modal-auto-id">--</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Placa</p>
                        <p class="info-value" id="modal-auto-placa">--</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Marca y modelo</p>
                        <p class="info-value" id="modal-auto-model">--</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Año</p>
                        <p class="info-value" id="modal-auto-anio">--</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Kilometraje</p>
                        <p class="info-value" id="modal-auto-km">--</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Foto de perfil</p>
                        <div class="info-value auto-detail-photo" id="modal-auto-photo">
                            <span>Sin imagen</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .section-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; padding-bottom:1rem; border-bottom:1px solid rgba(22,128,167,0.12); margin-bottom:1rem; }
        .section-header h2 { margin:0; font-size:1.75rem; color:#0f172a; }
        .section-header p { margin:0.35rem 0 0; color:#475569; }
        .autos-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; }
        .auto-card { border:1px solid rgba(22,128,167,0.15); border-radius:1.5rem; overflow:hidden; background:#f8fbff; cursor:pointer; transition:transform .2s ease, box-shadow .2s ease; }
        .auto-card:hover { transform:translateY(-2px); box-shadow:0 18px 40px rgba(22,128,167,0.1); }
        .auto-photo { width:100%; min-height:180px; display:grid; place-items:center; background:#e0f2fe; }
        .auto-photo img { width:100%; height:100%; object-fit:cover; }
        .auto-photo span { color:#475569; }
        .auto-meta { padding:1rem; }
        .empty-card { padding:2rem; border:1px dashed rgba(22,128,167,0.3); border-radius:1.5rem; text-align:center; background:#f8fbff; color:#475569; }
        .modal-form-top { padding:1.5rem 1.75rem 0; }
        .modal-form-top h3 { margin:0; font-size:1.75rem; color:#071933; }
        .modal-form-top p { margin:0.5rem 0 0; color:#475569; line-height:1.7; }
        .new-modal-grid { display:grid; gap:2rem; grid-template-columns: minmax(280px, 1fr) minmax(420px, 1.6fr); align-items:start; }
        .photo-panel { display:grid; gap:1rem; }
        .upload-card { border:1px solid rgba(22,128,167,0.18); padding:1rem; border-radius:1.25rem; background:#eff8ff; position:relative; }
        .upload-label { display:block; margin-bottom:0.75rem; font-weight:700; color:#334155; }
        .file-dropzone { display:grid; border:2px dashed #7dd3fc; border-radius:1.25rem; padding:1.25rem; background:#ffffff; cursor:pointer; }
        .file-dropzone:hover { background:#eff9ff; }
        .dropzone-content { display:grid; gap:1rem; place-items:center; text-align:center; color:#0f172a; }
        .dropzone-content svg { width:48px; height:48px; color:#0284c7; }
        .photo-input { display:none; }
        .preview-panel { border:1px solid rgba(22,128,167,0.18); border-radius:1.25rem; padding:1rem; background:#f8fbff; }
        .preview-title { display:block; margin-bottom:0.75rem; font-size:0.95rem; font-weight:700; color:#334155; }
        .fields-panel { display:grid; gap:1rem; }
        .field-grid { display:grid; gap:1rem; grid-template-columns:repeat(2,minmax(0,1fr)); }
        .input-group { display:grid; gap:0.4rem; }
        .input-group label { color:#334155; font-size:0.95rem; font-weight:600; }
        .input-group input, .input-group select { width:100%; padding:0.95rem 1rem; border:1px solid #cbd5e1; border-radius:1rem; background:#fff; color:#0f172a; }
        .field-note { margin:0.5rem 0 0; color:#475569; font-size:0.9rem; }
        .preview-box { min-height:220px; display:grid; place-items:center; border:1px dashed #cbd5e1; border-radius:1rem; background:#f8fafc; color:#64748b; text-align:center; padding:1rem; }
        .preview-box img { max-width:100%; max-height:100%; border-radius:1rem; }
        .modal-card, .detail-modal-card { max-width: 860px; padding:1.75rem; }
        .detail-header { display:flex; justify-content:space-between; gap:1rem; align-items:center; padding:1.25rem 0 0; }
        .detail-profile { display:flex; gap:1rem; align-items:center; }
        .detail-avatar { width:56px; height:56px; border-radius:9999px; background:#dbeafe; display:grid; place-items:center; color:#1d4ed8; font-weight:700; font-size:1.25rem; }
        .detail-name { margin:0; font-size:1.5rem; color:#0f172a; }
        .status-badge { display:inline-flex; align-items:center; gap:0.35rem; border-radius:9999px; padding:0.35rem 0.75rem; font-size:0.8rem; font-weight:600; color:#0f172a; background:#dbeafe; }
        .detail-grid { padding:1.5rem 0 0; }
        .badge-row { display:flex; gap:0.75rem; flex-wrap:wrap; margin-top:0.5rem; }
        .info-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:1rem; }
        .info-grid-auto { align-items:start; }
        .info-card { padding:1.15rem; border-radius:1.25rem; background:#f8fbff; border:1px solid #a7d8f2; }
        .info-label { margin:0 0 0.5rem; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:#475569; }
        .info-value { margin:0; font-size:1rem; font-weight:600; color:#0f172a; }
        .auto-detail-photo { min-height:180px; display:grid; place-items:center; background:#e0f2fe; border-radius:1rem; overflow:hidden; }
        .auto-detail-photo img { width:100%; height:100%; object-fit:cover; }
        .modal-overlay { position: fixed; inset:0; z-index:50; background: rgba(0,0,0,0.65); display:grid; place-items:center; padding:1.5rem; }
        .modal-card { width:min(900px,100%); max-height:calc(100vh - 3rem); overflow-y:auto; background:#ffffff; border:1px solid #1680A7; border-radius:1.5rem; position:relative; }
        .modal-card-form { max-width: 920px; }
        .modal-close { position:absolute; top:1rem; right:1rem; border:none; background:transparent; color:#071933; font-size:1.5rem; cursor:pointer; }
        .modal-actions { display:flex; justify-content:flex-end; gap:0.75rem; padding:1rem 1.5rem 1.5rem; background:#f8fbff; border-top:1px solid rgba(22,128,167,0.12); }
        .button-primary, .button-secondary { border:none; border-radius:1rem; padding:.95rem 1.25rem; cursor:pointer; }
        .button-primary { background:#2563eb; color:#fff; }
        .button-primary:hover { background:#1d4ed8; }
        .button-secondary { background:#eaf4ff; color:#071933; border:1px solid #1680A7; }
        .button-secondary:hover { background:#d7eefd; }
        @media (max-width: 980px) {
            .new-modal-grid { grid-template-columns: 1fr; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const newAutoModal = document.getElementById('new-auto-modal');
            const toggleNewAuto = document.getElementById('toggle-new-auto');
            const closeNewAutoModal = document.getElementById('close-new-auto-modal');
            const cancelNewAutoModal = document.getElementById('cancel-new-auto-modal');
            const autoModal = document.getElementById('autos-modal');
            const closeAutoModal = document.getElementById('close-auto-modal');
            const photoInput = document.getElementById('foto_perfil');
            const previewBox = document.getElementById('auto-image-preview');
            const modalAutoTitle = document.getElementById('modal-auto-title');
            const modalAutoId = document.getElementById('modal-auto-id');
            const modalAutoPlaca = document.getElementById('modal-auto-placa');
            const modalAutoModel = document.getElementById('modal-auto-model');
            const modalAutoAnio = document.getElementById('modal-auto-anio');
            const modalAutoKm = document.getElementById('modal-auto-km');
            const modalAutoColor = document.getElementById('modal-auto-color');
            const modalAutoLicense = document.getElementById('modal-auto-license');
            const modalAutoPhoto = document.getElementById('modal-auto-photo');
            const autoAvatar = document.getElementById('auto-avatar');

            function openModal(modal) {
                if (modal) {
                    modal.style.display = 'grid';
                }
            }

            function closeModal(modal) {
                if (modal) {
                    modal.style.display = 'none';
                }
            }

            if (toggleNewAuto) {
                toggleNewAuto.addEventListener('click', function () {
                    openModal(newAutoModal);
                });
            }

            if (closeNewAutoModal) {
                closeNewAutoModal.addEventListener('click', function () {
                    closeModal(newAutoModal);
                });
            }

            if (cancelNewAutoModal) {
                cancelNewAutoModal.addEventListener('click', function () {
                    closeModal(newAutoModal);
                });
            }

            if (photoInput) {
                photoInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) {
                        previewBox.innerHTML = '<span>Selecciona una imagen</span>';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        previewBox.innerHTML = '<img src="' + event.target.result + '" alt="Preview" />';
                    };
                    reader.readAsDataURL(file);
                });
            }

            document.querySelectorAll('.clickable-card').forEach(function (card) {
                card.addEventListener('click', function () {
                    const photo = card.dataset.photo;
                    const autoId = card.dataset.id;
                    const placa = card.dataset.placa || 'Sin placa';
                    const marca = card.dataset.marca || 'Desconocida';
                    const modelo = card.dataset.modelo || '';
                    const anio = card.dataset.anio || 'N/A';
                    const kilometraje = card.dataset.kilometraje || 'N/D';
                    const color = card.dataset.color || 'N/D';
                    const requiredLicense = card.dataset.requiredLicense || 'N/D';

                    modalAutoTitle.textContent = placa !== 'Sin placa' ? placa : 'Vehículo #' + autoId;
                    modalAutoId.textContent = autoId;
                    modalAutoPlaca.textContent = placa;
                    modalAutoModel.textContent = marca + ' ' + modelo;
                    modalAutoAnio.textContent = anio;
                    modalAutoKm.textContent = kilometraje;
                    modalAutoColor.textContent = 'Color: ' + color;
                    modalAutoLicense.textContent = 'Licencia: ' + requiredLicense;
                    autoAvatar.textContent = placa ? placa.charAt(0).toUpperCase() : 'A';

                    if (photo) {
                        modalAutoPhoto.innerHTML = '<img src="' + photo + '" alt="Auto ' + autoId + '" />';
                    } else {
                        modalAutoPhoto.innerHTML = '<span>Sin imagen</span>';
                    }
                    openModal(autoModal);
                });
            });

            if (autoModal) {
                autoModal.addEventListener('click', function (event) {
                    if (event.target === autoModal) {
                        closeModal(autoModal);
                    }
                });
            }

            if (closeAutoModal) {
                closeAutoModal.addEventListener('click', function () {
                    closeModal(autoModal);
                });
            }

            @if($errors->has('foto_perfil'))
                openModal(newAutoModal);
            @endif
        });
    </script>
@endsection

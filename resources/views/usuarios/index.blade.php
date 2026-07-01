@extends('layouts.app')

@section('content')
    <div class="section-card">
        <div class="section-header">
            <div>
                <h2>Usuarios</h2>
                <p>Desde aquí puedes agregar nuevos usuarios, bloquearlos o eliminarlos.</p>
            </div>
            <button type="button" class="button-primary" id="toggle-new-user">Agregar usuario</button>
        </div>

        @if(session('success'))
            <div class="card" style="margin-top:1rem; background: rgba(52,211,153,0.12); border-color: rgba(52,211,153,0.2); color: #166534;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-shell card" style="margin-top:1rem;">
            <div style="overflow-x:auto; max-height:520px; overflow-y:auto;">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="clickable-row" data-id="{{ $user->id }}"
                                data-toggle-url="{{ route('usuarios.toggle', $user, [], false) }}"
                                data-delete-url="{{ route('usuarios.destroy', $user, [], false) }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}"
                                data-role="{{ ucfirst($user->role->nombre_rol ?? 'Sin rol') }}"
                                data-status="{{ $user->is_active ? 'Activo' : 'Bloqueado' }}"
                            >
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role->nombre_rol ?? 'Sin rol') }}</td>
                                <td>{{ $user->is_active ? 'Activo' : 'Bloqueado' }}</td>
                                <td>
                                    <button type="button" class="button-secondary view-user">Ver</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="new-user-modal" style="display:none;">
        <div class="modal-card modal-card-form">
            <button type="button" class="modal-close" id="close-new-user-modal">×</button>
            <form method="POST" action="{{ route('usuarios.store', [], false) }}" id="new-user-form" class="new-user-form">
                @csrf
                <div class="p-8 new-modal-grid">
                    <div class="photo-panel">
                        <div>
                            <h3>Agregar usuario</h3>
                            <p>Completa los datos del nuevo usuario y asigna su rol.</p>
                        </div>
                    </div>
                    <div class="fields-panel">
                        <div class="field-row">
                            <div class="input-group">
                                <label for="name">Nombre</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" class="new-input" required />
                                @error('name')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="input-group">
                                <label for="email">Correo</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" class="new-input" required />
                                @error('email')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="input-group">
                                <label for="password">Contraseña</label>
                                <input id="password" name="password" type="password" class="new-input" required />
                                @error('password')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="input-group">
                                <label for="role_id">Rol</label>
                                <select id="role_id" name="role_id" class="new-input" required>
                                    @foreach(\App\Models\Role::all() as $role)
                                        <option value="{{ $role->id_rol }}" {{ old('role_id') == $role->id_rol ? 'selected' : '' }}>{{ ucfirst($role->nombre_rol) }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="button-secondary" id="cancel-new-user-modal">Cancelar</button>
                    <button type="submit" class="button-primary">Crear usuario</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="usuarios-modal" style="display:none;">
        <div class="modal-card detail-modal-card">
            <button type="button" class="modal-close" id="close-user-modal">×</button>
            <div class="detail-header">
                <div class="detail-profile">
                    <div class="detail-avatar" id="user-avatar">U</div>
                    <div>
                        <h2 class="detail-name" id="modal-user-name"></h2>
                        <span class="status-badge" id="modal-user-status"></span>
                    </div>
                </div>
                <div class="detail-actions">
                    <button type="button" class="button-secondary" id="toggle-user-status">Cambiar estado</button>
                    <button type="button" class="button-danger" id="delete-user">Eliminar</button>
                </div>
            </div>
            <div class="detail-grid">
                <div class="info-grid">
                    <div class="info-card">
                        <p class="info-label">Nombre</p>
                        <p class="info-value" id="modal-user-name-value">--</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Email</p>
                        <p class="info-value" id="modal-user-email">--</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Rol</p>
                        <p class="info-value" id="modal-user-role">--</p>
                    </div>
                </div>
            </div>
            <form id="user-toggle-form" method="POST" style="display:none;">
                @csrf
            </form>
            <form id="user-delete-form" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <style>
        .section-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; padding-bottom:1rem; border-bottom:1px solid rgba(22,128,167,0.12); margin-bottom:1rem; }
        .section-header h2 { margin:0; font-size:1.75rem; color:#0f172a; }
        .section-header p { margin:0.35rem 0 0; color:#475569; }
        .new-modal-grid { display:grid; gap:2rem; grid-template-columns: minmax(260px, 1fr) minmax(420px, 1.5fr); align-items:start; }
        .photo-panel { display:grid; gap:0.75rem; }
        .photo-panel h3 { margin:0; font-size:1.75rem; color:#0f172a; }
        .photo-panel p { margin:0.5rem 0 0; color:#475569; }
        .fields-panel { display:grid; gap:1rem; }
        .field-row { display:grid; grid-template-columns: 1fr 1fr; gap:1rem; }
        .input-group label { display:block; margin-bottom:0.5rem; color:#334155; font-size:0.95rem; }
        .input-group input, .input-group select { width:100%; padding:0.95rem 1rem; border:1px solid #cbd5e1; border-radius:1rem; background:#fff; color:#0f172a; }
        .new-input { width:100%; }
        .modal-actions { display:flex; justify-content:flex-end; gap:0.75rem; padding:1rem 1.5rem 1.5rem; background:#f8fbff; border-top:1px solid rgba(22,128,167,0.12); }
        .detail-modal-card { max-width: 720px; padding:0; border:none; }
        .detail-header { display:flex; justify-content:space-between; gap:1rem; align-items:center; padding:1.5rem 1.75rem; border-bottom:1px solid rgba(22,128,167,0.12); background:#f8fbff; }
        .detail-profile { display:flex; gap:1rem; align-items:center; }
        .detail-avatar { width:56px; height:56px; border-radius:9999px; background:#dbeafe; display:grid; place-items:center; color:#1d4ed8; font-weight:700; font-size:1.25rem; }
        .detail-name { margin:0; font-size:1.5rem; color:#0f172a; }
        .status-badge { display:inline-flex; align-items:center; gap:0.35rem; border-radius:9999px; padding:0.35rem 0.75rem; font-size:0.8rem; font-weight:600; color:#166534; background:#dcfce7; }
        .detail-actions { display:flex; gap:0.75rem; flex-wrap:wrap; }
        .detail-grid { padding:1.75rem; background:#ffffff; }
        .info-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:1rem; }
        .info-card { padding:1.15rem; border-radius:1.25rem; background:#f8fbff; border:1px solid #a7d8f2; }
        .info-label { margin:0 0 0.5rem; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:#475569; }
        .info-value { margin:0; font-size:1.2rem; font-weight:600; color:#0f172a; }
        .table-shell { max-height: 520px; overflow-y: auto; background: #ffffff; border: 1px solid #1680A7; border-radius: 1.25rem; }
        .table-list { width: 100%; border-collapse: collapse; min-width: 720px; }
        .table-list th, .table-list td { padding: 1rem; border-bottom: 1px solid rgba(22,128,167,0.18); text-align:left; color: #071933; }
        .table-list th { color: #165a8a; font-weight:600; }
        .clickable-row { cursor:pointer; transition: background .2s ease; }
        .clickable-row:hover { background: rgba(59,130,246,0.08); }
        .modal-overlay { position: fixed; inset:0; z-index:50; background: rgba(0,0,0,0.65); display:grid; place-items:center; padding:1.5rem; }
        .modal-card { width:min(900px,100%); max-height:calc(100vh - 3rem); overflow-y:auto; background:#ffffff; border:1px solid #1680A7; border-radius:1.5rem; padding:1.75rem; position:relative; }
        .modal-card-form { max-width: 920px; }
        .modal-close { position:absolute; top:1rem; right:1rem; border:none; background:transparent; color:#071933; font-size:1.5rem; cursor:pointer; }
        .button-primary, .button-secondary, .button-danger { border:none; border-radius:1rem; padding:.95rem 1.25rem; cursor:pointer; }
        .button-primary { background:#2563eb; color:#fff; }
        .button-primary:hover { background:#1d4ed8; }
        .button-secondary { background:#eaf4ff; color:#071933; border:1px solid #1680A7; }
        .button-secondary:hover { background:#d7eefd; }
        .button-danger { background:#dc2626; color:#fff; }
        .button-danger:hover { background:#b91c1c; }
        @media (max-width: 980px) {
            .new-modal-grid { grid-template-columns: 1fr; }
            .field-row { grid-template-columns: 1fr; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const newUserModal = document.getElementById('new-user-modal');
            const toggleNewUser = document.getElementById('toggle-new-user');
            const closeNewUserModal = document.getElementById('close-new-user-modal');
            const cancelNewUserModal = document.getElementById('cancel-new-user-modal');
            const userModal = document.getElementById('usuarios-modal');
            const closeUserModal = document.getElementById('close-user-modal');
            const toggleUserStatus = document.getElementById('toggle-user-status');
            const deleteUser = document.getElementById('delete-user');
            const userToggleForm = document.getElementById('user-toggle-form');
            const userDeleteForm = document.getElementById('user-delete-form');
            const modalUserName = document.getElementById('modal-user-name');
            const modalUserNameValue = document.getElementById('modal-user-name-value');
            const modalUserEmail = document.getElementById('modal-user-email');
            const modalUserRole = document.getElementById('modal-user-role');
            const modalUserStatus = document.getElementById('modal-user-status');
            const userAvatar = document.getElementById('user-avatar');

            if (toggleNewUser) {
                toggleNewUser.addEventListener('click', function () {
                    newUserModal.style.display = 'grid';
                });
            }

            if (closeNewUserModal) {
                closeNewUserModal.addEventListener('click', function () {
                    newUserModal.style.display = 'none';
                });
            }

            if (cancelNewUserModal) {
                cancelNewUserModal.addEventListener('click', function () {
                    newUserModal.style.display = 'none';
                });
            }

            function openUserModal(userData) {
                modalUserName.textContent = userData.name;
                modalUserNameValue.textContent = userData.name;
                modalUserEmail.textContent = userData.email;
                modalUserRole.textContent = userData.role;
                modalUserStatus.textContent = userData.status;
                userToggleForm.action = userData.toggleUrl;
                userDeleteForm.action = userData.deleteUrl;
                userAvatar.textContent = userData.name.charAt(0).toUpperCase();
                userModal.style.display = 'grid';
            }

            document.querySelectorAll('.clickable-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    openUserModal({
                        name: row.dataset.name,
                        email: row.dataset.email,
                        role: row.dataset.role,
                        status: row.dataset.status,
                        toggleUrl: row.dataset.toggleUrl,
                        deleteUrl: row.dataset.deleteUrl,
                    });
                });
            });

            document.querySelectorAll('.view-user').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.stopPropagation();
                    const row = this.closest('tr');
                    if (row) {
                        openUserModal({
                            name: row.dataset.name,
                            email: row.dataset.email,
                            role: row.dataset.role,
                            status: row.dataset.status,
                            toggleUrl: row.dataset.toggleUrl,
                            deleteUrl: row.dataset.deleteUrl,
                        });
                    }
                });
            });

            if (userModal) {
                userModal.addEventListener('click', function (event) {
                    if (event.target === userModal) {
                        userModal.style.display = 'none';
                    }
                });
            }

            if (closeUserModal) {
                closeUserModal.addEventListener('click', function () {
                    userModal.style.display = 'none';
                });
            }

            if (toggleUserStatus) {
                toggleUserStatus.addEventListener('click', function () {
                    if (userToggleForm.action) {
                        userToggleForm.submit();
                    }
                });
            }

            if (deleteUser) {
                deleteUser.addEventListener('click', function () {
                    if (!userDeleteForm.action) {
                        return;
                    }
                    if (confirm('¿Deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
                        userDeleteForm.submit();
                    }
                });
            }
        });
    </script>
@endsection

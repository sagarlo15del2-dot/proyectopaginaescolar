<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../model/UsuarioModel.php';
$usuariosSistema = UsuarioModel::mdlMostrarUsuarios();
$mensajeUsuario = isset($_SESSION['usuarioMensaje']) ? $_SESSION['usuarioMensaje'] : null;
error_log("ADMIN_USUARIOS: mensajeUsuario = " . ($mensajeUsuario ?? 'null'));
unset($_SESSION['usuarioMensaje']);

$rutaActual = (isset($_GET['ruta']) && $_GET['ruta'] === 'panel-usuario') ? 'panel-usuario' : 'admin';
$accionUsuarios = 'index.php?ruta=' . $rutaActual . '&vista=usuarios';
?>

<div id="vista-usuarios" class="dashboard-content" style="display: none;">
            
    <div class="header-mensajes">
        <div>
            <h1 class="page-title">👥 Gestión de Usuarios</h1>
            <p>Crea cuentas para tu equipo y administra el directorio del personal.</p>
        </div>
    </div>

    <?php if ($mensajeUsuario === 'ok'): ?>
        <div class="alert alert-success">Usuario creado correctamente.</div>
    <?php elseif ($mensajeUsuario === 'editado_ok'): ?>
        <div class="alert alert-success">Usuario actualizado correctamente.</div>
    <?php elseif ($mensajeUsuario === 'eliminado_ok'): ?>
        <div class="alert alert-success">Usuario eliminado correctamente.</div>
    <?php elseif ($mensajeUsuario === 'eliminado_error'): ?>
        <div class="alert alert-danger">No se pudo eliminar el usuario.</div>
    <?php elseif ($mensajeUsuario === 'duplicate_email'): ?>
        <div class="alert alert-warning">No se pudo crear o actualizar: ya existe un usuario con ese correo.</div>
    <?php elseif ($mensajeUsuario === 'error'): ?>
        <div class="alert alert-danger">No se pudo completar la accion en usuarios. Verifica los datos e intenta de nuevo.</div>
    <?php endif; ?>

    <div class="card form-card">
        <form action="<?php echo htmlspecialchars($accionUsuarios); ?>" method="POST" enctype="multipart/form-data">
            <h3 class="form-section-title">👤 Datos del Nuevo Usuario</h3>
            
            <div class="form-usuario-container">
                
                <div class="area-foto-upload">
                    <div class="avatar-preview-container">
                        <span>👤</span>
                    </div>
                    
                    <label for="foto_perfil" class="label-archivo-custom">Elegir Foto</label>
                    <input type="file" name="foto_perfil" id="foto_perfil" class="input-archivo-oculto" accept="image/*">
                </div>

                <div class="area-datos-form">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Nombre y Apellidos Completos</label>
                            <input type="text" name="nuevo_nombre" class="input-admin" placeholder="Ej. Ana Jiménez" required>
                        </div>
                        <div class="form-group">
                            <label>Rol del Sistema (Cargo)</label>
                            <select name="nuevo_rol" class="select-admin">
                                <option value="admin">Administrador Global</option>
                                <option value="editor">Editor de Contenido</option>
                                <option value="docente">Docente / Soporte</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Correo Electrónico (Usuario)</label>
                            <input type="email" name="nuevo_email" class="input-admin" placeholder="ana@miescuela.edu.mx" required>
                        </div>
                        <div class="form-group">
                            <label>Contraseña Temporal</label>
                            <input type="password" name="nuevo_pass" class="input-admin" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions-right">
                <button type="submit" class="btn-guardar">➕ Agregar Usuario</button>
            </div>
        </form>
    </div>

    <h3 class="titulo-seccion-secundario">Usuarios Registrados</h3>
    <div class="card card-tabla">
        <table class="tabla-mensajes">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nombre Completo</th>
                    <th>Correo Electrónico</th>
                    <th>Rol / Cargo</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuariosSistema)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay usuarios registrados todavía.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuariosSistema as $usuario): ?>
                        <tr class="fila-leida">
                            <td class="text-center">
                                <?php if (!empty($usuario['foto_perfil'])): ?>
                                    <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de <?php echo htmlspecialchars($usuario['nombre']); ?>" class="avatar-tabla">
                                <?php else: ?>
                                    <div class="avatar-placeholder"><?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="txt-nombre"><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td class="txt-correo"><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><span class="badge-<?php echo $usuario['rol'] === 'admin' ? 'admin' : ($usuario['rol'] === 'editor' ? 'editor' : 'docente'); ?>"><?php echo htmlspecialchars(ucfirst($usuario['rol'])); ?></span></td>
                            <td class="text-center">
                                <button type="button" class="btn-leer-sec"
                                    data-id="<?php echo $usuario['id']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES); ?>"
                                    data-email="<?php echo htmlspecialchars($usuario['email'], ENT_QUOTES); ?>"
                                    data-rol="<?php echo htmlspecialchars($usuario['rol'], ENT_QUOTES); ?>"
                                    data-foto="<?php echo htmlspecialchars($usuario['foto_perfil'] ?? '', ENT_QUOTES); ?>"
                                    onclick="editarUsuario(this)">Editar</button>
                                <button type="button" class="btn-eliminar" title="Dar de baja" onclick="eliminarUsuario(<?php echo $usuario['id']; ?>, '<?php echo htmlspecialchars($usuario['nombre']); ?>')">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
var accionUsuarios = '<?php echo htmlspecialchars($accionUsuarios, ENT_QUOTES); ?>';

function editarUsuario(btn) {
    // Eliminar modal anterior si existiera
    var anterior = document.getElementById('editModal');
    if (anterior) anterior.remove();

    var id     = btn.getAttribute('data-id');
    var nombre = btn.getAttribute('data-nombre');
    var email  = btn.getAttribute('data-email');
    var rol    = btn.getAttribute('data-rol');
    var foto   = btn.getAttribute('data-foto');

    var docente = (rol === 'docente') ? 'selected' : '';
    var editor  = (rol === 'editor')  ? 'selected' : '';
    var admin   = (rol === 'admin')   ? 'selected' : '';

    var fotoHTML = foto
        ? '<img id="preview-foto-modal" src="' + foto + '" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;">'
        : '<div id="preview-foto-modal" style="width:80px;height:80px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:2rem;color:#94a3b8;">&#128100;</div>';

    var overlay = document.createElement('div');
    overlay.id = 'editModal';
    overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,0.6);z-index:99999;display:flex;align-items:center;justify-content:center;';
    overlay.innerHTML =
        '<div style="background:#fff;border-radius:16px;width:90%;max-width:480px;box-shadow:0 25px 50px rgba(0,0,0,0.2);overflow:hidden;font-family:Inter,sans-serif;">' +
        '  <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 26px 16px;border-bottom:1px solid #e2e8f0;">' +
        '    <h3 style="margin:0;font-size:1.1rem;color:#0e1b38;">&#9999;&#65039; Editar Usuario</h3>' +
        '    <button onclick="cerrarModal()" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#64748b;line-height:1;padding:4px 8px;border-radius:6px;">&#x2715;</button>' +
        '  </div>' +
        '  <form method="POST" action="' + accionUsuarios + '" enctype="multipart/form-data">' +
        '    <input type="hidden" name="editar_usuario_id" value="' + id + '">' +
        '    <div style="padding:22px 26px;display:flex;flex-direction:column;gap:14px;">' +
        '      <div style="display:flex;align-items:center;gap:18px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">' +
        fotoHTML +
        '        <div style="display:flex;flex-direction:column;gap:6px;">' +
        '          <span style="font-size:0.82rem;font-weight:600;color:#64748b;">Foto actual</span>' +
        '          <label for="editar_foto_perfil_input" style="display:inline-block;padding:6px 14px;background:#0e1b38;color:#fff;border-radius:6px;font-size:0.82rem;font-weight:600;cursor:pointer;">Cambiar foto</label>' +
        '          <input type="file" id="editar_foto_perfil_input" name="editar_foto_perfil" accept="image/*" style="display:none;" onchange="previewFotoModal(this)">' +
        '        </div>' +
        '      </div>' +
        '      <div style="display:flex;flex-direction:column;gap:6px;">' +
        '        <label style="font-size:0.88rem;font-weight:600;color:#1e293b;">Nombre y Apellidos</label>' +
        '        <input type="text" name="editar_nombre" value="' + nombre + '" required style="padding:10px 14px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;width:100%;box-sizing:border-box;">' +
        '      </div>' +
        '      <div style="display:flex;flex-direction:column;gap:6px;">' +
        '        <label style="font-size:0.88rem;font-weight:600;color:#1e293b;">Correo Electr\u00F3nico</label>' +
        '        <input type="email" name="editar_email" value="' + email + '" required style="padding:10px 14px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;width:100%;box-sizing:border-box;">' +
        '      </div>' +
        '      <div style="display:flex;flex-direction:column;gap:6px;">' +
        '        <label style="font-size:0.88rem;font-weight:600;color:#1e293b;">Rol del Sistema</label>' +
        '        <select name="editar_rol" style="padding:10px 14px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;width:100%;background:#fff;">' +
        '          <option value="docente" ' + docente + '>Docente / Soporte</option>' +
        '          <option value="editor" '  + editor  + '>Editor de Contenido</option>' +
        '          <option value="admin" '   + admin   + '>Administrador Global</option>' +
        '        </select>' +
        '      </div>' +
        '    </div>' +
        '    <div style="padding:14px 26px 20px;display:flex;justify-content:flex-end;gap:10px;border-top:1px solid #e2e8f0;">' +
        '      <button type="button" onclick="cerrarModal()" style="background:#f1f5f9;color:#1e293b;border:1px solid #e2e8f0;padding:9px 20px;border-radius:8px;font-weight:600;font-size:0.9rem;cursor:pointer;">Cancelar</button>' +
        '      <button type="submit" style="background:#0e1b38;color:#fff;border:none;padding:9px 22px;border-radius:8px;font-weight:600;font-size:0.9rem;cursor:pointer;">&#128190; Guardar cambios</button>' +
        '    </div>' +
        '  </form>' +
        '</div>';

    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) cerrarModal();
    });

    document.body.appendChild(overlay);
    overlay.querySelector('input[name="editar_nombre"]').focus();
}

function cerrarModal() {
    var modal = document.getElementById('editModal');
    if (modal) modal.remove();
}

function previewFotoModal(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview = document.getElementById('preview-foto-modal');
        if (!preview) return;
        var img = document.createElement('img');
        img.id = 'preview-foto-modal';
        img.src = e.target.result;
        img.style.cssText = 'width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #3b82f6;';
        preview.parentNode.replaceChild(img, preview);
    };
    reader.readAsDataURL(input.files[0]);
}

function eliminarUsuario(id, nombre) {
    if (confirm('¿Estás seguro de que quieres eliminar al usuario "' + nombre + '"? Esta acción no se puede deshacer.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = accionUsuarios;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'eliminar_usuario';
        input.value = id;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>


        
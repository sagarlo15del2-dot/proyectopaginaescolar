<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "model/MensajeModel.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<script>window.location = "index.php#contacto";</script>';
    exit();
}

$nombre   = trim($_POST['nombre'] ?? '');
$email    = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$asunto   = trim($_POST['asunto'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');

if ($nombre === '' || $email === '' || $asunto === '' || $mensaje === '') {
    echo '<script>window.location = "index.php#contacto";</script>';
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '<script>window.location = "index.php#contacto";</script>';
    exit();
}

$resultado = MensajeModel::mdlGuardarMensaje([
    'nombre' => $nombre,
    'email' => $email,
    'telefono' => $telefono,
    'asunto' => $asunto,
    'mensaje' => $mensaje
]);

$_SESSION['contacto_estado'] = ($resultado === 'ok') ? 'ok' : 'error';

echo '<script>window.location = "index.php#contacto";</script>';
exit();
?>
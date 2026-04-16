<?php
require_once __DIR__ . '/../model/UsuarioModel.php';

header('Content-Type: application/json');

if (isset($_GET['action']) && $_GET['action'] === 'get' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    error_log("AJAX_USUARIOS: Solicitando usuario ID: $id");
    
    $usuario = UsuarioModel::mdlObtenerUsuario($id);
    
    if ($usuario) {
        error_log("AJAX_USUARIOS: Usuario encontrado: " . print_r($usuario, true));
        echo json_encode([
            'success' => true,
            'usuario' => $usuario
        ]);
    } else {
        error_log("AJAX_USUARIOS: Usuario no encontrado para ID: $id");
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ]);
    }
} else {
    error_log("AJAX_USUARIOS: Acción no válida o parámetros faltantes");
    echo json_encode([
        'success' => false,
        'message' => 'Acción no válida'
    ]);
}
?>
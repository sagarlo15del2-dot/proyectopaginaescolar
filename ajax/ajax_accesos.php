<?php
require_once __DIR__ . '/../model/PermisosModel.php';

header('Content-Type: application/json');

if (isset($_GET['action']) && $_GET['action'] === 'get' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $permisos = PermisosModel::mdlObtenerPermisos($id);

    if ($permisos !== null) {
        echo json_encode(['success' => true, 'permisos' => $permisos]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudieron cargar los permisos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
?>

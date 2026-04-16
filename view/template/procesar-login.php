<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "model/AdminModel.php";
require_once "model/UsuarioModel.php";
require_once "model/PermisosModel.php";

if(isset($_POST["usuario"]) && isset($_POST["password"])){

    $usuario  = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);

    if(empty($usuario) || empty($password)){
        echo '<script>window.location = "index.php?ruta=acceso&error=datos";</script>';
        exit();
    }

    // 1. Buscar en administradores (contraseña en texto plano)
    $admin = AdminModel::mdlIngresoAdmin($usuario, $password);

    if($admin){
        $_SESSION["accesoAdmin"]  = "ok";
        $_SESSION["nombreAdmin"] = $admin["nombre"];
        echo '<script>window.location = "index.php?ruta=admin";</script>';
        exit();
    }

    // 2. Buscar en usuarios (contraseña encriptada con bcrypt)
    $usuarioObj = UsuarioModel::mdlLoginUsuario($usuario, $password);

    if($usuarioObj){
        $permisos = PermisosModel::mdlObtenerPermisos($usuarioObj['id']);
        if(!$permisos) $permisos = [];

        $_SESSION['usuarioLogueado'] = true;
        $_SESSION['usuarioId']       = $usuarioObj['id'];
        $_SESSION['usuarioNombre']   = $usuarioObj['nombre'];
        $_SESSION['usuarioEmail']    = $usuarioObj['email'];
        $_SESSION['usuarioRol']      = $usuarioObj['rol'];
        $_SESSION['usuarioFoto']     = $usuarioObj['foto_perfil'];
        $_SESSION['usuarioPermisos'] = $permisos;

        echo '<script>window.location = "index.php?ruta=panel-usuario";</script>';
        exit();
    }

    // 3. Nadie coincidió
    $usuarioSafe = urlencode($usuario);
    echo '<script>window.location = "index.php?ruta=acceso&error=credenciales&usuario=' . $usuarioSafe . '";</script>';
}
?>
<?php
require_once "config/conexion.php";

class AdminModel {
    static public function mdlIngresoAdmin($usuario, $password) {
        $stmt = Conexion::conectar()->prepare(
            "SELECT * FROM administradores WHERE usuario = :usuario AND password = :password"
        );
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>
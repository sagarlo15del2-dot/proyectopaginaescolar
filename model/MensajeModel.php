<?php
require_once __DIR__ . '/../config/conexion.php';

class MensajeModel {
    private static function crearTablaMensajes() {
        $sql = "CREATE TABLE IF NOT EXISTS mensajes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            telefono VARCHAR(20) DEFAULT NULL,
            asunto VARCHAR(255) NOT NULL,
            mensaje TEXT NOT NULL,
            fecha_envio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            estado ENUM('no_leido','leido','respondido') DEFAULT 'no_leido'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        Conexion::conectar()->exec($sql);
    }

    public static function mdlGuardarMensaje($datos) {
        try {
            self::crearTablaMensajes();
            $db = Conexion::conectar();
            $stmt = $db->prepare(
                "INSERT INTO mensajes (nombre, email, telefono, asunto, mensaje) VALUES (?, ?, ?, ?, ?)"
            );

            $ok = $stmt->execute([
                $datos['nombre'],
                $datos['email'],
                $datos['telefono'],
                $datos['asunto'],
                $datos['mensaje']
            ]);

            return $ok ? 'ok' : 'error';
        } catch (PDOException $e) {
            error_log('MensajeModel::mdlGuardarMensaje ERROR: ' . $e->getMessage());
            return 'error';
        }
    }

    public static function mdlListarMensajes($limite = 200) {
        try {
            self::crearTablaMensajes();
            $limite = max(1, (int)$limite);
            $stmt = Conexion::conectar()->prepare(
                "SELECT id, nombre, email, telefono, asunto, mensaje, fecha_envio, estado
                 FROM mensajes
                 ORDER BY fecha_envio DESC
                 LIMIT {$limite}"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('MensajeModel::mdlListarMensajes ERROR: ' . $e->getMessage());
            return [];
        }
    }

    public static function mdlContarNoLeidos() {
        try {
            self::crearTablaMensajes();
            $stmt = Conexion::conectar()->prepare(
                "SELECT COUNT(*) AS total FROM mensajes WHERE estado = 'no_leido'"
            );
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return isset($row['total']) ? (int)$row['total'] : 0;
        } catch (PDOException $e) {
            error_log('MensajeModel::mdlContarNoLeidos ERROR: ' . $e->getMessage());
            return 0;
        }
    }

    public static function mdlMarcarLeido($id) {
        try {
            $stmt = Conexion::conectar()->prepare(
                "UPDATE mensajes SET estado = 'leido' WHERE id = ?"
            );
            return $stmt->execute([(int)$id]) ? 'ok' : 'error';
        } catch (PDOException $e) {
            error_log('MensajeModel::mdlMarcarLeido ERROR: ' . $e->getMessage());
            return 'error';
        }
    }

    public static function mdlEliminarMensaje($id) {
        try {
            $stmt = Conexion::conectar()->prepare("DELETE FROM mensajes WHERE id = ?");
            return $stmt->execute([(int)$id]) ? 'ok' : 'error';
        } catch (PDOException $e) {
            error_log('MensajeModel::mdlEliminarMensaje ERROR: ' . $e->getMessage());
            return 'error';
        }
    }
}
?>
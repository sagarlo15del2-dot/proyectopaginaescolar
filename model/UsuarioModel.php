<?php
require_once __DIR__ . '/../config/conexion.php';

class UsuarioModel {
    private static function crearTablaUsuarios() {
        $sql = "CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            rol ENUM('admin','editor','docente') NOT NULL DEFAULT 'docente',
            foto_perfil VARCHAR(255) DEFAULT NULL,
            fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $db = Conexion::conectar();
        $db->exec($sql);
    }

    static public function mdlCrearUsuario($datos) {
        try {
            self::crearTablaUsuarios();

            $db = Conexion::conectar();

            $sql = "INSERT INTO usuarios (nombre, email, password, rol, foto_perfil, fecha_creacion) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $db->prepare($sql);

            $result = $stmt->execute([
                $datos["nombre"],
                $datos["email"],
                $datos["password"],
                $datos["rol"],
                $datos["foto_perfil"]
            ]);

            if ($result) {
                // Verificar que realmente se insertó
                if ($stmt->rowCount() > 0) {
                    error_log("UsuarioModel: Usuario insertado exitosamente, filas afectadas: " . $stmt->rowCount());
                    return "ok";
                } else {
                    error_log("UsuarioModel: execute() true pero rowCount() = 0");
                    return "error";
                }
            } else {
                error_log("UsuarioModel: execute() returned false");
                return "error";
            }

        } catch (PDOException $e) {
            $errorMsg = $e->getMessage();
            error_log("UsuarioModel PDOException: " . $errorMsg);

            // Detectar email duplicado
            if (strpos($errorMsg, 'Duplicate entry') !== false && strpos($errorMsg, 'email') !== false) {
                return "duplicate_email";
            }

            return "error";
        }
    }

    static public function mdlMostrarUsuarios() {
        try {
            self::crearTablaUsuarios();
            $stmt = Conexion::conectar()->prepare("SELECT id, nombre, email, rol, foto_perfil, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("UsuarioModel::mdlMostrarUsuarios ERROR: " . $e->getMessage());
            return [];
        }
    }

    static public function mdlEditarUsuario($id, $datos) {
        try {
            $db = Conexion::conectar();
            $stmt = $db->prepare(
                "UPDATE usuarios SET nombre = ?, email = ?, rol = ?, foto_perfil = ? WHERE id = ?"
            );

            $result = $stmt->execute([
                $datos["nombre"],
                $datos["email"],
                $datos["rol"],
                $datos["foto_perfil"],
                $id
            ]);

            return $result ? "ok" : "error";
        } catch (PDOException $e) {
            error_log("UsuarioModel::mdlEditarUsuario ERROR: " . $e->getMessage());
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return "duplicate_email";
            }
            return "error";
        }
    }

    static public function mdlEliminarUsuario($id) {
        try {
            $stmt = Conexion::conectar()->prepare("DELETE FROM usuarios WHERE id = ?");
            $result = $stmt->execute([$id]);
            return $result ? "ok" : "error";
        } catch (PDOException $e) {
            error_log("UsuarioModel::mdlEliminarUsuario ERROR: " . $e->getMessage());
            return "error";
        }
    }

    static public function mdlObtenerUsuario($id) {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT id, nombre, email, rol, foto_perfil FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("UsuarioModel::mdlObtenerUsuario ERROR: " . $e->getMessage());
            return null;
        }
    }

    static public function mdlLoginUsuario($emailOrNombre, $password) {
        try {
            self::crearTablaUsuarios();
            // Buscar por email O por nombre de usuario
            $stmt = Conexion::conectar()->prepare(
                "SELECT id, nombre, email, rol, foto_perfil, password FROM usuarios WHERE email = ? OR nombre = ? LIMIT 1"
            );
            $stmt->execute([$emailOrNombre, $emailOrNombre]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            $passwordValida = false;

            if ($usuario) {
                $passwordGuardada = $usuario['password'];
                $passwordValida = password_verify($password, $passwordGuardada) || $password === $passwordGuardada;
            }

            if ($usuario && $passwordValida) {
                unset($usuario['password']); // no guardar hash en sesión
                return $usuario;
            }
            return null;
        } catch (PDOException $e) {
            error_log("UsuarioModel::mdlLoginUsuario ERROR: " . $e->getMessage());
            return null;
        }
    }
}
?>
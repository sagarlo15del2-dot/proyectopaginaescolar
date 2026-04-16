<?php
class Conexion {
    static public function conectar() {
        $host = "localhost";
        $db   = "db_paginaescolar";
        $user = "root";
        $pass = "";
        $port = "3306";

        try {
            $link = new PDO("mysql:host={$host};port={$port};dbname={$db}", $user, $pass);
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $link->exec("set names utf8");
            return $link;
        } catch (PDOException $e) {
            die("Error al conectar con la base de datos.");
        }
    }
}
?>
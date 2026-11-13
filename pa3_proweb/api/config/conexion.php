<?php
class Conexion {
    private $host = "localhost";
    private $db = "bd_productos";
    private $usuario = "root";
    private $password = "";

    public function conectar() {
        try {
            $conexion = new PDO(
                "mysql:host=" . $this->host .
                ";dbname=" . $this->db . ";charset=utf8mb4",
                $this->usuario,
                $this->password
            );

            $conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $conexion;
        } catch (PDOException $e) {
            throw new Exception("Error al conectar con la base de datos.");
        }
    }
}
?>

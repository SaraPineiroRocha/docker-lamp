<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/UD5/entregaTarea/modelo/mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/UD5/entregaTarea/modelo/pdo.php');
class Usuario {
    public $id;
    public $username;
    public $nombre;
    public $apellidos;
    public $contrasena;
    public $rol;

    // Constructor
    public function __construct($id = null, $username = null, $nombre = null, $apellidos = null, $contrasena = null, $rol = null) {
        $this->id = $id;
        $this->username = $username;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
    }

    // Getters y setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }

    public function nuevoUsuario($nombre, $apellidos, $username, $contrasena, $rol) {
        return nuevoUsuario($nombre, $apellidos, $username, $contrasena, $rol); // Usar la función de mysqli.php
    }

    // Método para actualizar un usuario
    public function actualizaUsuario()
{
    try {
        $con = conectaPDO();
        $stmt = $con->prepare("UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, username = :username, rol = :rol, contrasena = :contrasena WHERE id = :id");

        $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellidos', $this->apellidos, PDO::PARAM_STR);
        $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $this->rol, PDO::PARAM_INT);

        if (!empty($this->contrasena)) {
            $stmt->bindParam(':contrasena', password_hash($this->contrasena, PASSWORD_DEFAULT), PDO::PARAM_STR);
        } else {
            $stmt->bindParam(':contrasena', $this->contrasena, PDO::PARAM_STR);
        }

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;

    } catch (PDOException $e) {
        return false;
    } finally {
        $con = null;
    }
}


    // Método para obtener la lista de usuarios
    public function listaUsuarios() {
        return listaUsuarios(); // Usar la función de mysqli.php para obtener todos los usuarios
    }

    // Método para buscar un usuario
    public function buscaUsuario($id) {
        return buscaUsuario($id); // Usar la función de mysqli.php
    }

    public function borraUsuario($id) {
        return borraUsuario($id); // Usar la función de mysqli.php
    }
}
?>

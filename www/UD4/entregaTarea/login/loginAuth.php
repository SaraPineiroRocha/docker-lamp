<?php
session_start();
require_once('../modelo/pdo.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $conexion = conectaPDO();

        $sql = "SELECT id, username, contrasena, rol FROM usuarios WHERE username = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña
        if ($username == $usuario['username'] && $password == $usuario['contrasena']) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['rol'] = $usuario['rol'];
            header("Location: login.php");
            exit();
        } else {
            header("Location: login.php?error=Usuario o contraseña incorrectos");
            exit();
        }
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }
}
?>

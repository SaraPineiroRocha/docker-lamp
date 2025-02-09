<?php
session_start();
require_once('../modelo/pdo.php');

if (!isset($_GET['id']) || !isset($_GET['tarea'])) {
    header("Location: tareas.php");
    exit();
}

$id = $_GET['id'];
$id_tarea = $_GET['tarea'];

try {
    $conexion = new PDO("mysql:host=localhost;dbname=tareas", "test", "root");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener ruta del archivo
    $stmt = $conexion->prepare("SELECT file FROM ficheros WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $archivo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($archivo && file_exists("../" . $archivo['file'])) {
        unlink("../" . $archivo['file']);
    }

    // Eliminar de la base de datos
    $stmt = $conexion->prepare("DELETE FROM ficheros WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: tarea.php?id=$id_tarea");
    exit();
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>

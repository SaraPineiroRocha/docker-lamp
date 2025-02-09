<?php
session_start();
require_once('../modelo/pdo.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['archivo']) || !isset($_POST['id_tarea'])) {
    header("Location: tarea.php");
    exit();
}

$id_tarea = $_POST['id_tarea'];
$descripcion = $_POST['descripcion'] ?? '';
$archivo = $_FILES['archivo'];
$extensiones_permitidas = ['jpg', 'png', 'pdf', 'jpeg'];
$max_tamano = 20 * 1024 * 1024; // 20 MB

// Validar tamaño
if ($archivo['size'] > $max_tamano) {
    die("Error: El archivo excede los 20MB permitidos.");
}

// Validar extensión
$ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
if (!in_array(strtolower($ext), $extensiones_permitidas)) {
    die("Error: Formato no permitido. Solo JPG, PNG y PDF.");
}

// Generar nombre único
$nombre_unico = uniqid() . "." . $ext;
$ruta_destino = "../files/" . $nombre_unico;

if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
    die("Error al subir el archivo.");
}

// Guardar en la base de datos
try {
    $conexion = conectaPDO();

    $sql = "INSERT INTO ficheros (nombre, file, descripcion, id_tarea) VALUES (:nombre, :file, :descripcion, :id_tarea)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':nombre' => $archivo['name'],
        ':file' => "files/$nombre_unico",
        ':descripcion' => $descripcion,
        ':id_tarea' => $id_tarea
    ]);

    header("Location: tarea.php?id=$id_tarea");
    exit();
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
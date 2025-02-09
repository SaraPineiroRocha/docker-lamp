<?php
session_start();
require_once('../modelo/pdo.php');

if (!isset($_GET['id'])) {
    header("Location: tareas.php");
    exit();
}

$id_tarea = $_GET['id'];

try {

    $conexion = conectaPDO();

    // Obtener detalles de la tarea
    $stmt = $conexion->prepare("SELECT * FROM tareas WHERE id = :id");
    $stmt->bindParam(':id', $id_tarea, PDO::PARAM_INT);
    $stmt->execute();
    $tarea = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener archivos adjuntos
    $stmt = $conexion->prepare("SELECT * FROM ficheros WHERE id_tarea = :id_tarea");
    $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
    $stmt->execute();
    $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Detalle de Tarea</h2>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($tarea['titulo']) ?></p>
        <p><strong>Descripción:</strong> <?= htmlspecialchars($tarea['descripcion']) ?></p>

        <h3>Archivos Adjuntos</h3>
        <ul>
            <?php foreach ($archivos as $archivo): ?>
                <li>
                    <?= htmlspecialchars($archivo['nombre']) ?> 
                    <a href="<?= htmlspecialchars($archivo['file']) ?>" download>Descargar</a>
                    <a href="borrarArchivo.php?id=<?= $archivo['id'] ?>&tarea=<?= $id_tarea ?>" class="text-danger">Borrar</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <a href="subidaFichForm.php?id_tarea=<?= $id_tarea ?>" class="btn btn-primary">Añadir Archivo</a>
        <a href="tareas.php" class="btn btn-secondary">Volver</a>
    </div>
</body>
</html>

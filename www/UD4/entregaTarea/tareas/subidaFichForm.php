<?php
session_start();
if (!isset($_GET['id_tarea'])) {
    header("Location: tareas.php");
    exit();
}

$id_tarea = $_GET['id_tarea'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Archivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Subir Archivo</h2>
        <form action="subidaFichProc.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_tarea" value="<?= $id_tarea ?>">
            <div class="mb-3">
                <label class="form-label">Archivo</label>
                <input type="file" name="archivo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcion" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Subir</button>
        </form>
    </div>
</body>
</html>
<?php
// Incluir archivo de configuración de base de datos
require_once('utils.php');

// Comprobar si se pasó el id por la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $servername = 'localhost';
    $dbname = 'tareas';
    $username = 'root';
    $password = '';

    // Obtener los datos de la tarea desde la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM tareas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tarea = $result->fetch_assoc();

    if ($tarea) {
        // Si existe la tarea, mostrar los datos en el formulario
        $titulo = $tarea['titulo'];
        $descripcion = $tarea['descripcion'];
        $estado = $tarea['estado'];
    } else {
        // Si no existe la tarea, redirigir o mostrar error
        echo "Tarea no encontrada.";
        exit;
    }
} else {
    echo "ID de tarea no proporcionado.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once('header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include_once('menu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Editar Tarea</h2>
            </div>

            <div class="container justify-content-between">
                <form action="editaTarea.php" method="POST" class="mb-5 w-50">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($descripcion); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="en_proceso" <?php echo ($estado == 'en_proceso') ? 'selected' : ''; ?>>En Proceso</option>
                            <option value="pendiente" <?php echo ($estado == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="completada" <?php echo ($estado == 'completada') ? 'selected' : ''; ?>>Completada</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>

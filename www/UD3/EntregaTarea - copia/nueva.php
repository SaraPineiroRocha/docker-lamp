<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD2. Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            
            <?php include_once('menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Gestión de tarea</h2>
                </div>

                <div class="container justify-content-between">
                <?php
require_once('utils.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = trim($_POST['descripcion']);
    $estado = trim($_POST['estado']);
    $id_usuario = intval($_POST['id_usuario']);

    $errores = [];

    // Validación
    if (empty($descripcion)) {
        $errores[] = "La descripción no puede estar vacía.";
    }
    if (!in_array($estado, ['en_proceso', 'pendiente', 'completada'])) {
        $errores[] = "El estado no es válido.";
    }
    if ($id_usuario <= 0) {
        $errores[] = "El usuario seleccionado no es válido.";
    }

    if (empty($errores)) {
        // Guardar tarea en la base de datos
        if (guardarTarea($descripcion, $estado, $id_usuario)) {
            echo "<p class='text-success'>La tarea se creó correctamente.</p>";
        } else {
            echo "<p class='text-danger'>Ocurrió un error al guardar la tarea.</p>";
        }
    } else {
        foreach ($errores as $error) {
            echo "<p class='text-danger'>$error</p>";
        }
    }
}

function guardarTarea($descripcion, $estado, $id_usuario) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tareas";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO tareas (descripcion, estado, id_usuario) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $descripcion, $estado, $id_usuario);

    $resultado = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $resultado;
}
?>


                </div>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
    
</body>
</html>

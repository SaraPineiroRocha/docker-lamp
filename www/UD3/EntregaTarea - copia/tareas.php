<!-- tareas.php -->
<?php
// Configuración de la base de datos usando PDO
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tareas";

try {
    // Crear conexión con PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
    die();
}

// Variables para los filtros
$usuario = "";
$estado = "";

// Verificar si se recibió información del formulario
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['username'])) {
    // Filtrar los datos recibidos
    $usuario = htmlspecialchars($_GET['username']);
    $estado = isset($_GET['estado']) ? htmlspecialchars($_GET['estado']) : '';

    // Construir la consulta SQL
    $sql = "SELECT t.id, t.descripcion, t.estado, u.username
            FROM tareas t
            JOIN usuarios u ON t.id_usuario = u.id
            WHERE u.username LIKE :usuario";

    if ($estado != '') {
        $sql .= " AND t.estado = :estado";
    }

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Enlazar parámetros
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

    if ($estado != '') {
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    }

    // Ejecutar la consulta
    $stmt->execute();
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si no hay filtro, mostrar todas las tareas
    $stmt = $conn->prepare("SELECT t.id, t.descripcion, t.estado, u.username
                            FROM tareas t
                            JOIN usuarios u ON t.id_usuario = u.id");
    $stmt->execute();
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            
            <?php include_once('menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Tareas de Usuario</h2>
                </div>

                <div class="container justify-content-between">
                    <?php if (count($tareas) > 0): ?>
                        <div class="table">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Identificador</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Usuario</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tareas as $tarea): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($tarea['id']); ?></td>
                                            <td><?php echo htmlspecialchars($tarea['descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($tarea['estado']); ?></td>
                                            <td><?php echo htmlspecialchars($tarea['username']); ?></td>
                                            <td>
                                            <a href="editaTareaForm.php?id=<?php echo $tarea['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                            <a href="borraTarea.php?id=<?php echo $tarea['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres borrar esta tarea?')">Borrar</a>

                                          </td>
                                        </tr>
                                        </tr>
                                        
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No se encontraron tareas para los filtros seleccionados.</p>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>

</body>
</html>

<?php
// Cerrar la conexión
$conn = null;
?>

<?php
// Configuración de la conexión PDO
$host = 'localhost';
$dbname = 'tareas';
$username = 'root';
$password = '';

// Crear conexión PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificar si se pasa el ID por URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar la base de datos para obtener los datos del usuario
    $sql = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si no se encuentra el usuario
    if (!$usuario) {
        die("Usuario no encontrado.");
    }
} else {
    die("ID de usuario no válido.");
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
                <h2>Editar Usuario</h2>
            </div>

            
<div class="container justify-content-between">
    <form action="editaUsuario.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']); ?>">

        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['nombre']); ?>" required><br>

        <label for="apellidos" class="form-label">Apellidos:</label>
        <input type="text" class="form-control" name="apellidos" id="apellidos" value="<?= htmlspecialchars($usuario['apellidos']); ?>" required><br>

        <label for="username" class="form-label">Username:</label>
        <input type="text" class="form-control" name="username" id="username" value="<?= htmlspecialchars($usuario['username']); ?>" required><br>

        <label for="contraseña" class="form-label">Contraseña:</label>
        <input type="password" class="form-control" name="contraseña" id="contraseña"><br>

        <input type="submit" class="btn btn-primary" value="Actualizar Usuario">
    </form>

            </div>
        </main>
    </div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html> 
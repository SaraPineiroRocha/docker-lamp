<?php
// Incluir archivo de configuración de la base de datos
require_once('utils.php');

$servername = 'localhost'; // Cambia si el host no es localhost
$dbname = 'tareas';  // Nombre de la base de datos
$username = 'root';  // Tu usuario de MySQL
$password = '';      // Tu contraseña de MySQL


// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la lista de usuarios
$sql = "SELECT id, username, nombre, apellidos FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once('header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include_once('menu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Usuarios</h2>
            </div>

            <div class="container justify-content-between">
                <div class="table">
                    <table class="table table-striped table-hover">
                        <thead class="thead">
                            <tr>
                                <th>Identificador</th>
                                <th>Username</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Si hay resultados, mostrar los usuarios
                            if ($result->num_rows > 0) {
                                while($usuario = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $usuario['id'] . '</td>';
                                    echo '<td>' . htmlspecialchars($usuario['username']) . '</td>';
                                    echo '<td>' . htmlspecialchars($usuario['nombre']) . '</td>';
                                    echo '<td>' . htmlspecialchars($usuario['apellidos']) . '</td>';
                                    echo '<td>
                                            <a href="editaUsuarioForm.php?id=' . $usuario['id'] . '" class="btn btn-sm btn-warning">Editar</a>
                                            <a href="borraUsuario.php?id=' . $usuario['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Estás seguro de que quieres borrar este usuario?\')">Borrar</a>
                                          </td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5">No hay usuarios registrados</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>

<?php
try {
    // Crear la conexión sin indicar la base de datos inicialmente
    $conexion = new mysqli('localhost', 'root', '', ''); // Cambiar las credenciales según corresponda
    if ($conexion->connect_error) {
        throw new Exception('Error en la conexión: ' . $conexion->connect_error);
    }

    // Crear la base de datos 'tareas' si no existe
    $sql = 'CREATE DATABASE IF NOT EXISTS tareas';
    if ($conexion->query($sql)) {
        $message = 'Base de datos "tareas" creada o ya existía.';
    } else {
        throw new Exception('Error creando la base de datos: ' . $conexion->error);
    }

    // Seleccionar la base de datos 'tareas'
    $conexion->select_db('tareas');

    // Crear la tabla 'usuarios' si no existe
    $sql = 'CREATE TABLE IF NOT EXISTS usuarios (
        id INT(6) AUTO_INCREMENT PRIMARY KEY, 
        username VARCHAR(50) NOT NULL, 
        nombre VARCHAR(50) NOT NULL,
        apellidos VARCHAR(100) NOT NULL,
        contraseña VARCHAR(100) NOT NULL
    )';
    if ($conexion->query($sql)) {
        $message .= '<br>Tabla "usuarios" creada o ya existía.';
    } else {
        throw new Exception('Error creando la tabla "usuarios": ' . $conexion->error);
    }

    // Crear la tabla 'tareas' si no existe
    $sql = 'CREATE TABLE IF NOT EXISTS tareas (
        id INT(6) AUTO_INCREMENT PRIMARY KEY, 
        titulo VARCHAR(50) NOT NULL, 
        descripcion VARCHAR(250) NOT NULL,
        estado ENUM("pendiente", "completado", "en progreso") NOT NULL DEFAULT "pendiente",
        id_usuario INT(6) NOT NULL,
        CONSTRAINT fk_usuarios_tareas FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
    )';
    if ($conexion->query($sql)) {
        $message .= '<br>Tabla "tareas" creada o ya existía.';
    } else {
        throw new Exception('Error creando la tabla "tareas": ' . $conexion->error);
    }
} catch (Exception $e) {
    // Manejar errores de conexión y ejecución
    $message = 'Error: ' . $e->getMessage();
} finally {
    // Cerrar la conexión si se estableció
    if (isset($conexion) && $conexion->connect_errno === 0) {
        $conexion->close();
        $message .= '<br>Conexión cerrada.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicialización de Base de Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <?php include_once('menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Resultado de la Inicialización</h2>
                </div>

                <div class="container justify-content-between">
                    <div class="alert alert-info" role="alert">
                        <?php echo $message; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>

</body>
</html>

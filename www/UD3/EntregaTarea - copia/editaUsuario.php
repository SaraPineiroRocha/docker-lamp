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

// Verificar si los datos del formulario han sido enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Filtrar y validar los datos del formulario
    $id = $_POST['id'];
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $apellidos = filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $contraseña = $_POST['contraseña'];

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($apellidos) || empty($username)) {
        echo "Todos los campos son obligatorios.";
    } else {
        // Preparar la consulta de actualización
        if (!empty($contraseña)) {
            // Si se proporciona una nueva contraseña, encriptarla
            $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, username = :username, contraseña = :contraseña WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':contraseña', $contraseña_hash, PDO::PARAM_STR);  // Asegurarse de vincular el parámetro de la contraseña
        } else {
            // Si no se proporciona contraseña, no la actualizamos
            $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, username = :username WHERE id = :id";
            $stmt = $pdo->prepare($sql);
        }

        // Vincular los demás parámetros
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta de actualización
        try {
            $stmt->execute();
            echo "Usuario actualizado exitosamente.";
        } catch (PDOException $e) {
            echo "Error al actualizar el usuario: " . $e->getMessage();
        }
    }
} else {
    echo "Método de solicitud no permitido.";
}

// Cerrar la conexión
$pdo = null;
?>
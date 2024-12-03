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

    try {
        // Iniciar transacción para borrar el usuario y sus tareas
        $pdo->beginTransaction();

        // Borrar las tareas asociadas al usuario
        $sql = "DELETE FROM tareas WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Borrar el usuario de la tabla usuarios
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar la transacción
        $pdo->commit();

        echo "Usuario y sus tareas fueron borrados exitosamente.";
    } catch (PDOException $e) {
        // Si ocurre un error, revertir la transacción
        $pdo->rollBack();
        echo "Error al borrar el usuario: " . $e->getMessage();
    }
} else {
    echo "ID de usuario no válido o no proporcionado.";
}

// Cerrar la conexión
$pdo = null;
?>

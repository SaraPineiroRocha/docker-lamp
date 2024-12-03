<?php
// Incluir archivo de configuración para la conexión a la base de datos
require_once('utils.php');

// Verificar que se ha pasado el parámetro 'id' en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_tarea = $_GET['id'];

    // Definir las variables de conexión
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tareas";

    // Crear la conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar si la conexión funciona
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consultar si la tarea existe
    $sql = "SELECT id FROM tareas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_tarea);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si la tarea existe, proceder a eliminarla
        $delete_sql = "DELETE FROM tareas WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id_tarea);

        if ($delete_stmt->execute()) {
            echo "<p>La tarea ha sido eliminada correctamente.</p>";
        } else {
            echo "<p class='error'>Error al eliminar la tarea. Inténtalo de nuevo.</p>";
        }
        $delete_stmt->close();
    } else {
        echo "<p class='error'>La tarea no existe.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p class='error'>No se ha proporcionado un ID válido.</p>";
}
?>

<?php
// Incluir archivo de configuración de base de datos
require_once('utils.php');

// Verificar que el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    // Validación simple
    if (empty($titulo) || empty($descripcion) || empty($estado)) {
        echo '<p class="error">Todos los campos son obligatorios.</p>';
    } else {
        // Crear conexión a la base de datos
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Preparar la consulta para actualizar la tarea
        $sql = "UPDATE tareas SET titulo = ?, descripcion = ?, estado = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $titulo, $descripcion, $estado, $id);

        // Ejecutar la consulta y verificar el resultado
        if ($stmt->execute()) {
            echo '<p class="success">La tarea ha sido actualizada correctamente.</p>';
        } else {
            echo '<p class="error">Error al actualizar la tarea: ' . $conn->error . '</p>';
        }

        // Cerrar la conexión
        $stmt->close();
        $conn->close();
    }
} else {
    echo '<p class="error">Método no permitido.</p>';
}
?>

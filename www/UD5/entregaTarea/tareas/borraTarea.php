<?php
require_once('../login/sesiones.php');
require_once('../modelo/mysqli.php');
require_once '/var/www/html/UD5/entregaTarea/clases/Tarea.php';
require_once '/var/www/html/UD5/entregaTarea/clases/Usuario.php';

$response = 'error';
$messages = array();
$location = 'tareas.php';

// Recupera el ID de la tarea a eliminar
if (isset($_GET['id'])) {
    $id_tarea = $_GET['id'];

    // Elimina los ficheros asociados con esta tarea
    try {
        $con = conectaPDO(); // Usamos PDO para la conexión a la base de datos
        $sqlBorrarFicheros = 'DELETE FROM ficheros WHERE id_tarea = :id_tarea';
        $stmt = $con->prepare($sqlBorrarFicheros);
        $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
        $stmt->execute(); // Ejecuta la consulta de eliminación de ficheros
    } catch (PDOException $e) {
        echo 'Error al borrar los ficheros: ' . $e->getMessage();
        exit();
    }
    try {
        $con = conectaPDO(); // Usamos PDO para la conexión a la base de datos
        $sqlBorrarTarea = 'DELETE FROM tareas WHERE id = :id_tarea';
        $stmt = $con->prepare($sqlBorrarTarea);
        $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
        $stmt->execute(); // Ejecuta la consulta de eliminación de tarea

        header("Location: listaTareas.php"); // Redirige a la lista de tareas después de eliminar
        exit();
    } catch (PDOException $e) {
        echo 'Error al borrar la tarea: ' . $e->getMessage();
        exit();
    }
} else {
    echo 'No se ha proporcionado un ID de tarea.';
    exit();
}
?>
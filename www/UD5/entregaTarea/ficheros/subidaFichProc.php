<?php
require_once('../login/sesiones.php');
require_once('../modelo/mysqli.php');
require_once('../modelo/pdo.php');
require_once '/var/www/html/UD5/entregaTarea/clases/Fichero.php';
require_once '/var/www/html/UD5/entregaTarea/clases/FicherosDBImp.php';

$status = 'error';
$messages = array();
$id_tarea = 0;

if (!empty($_POST) && isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
    $nombre = $_FILES['archivo']['name'];
    $tmp_name = $_FILES['archivo']['tmp_name'];
    $id_tarea = $_POST['id_tarea'];
    $descripcion = $_POST['descripcion'];

    $upload_dir = '../uploads/';
    $file_path = $upload_dir . basename($nombre);

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    
    if (move_uploaded_file($tmp_name, $file_path)) {
        try {
            // Crear objeto Fichero
            $fichero = new Fichero(null, $nombre, $file_path, $descripcion, $id_tarea);
            
            // Usar la clase FicherosDBImp para guardar el fichero
            $ficherosDB = new FicherosDBImp();
            $ficherosDB->nuevoFichero($fichero);
            $status = 'success';
            array_push($messages, 'Archivo subido correctamente.');

        } catch (DatabaseException $e) {
            array_push($messages, 'Error al subir el archivo: ' . $e->getMessage());
            error_log("Error en método: " . $e->getMethod() . ", SQL: " . $e->getSql());
        }
    } else {
        array_push($messages, 'No se pudo mover el archivo.');
    }
} else {
    array_push($messages, 'El archivo no es válido o no se ha subido correctamente.');
}

$_SESSION['status'] = $status;
$_SESSION['messages'] = $messages;
header("Location: subidaFichForm.php?id=" . $id_tarea);
?>

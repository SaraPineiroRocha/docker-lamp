<?php
require_once('../login/sesiones.php');
require_once('../modelo/mysqli.php');
require_once('../modelo/pdo.php');
require_once '/var/www/html/UD5/entregaTarea/clases/Fichero.php';

$status = 'error';
$messages = array();
$id_tarea = 0;

if (!empty($_GET)) {
    $id = $_GET['id'];
    $archivo = buscaFichero($id);  // Esto devuelve un objeto Fichero
    if (!empty($id) && $archivo) {
        $id_tarea = $archivo->getTarea();  // Accedemos a la tarea usando el getter
        if (checkAdmin() || esPropietarioTarea($_SESSION['usuario']['id'], $id_tarea)) {
            $ruta = '../' . $archivo->getFile();  // Usamos el getter para obtener la ruta del archivo
            $borrado = borrarArchivo($ruta);
            if ($borrado) {
                $borrado = borraFichero($archivo->getId());  // Borramos el fichero usando su ID
            }

            if ($borrado) {
                $status = 'success';
                array_push($messages, 'Archivo borrado correctamente.');
            } else {
                array_push($messages, 'No se pudo borrar el archivo.');
            }
        } else {
            array_push($messages, 'No tienes permisos sobre este archivo.');
        }
    } else {
        array_push($messages, 'No se pudo recuperar la información del archivo.');
    }
} else {
    array_push($messages, 'Debes acceder a través del listado de tareas.');
}

$_SESSION['status'] = $status;
$_SESSION['messages'] = $messages;
header("Location: ../tareas/tarea.php?id=" . $id_tarea);

function borrarArchivo($archivo)
{
    return (file_exists($archivo) && unlink($archivo));
}
?>

<?php
ob_start(); // Iniciar almacenamiento en búfer de salida

require_once '../login/sesiones.php';
require_once '../utils.php';
require_once '../modelo/mysqli.php';  // Incluye el archivo con la función
require_once '/var/www/html/UD5/entregaTarea/clases/Tarea.php';
require_once '/var/www/html/UD5/entregaTarea/clases/Usuario.php';  // Asegúrate de incluir la clase Usuario

$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$estado = $_POST['estado'];
$id_usuario = $_POST['id_usuario'];

$response = 'error';
$messages = array();
$location = 'nuevaForm.php';

$error = false;

if (!checkAdmin()) {
    $id_usuario = $_SESSION['usuario']['id']; // Si no es admin, tomamos el id de la sesión
}

// Verificar título
if (!validarCampoTexto($titulo)) {
    $error = true;
    array_push($messages, 'El campo titulo es obligatorio y debe contener al menos 3 caracteres.');
}

// Verificar descripción
if (!validarCampoTexto($descripcion)) {
    $error = true;
    array_push($messages, 'El campo descripcion es obligatorio y debe contener al menos 3 caracteres.');
}

// Verificar estado
if (!validarCampoTexto($estado)) {
    $error = true;
    array_push($messages, 'El campo estado es obligatorio.');
}

// Verificar id_usuario
if (!esNumeroValido($id_usuario)) {
    $error = true;
    array_push($messages, 'El campo usuario es obligatorio.');
}

if (!$error) {

    $usuario = buscaUsuarioMysqli($id_usuario); 

    if ($usuario) {
        
        $tarea = new Tarea(null, $titulo, $descripcion, $estado, $usuario);  // El id es null ya que es una nueva tarea
        
        if ($tarea instanceof Tarea) {
            // Guardar tarea
            $resultado = nuevaTarea($tarea);  
            if ($resultado[0]) {
                $response = 'success';
                array_push($messages, 'Tarea guardada correctamente.');
            } else {
                $response = 'error';
                array_push($messages, 'Ocurrió un error guardando la tarea: ' . $resultado[1] . '.');
            }
        } else {
            $response = 'error';
            array_push($messages, 'Error al crear el objeto tarea.');
        }
    } else {
    
        $response = 'error';
        array_push($messages, 'No se encontró el usuario.');
    }
}

$_SESSION['status'] = $response;
$_SESSION['messages'] = $messages;

header("Location: $location");
exit;
?>

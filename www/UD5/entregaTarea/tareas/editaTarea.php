<?php
require_once('../login/sesiones.php');
require_once('../utils.php');
require_once '/var/www/html/UD5/entregaTarea/clases/Tarea.php';
require_once '/var/www/html/UD5/entregaTarea/clases/Usuario.php';

$id = $_POST['id'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$estado = $_POST['estado'];
$id_usuario = $_POST['id_usuario'];

$response = 'error';
$messages = array();

$error = false;
$location = 'editaTareaForm.php?id=' . $id;

if (!checkAdmin()) $id_usuario = $_SESSION['usuario']['id'];

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
    // Recuperar el usuario correspondiente al id_usuario
    require_once('../modelo/mysqli.php');
    
    $usuario = buscaUsuarioMysqli($id_usuario);

    // Verifica si el usuario existe
    if (!$usuario) {
        $error = true;
        array_push($messages, 'El usuario especificado no existe.');
    } else {
    
        $usuarioObj = new Usuario(
            $usuario->getId(), 
            $usuario->getUsername(), 
            $usuario->getNombre(), 
            $usuario->getApellidos(), 
            $usuario->getContrasena(), 
            $usuario->getRol()
        );


        $tarea = new Tarea($id, $titulo, $descripcion, $estado, $usuarioObj);
    }
}


if (!$error) {
    $resultado = actualizaTarea($tarea);

    
    if ($resultado[0]) {
        $response = 'success';
        array_push($messages, 'Tarea actualizada correctamente.');
        $location = 'tareas.php';
    } else {
        $response = 'error';
        array_push($messages, 'Ocurrió un error actualizando la tarea: ' . $resultado[1] . '.');
    }
}

// Establecer el estado y los mensajes en la sesión
$_SESSION['status'] = $response;
$_SESSION['messages'] = $messages;

// Redirigir a la página correspondiente
header("Location: $location");
exit();
?>
<?php
require_once('../login/sesiones.php');
if (!checkAdmin()) redirectIndex();

require_once '/var/www/html/UD5/entregaTarea/clases/Usuario.php';
require_once('../utils.php');

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$username = $_POST['username'];
$contrasena = $_POST['contrasena'];
$rol = $_POST['rol'];
$error = false;

$message = 'Error actualizando el usuario.';

// Verificar nombre
if (!validarCampoTexto($nombre)) {
    $error = true;
    $message = 'El campo nombre es obligatorio y debe contener al menos 3 caracteres.';
}
// Verificar apellidos
if (!$error && !validarCampoTexto($apellidos)) {
    $error = true;
    $message = 'El campo apellidos es obligatorio y debe contener al menos 3 caracteres.';
}
// Verificar username
if (!$error && !validarCampoTexto($username)) {
    $error = true;
    $message = 'El campo username es obligatorio y debe contener al menos 3 caracteres.';
}
// Verificar contrasena
if (!$error && !empty($contrasena) && !validaContrasena($contrasena)) {
    $error = true;
    $message = 'La contraseña debe ser compleja.';
}

if (!$error) {
    $usuario = new Usuario();
$usuario->setId($id);
$usuario->setNombre(filtraCampo($nombre));
$usuario->setApellidos(filtraCampo($apellidos));
$usuario->setUsername(filtraCampo($username));
$usuario->setRol($rol);

if (!empty($contrasena)) {
    $usuario->setContrasena($contrasena);
}


require_once('../modelo/pdo.php');
$resultado = actualizaUsuario($usuario);

if ($resultado) {
    $message = 'Usuario actualizado correctamente.';
} else {
    $message = 'Ocurrió un error actualizando el usuario.';
    $error = true;
}
}

$status = $error ? 'error' : 'success';
header("Location: editaUsuarioForm.php?id=$id&status=$status&message=$message");
exit;
?>

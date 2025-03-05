<?php
require_once('../login/sesiones.php');
require_once('../modelo/mysqli.php');
require_once('../modelo/pdo.php');
require_once '/var/www/html/UD5/entregaTarea/clases/Fichero.php';

$status = 'error';
$messages = array();
$id_tarea = 0;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    $id_tarea = $_GET['id'];

    list($tareaEncontrada, $tarea) = buscaTarea($id_tarea);

    if (!$tareaEncontrada) {
        array_push($messages, 'No se pudo recuperar la información de la tarea.');
    } else {
    }
} else {
    array_push($messages, 'ID de tarea inválido.');
}

$_SESSION['status'] = $status;
$_SESSION['messages'] = $messages;

if (!empty($messages)) {
    header("Location: ../tareas/tareas.php");
    exit();
}

?>

<form action="subidaFichProc.php" method="post" enctype="multipart/form-data">
    <h2>Subir nuevo archivo para la tarea: <?php echo htmlspecialchars($tarea->getTitulo()); ?></h2>
    <input type="hidden" name="id_tarea" value="<?php echo $id_tarea; ?>">
    
    <label for="nombre">Nombre del archivo:</label>
    <input type="text" id="nombre" name="nombre" required>
    
    <label for="descripcion">Descripción del archivo:</label>
    <textarea id="descripcion" name="descripcion" required></textarea>
    
    <label for="file">Seleccionar archivo:</label>
    <input type="file" id="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.txt,.docx" required>
    
    <button type="submit">Subir archivo</button>
</form>

<?php
// Mostrar mensajes si hay errores
if (!empty($messages)) {
    foreach ($messages as $message) {
        echo "<p>$message</p>";
    }
}
?>

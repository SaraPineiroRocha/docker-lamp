<?php
require_once('../login/sesiones.php');
require_once '/var/www/html/UD5/entregaTarea/clases/Tarea.php';
require_once '/var/www/html/UD5/entregaTarea/clases/Usuario.php';
?>
<?php include_once('../vista/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include_once('../vista/menu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Actualizar tarea</h2>
                <?php include_once('../vista/erroresSession.php'); ?>
            </div>

            <div class="container justify-content-between">
                <form action="editaTarea.php" method="POST" class="mb-5 w-50">
                    <?php
                    require_once('../modelo/mysqli.php');
                    if (!empty($_GET)) {
                        $id = $_GET['id'];
                        if (!empty($id)) {
                            if (checkAdmin() || esPropietarioTarea($_SESSION['usuario']['id'], $id)) {
                                
                                list($exito, $tareaData) = buscaTarea($id);
                                if ($exito) {
                            
                                    $tarea = $tareaData;
                                    ?>
                                    <input type="hidden" name="id" value="<?php echo $tarea->getId(); ?>">
                                    <?php include_once('formTarea.php'); ?>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                    <?php
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">No se pudo recuperar la información de la tarea.</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger" role="alert">No tienes permisos sobre esta tarea.</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger" role="alert">No se pudo recuperar la información de la tarea.</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Debes acceder a través del listado de tareas.</div>';
                    }
                    ?>
                </form>

            </div>
        </main>
    </div>
</div>

<?php include_once('../vista/footer.php'); ?>

</body>
</html>

<?php
    require_once('../login/sesiones.php');
    if (!checkAdmin()) redirectIndex();

    require_once '/var/www/html/UD5/entregaTarea/clases/Usuario.php'; // Asegúrate de que la ruta es correcta
?>
<?php include_once('../vista/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include_once('../vista/menu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Usuarios</h2>
                <?php include_once ('../vista/erroresGet.php'); ?>
            </div>

            <div class="container justify-content-between">
            <?php
                $usuario = new Usuario(); // Instanciar la clase Usuario
                $resultado = $usuario->listaUsuarios(); // Obtener la lista de usuarios
                if ($resultado[0]) {
            ?>
                <div class="table">
                    <table class="table table-sm table-striped table-hover">
                        <thead class="thead">
                            <tr>                            
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $lista = $resultado[1];
                                if (count($lista) > 0) {
                                    foreach ($lista as $usuario) {
                                        echo '<tr>';
                                        echo '<td>' . $usuario->id . '</td>'; // Acceso con -> en lugar de []
                                        echo '<td>' . $usuario->nombre . '</td>'; // Acceso con -> en lugar de []
                                        echo '<td>' . $usuario->apellidos . '</td>'; // Acceso con -> en lugar de []
                                        echo '<td>' . $usuario->username . '</td>'; // Acceso con -> en lugar de []
                                        echo '<td>' . ($usuario->rol == 1 ? 'administrador' : '') . '</td>'; // Acceso con -> en lugar de []
                                        echo '<td>';
                                        echo '<a class="btn btn-sm btn-outline-success" href="editaUsuarioForm.php?id=' . $usuario->id . '" role="button">Editar</a>';
                                        echo '<a class="btn btn-sm btn-outline-danger ms-2" href="borraUsuario.php?id=' . $usuario->id . '" role="button">Borrar</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="100">No hay usuarios</td></tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
                } else {
                    echo '<div class="alert alert-warning" role="alert">' . $resultado[1] . '</div>';
                }
            ?>
            </div>
        </main>
    </div>
</div>

<?php include_once('../vista/footer.php'); ?>

</body>
</html>

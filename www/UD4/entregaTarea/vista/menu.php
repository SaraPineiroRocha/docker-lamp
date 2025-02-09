<?php
$rol = $_SESSION['rol'] ?? null;
$tema = $_COOKIE['tema'] ?? 'light';
$bgClass = ($tema === 'light') ? 'bg-light' : ''; 
?>
<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/UD4/entregaTarea/index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/UD4/entregaTarea/init.php">Inicializar (mysqli)</a>
            </li>
            <?php if ($rol === 1): ?>  
                <li class="nav-item">
                    <a class="nav-link" href="/UD4/entregaTarea/usuarios/usuarios.php">Lista de usuarios (PDO)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/UD4/entregaTarea/usuarios/nuevoUsuarioForm.php">Nuevo usuario (PDO)</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="/UD4/entregaTarea/tareas/tareas.php">Lista de tareas (mysqli)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/UD4/entregaTarea/tareas/nuevaForm.php">Nueva tarea (mysqli)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/UD4/entregaTarea/tareas/buscaTareas.php">Buscador de tareas (PDO)</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tareas.php">Mis Tareas</a>
            </li>
            <li class="nav-item">
            <form action="./vista/tema.php" class="m-3 w-50">
                <select id="tema" name="tema" class="form-select mb-2" aria-label="Selector de tema">
                <option value="light" selected> Claro</option>
                <option value="dark">Oscuro</option>
                <option value="auto">Automático</option>
            </select>
            <button type="submit" class="btn btn-primary w-100">Aplicar</button>
            </form>
            </li>

            <li class="nav-item">
                <a class="nav-link text-danger" href="/UD4/entregaTarea/login/logout.php">Salir</a>
            </li>
        </ul>
    </div>
</nav>

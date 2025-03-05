<?php

require_once '/var/www/html/UD5/entregaTarea/clases/Usuario.php';
require_once '/var/www/html/UD5/entregaTarea/clases/Tarea.php';
require_once '/var/www/html/UD5/entregaTarea/clases/Fichero.php';

function conecta($host, $user, $pass, $db)
{
    $conexion = new mysqli($host, $user, $pass, $db);
    return $conexion;
}

function conectaTareas()
{
    $host = $_ENV['DATABASE_HOST'];
    $user = $_ENV['DATABASE_USER'];
    $pass = $_ENV['DATABASE_PASSWORD'];
    $name = $_ENV['DATABASE_NAME'];
    return conecta($host, $user, $pass, $name);
}

function cerrarConexion($conexion)
{
    if (isset($conexion) && $conexion->connect_errno === 0) {
        $conexion->close();
    }
}

function creaDB()
{
    try {
        $host = $_ENV['DATABASE_HOST'];
        $user = $_ENV['DATABASE_USER'];
        $pass = $_ENV['DATABASE_PASSWORD'];
        $conexion = conecta($host, $user, $pass, null);
        
        if ($conexion->connect_error)
        {
            return [false, $conexion->error];
        }
        else
        {
            // Verificar si la base de datos ya existe
            $sqlCheck = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'tareas'";
            $resultado = $conexion->query($sqlCheck);
            if ($resultado && $resultado->num_rows > 0) {
                return [false, 'La base de datos "tareas" ya existía.'];
            }

            $sql = 'CREATE DATABASE IF NOT EXISTS tareas';
            if ($conexion->query($sql))
            {
                return [true, 'Base de datos "tareas" creada correctamente'];
            }
            else
            {
                return [false, 'No se pudo crear la base de datos "tareas".'];
            }
        }
    }
    catch (mysqli_sql_exception $e)
    {
        return [false, $e->getMessage()];
    }
    finally
    {
        cerrarConexion($conexion);
    }
}

function createTablaUsuarios()
{
    try {
        $conexion = conectaTareas();
        
        if ($conexion->connect_error)
        {
            return [false, $conexion->error];
        }
        else
        {
            // Verificar si la tabla ya existe
            $sqlCheck = "SHOW TABLES LIKE 'usuarios'";
            $resultado = $conexion->query($sqlCheck);

            if ($resultado && $resultado->num_rows > 0)
            {
                return [false, 'La tabla "usuarios" ya existía.'];
            }

            $sql = 'CREATE TABLE `usuarios` (`id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(50) NOT NULL , `rol` INT DEFAULT 0, `nombre` VARCHAR(50) NOT NULL , `apellidos` VARCHAR(100) NOT NULL , `contrasena` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ';
            if ($conexion->query($sql))
            {
                return [true, 'Tabla "usuarios" creada correctamente'];
            }
            else
            {
                return [false, 'No se pudo crear la tabla "usuarios".'];
            }
        }
    }
    catch (mysqli_sql_exception $e)
    {
        return [false, $e->getMessage()];
    }
    finally
    {
        cerrarConexion($conexion);
    }
}

function createTablaTareas()
{
    try {
        $conexion = conectaTareas();
        
        if ($conexion->connect_error)
        {
            return [false, $conexion->error];
        }
        else
        {
            // Verificar si la tabla ya existe
            $sqlCheck = "SHOW TABLES LIKE 'tareas'";
            $resultado = $conexion->query($sqlCheck);

            if ($resultado && $resultado->num_rows > 0)
            {
                return [false, 'La tabla "tareas" ya existía.'];
            }

            $sql = 'CREATE TABLE `tareas` (`id` INT NOT NULL AUTO_INCREMENT, `titulo` VARCHAR(50) NOT NULL, `descripcion` VARCHAR(250) NOT NULL, `estado` VARCHAR(50) NOT NULL, `id_usuario` INT NOT NULL, PRIMARY KEY (`id`), FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id`))';
            if ($conexion->query($sql))
            {
                return [true, 'Tabla "tareas" creada correctamente'];
            }
            else
            {
                return [false, 'No se pudo crear la tabla "tareas".'];
            }
        }
    }
    catch (mysqli_sql_exception $e)
    {
        return [false, $e->getMessage()];
    }
    finally
    {
        cerrarConexion($conexion);
    }
}

function createTablaFicheros()
{
    try {
        $conexion = conectaTareas();
        
        if ($conexion->connect_error)
        {
            return [false, $conexion->error];
        }
        else
        {
            // Verificar si la tabla ya existe
            $sqlCheck = "SHOW TABLES LIKE 'ficheros'";
            $resultado = $conexion->query($sqlCheck);

            if ($resultado && $resultado->num_rows > 0)
            {
                return [false, 'La tabla "ficheros" ya existía.'];
            }

            $sql = 'CREATE TABLE `ficheros` (`id` INT NOT NULL AUTO_INCREMENT, `nombre` VARCHAR(100) NOT NULL, `file` VARCHAR(250) NOT NULL, `descripcion` VARCHAR(250) NOT NULL, `id_tarea` INT NOT NULL, PRIMARY KEY (`id`), FOREIGN KEY (`id_tarea`) REFERENCES `tareas`(`id`))';
            if ($conexion->query($sql))
            {
                return [true, 'Tabla "ficheros" creada correctamente'];
            }
            else
            {
                return [false, 'No se pudo crear la tabla "ficheros".'];
            }
        }
    }
    catch (mysqli_sql_exception $e)
    {
        return [false, $e->getMessage()];
    }
    finally
    {
        cerrarConexion($conexion);
    }
}

function listaTareas()
{
    try {
        $conexion = conectaTareas();

        if ($conexion->connect_error) {
            return [false, $conexion->error];
        } else {
            $sql = "SELECT * FROM tareas";
            $resultados = $conexion->query($sql);
            $tareas = array();
            while ($row = $resultados->fetch_assoc()) {
                $usuario = buscaUsuarioMysqli($row['id_usuario']);
                $row['id_usuario'] = $usuario->getUsername();
                // Crear objeto Tarea
                $tareas[] = new Tarea($row['id'], $row['titulo'], $row['descripcion'], $row['estado'], $usuario);
            }
            return [true, $tareas];
        }

    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function nuevaTarea(Tarea $tarea)
{
    $conexion = conectaTareas();

    if ($conexion->connect_error) {
        return [false, $conexion->error];
    } else {
        $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, estado, id_usuario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $tarea->getTitulo(), $tarea->getDescripcion(), $tarea->getEstado(), $tarea->getUsuario()->getId());

        if ($stmt->execute()) {
            return [true, $conexion->insert_id];
        } else {
            return [false, $stmt->error];
        }
    }
}


function actualizaTarea(Tarea $tarea)
{
    $conexion = conectaTareas();

    if ($conexion->connect_error) {
        return [false, $conexion->error];
    } else {
    
        $titulo = $tarea->getTitulo();
        $descripcion = $tarea->getDescripcion();
        $estado = $tarea->getEstado();
        $id_usuario = $tarea->getUsuario()->getId();
        $id_tarea = $tarea->getId();

    
        $stmt = $conexion->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, estado = ?, id_usuario = ? WHERE id = ?");
        
        if ($stmt === false) {
            return [false, "Error al preparar la sentencia: " . $conexion->error];
        }

        $stmt->bind_param("sssii", $titulo, $descripcion, $estado, $id_usuario, $id_tarea);

        if ($stmt->execute()) {
            return [true, 'Tarea actualizada con éxito.'];
        } else {
            return [false, $stmt->error];
        }
    }
}


function borraTarea($id)
{
    $conexion = conectaTareas();

    if ($conexion->connect_error) {
        return [false, $conexion->error];
    } else {
        $stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return [true, 'Tarea eliminada con éxito.'];
        } else {
            return [false, $stmt->error];
        }
    }
}

function buscaTarea($id)
{
    $conexion = conectaTareas();

    if ($conexion->connect_error) {
        error_log("Error de conexión en buscaTarea: " . $conexion->connect_error);
        return [false, 'Error de conexión a la base de datos.'];
    }

    $sql = "SELECT id, titulo, descripcion, estado, id_usuario FROM tareas WHERE id = ?";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        error_log("Error en la preparación de la consulta SQL: " . $conexion->error);
        return [false, 'Error en la consulta de la tarea.'];
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultados = $stmt->get_result();

    if (!$resultados) {
        error_log("Error al ejecutar la consulta: " . $stmt->error);
        return [false, 'Error al ejecutar la consulta de la tarea.'];
    }

    if ($resultados->num_rows == 1) {
        $row = $resultados->fetch_assoc();
        $usuario = buscaUsuarioMysqli($row['id_usuario']);
        $tarea = new Tarea($row['id'], $row['titulo'], $row['descripcion'], $row['estado'], $usuario);
        return [true, $tarea];
    } else {
        error_log("No se encontró la tarea con ID: " . $id);
        return [false, 'Tarea no encontrada.'];
    }
}





function esPropietarioTarea($idUsuario, $idTarea)
{
    $tarea = buscaTarea($idTarea);
    if ($tarea)
    {
        return $tarea['id_usuario'] == $idUsuario;
    }
    else
    {
        return false;
    }
}

function buscaUsuarioMysqli($id)
{
    $conexion = conectaTareas();

    if ($conexion->connect_error) {
        return [false, $conexion->error];
    } else {
        $sql = "SELECT id, username, nombre, apellidos, rol, contrasena FROM usuarios WHERE id = " . (int)$id;
        $resultados = $conexion->query($sql);

        if ($resultados->num_rows == 1) {
            $usuarioBD = $resultados->fetch_assoc();
            // Creamos un objeto Usuario con los datos obtenidos de la base de datos
            return new Usuario(
                $usuarioBD['id'], 
                $usuarioBD['username'], 
                $usuarioBD['nombre'], 
                $usuarioBD['apellidos'], 
                $usuarioBD['contrasena'], 
                $usuarioBD['rol']
            );
        } else {
            return null;
        }
    }
}

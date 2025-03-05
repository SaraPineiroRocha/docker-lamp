<?php

require_once '/var/www/html/UD5/entregaTarea/clases/Usuario.php';
require_once '/var/www/html/UD5/entregaTarea/clases/Tarea.php';
require_once '/var/www/html/UD5/entregaTarea/clases/Fichero.php';

function conectaPDO()
{
    $servername = $_ENV['DATABASE_HOST'];
    $username = $_ENV['DATABASE_USER'];
    $password = $_ENV['DATABASE_PASSWORD'];
    $dbname = $_ENV['DATABASE_NAME'];

    $conPDO = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conPDO;
}

function listaUsuarios()
{
    try {
        $con = conectaPDO();
        $stmt = $con->prepare('SELECT id, username, nombre, apellidos, rol, contrasena FROM usuarios');
        $stmt->execute();

        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultados = $stmt->fetchAll();
        $usuarios = array();
        foreach ($resultados as $usuarioBD) {
            $usuarios[] = new Usuario(
                $usuarioBD['id'],
                $usuarioBD['username'],
                $usuarioBD['nombre'],
                $usuarioBD['apellidos'],
                $usuarioBD['contrasena'],
                $usuarioBD['rol']
            );
        }
        return [true, $usuarios];
    }
    catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
    finally {
        $con = null;
    }
}

function listaTareasPDO($id_usuario, $estado)
{
    try {
        $con = conectaPDO();
        $sql = 'SELECT * FROM tareas WHERE id_usuario = ' . $id_usuario;
        if (isset($estado)) {
            $sql = $sql . " AND estado = '" . $estado . "'";
        }
        $stmt = $con->prepare($sql);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tareas = array();
        while ($row = $stmt->fetch()) {
            $usuario = buscaUsuario($row['id_usuario']);
            // Crear objeto Tarea
            $tareas[] = new Tarea($row['id'], $row['titulo'], $row['descripcion'], $row['estado'], $usuario);
        }
        return [true, $tareas];
    }
    catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
    finally {
        $con = null;
    }
}

function nuevoUsuario($nombre, $apellidos, $username, $contrasena, $rol=0)
{
    try{
        $con = conectaPDO();
        $stmt = $con->prepare("INSERT INTO usuarios (nombre, apellidos, username, rol, contrasena) VALUES (:nombre, :apellidos, :username, :rol, :contrasena)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rol', $rol);
        $hasheado = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt->bindParam(':contrasena', $hasheado);
        $stmt->execute();
        
        $stmt->closeCursor();

        return [true, null];
    }
    catch (PDOExcetion $e)
    {
        return [false, $e->getMessage()];
    }
    finally
    {
        $con = null;
    }
}

function actualizaUsuario(Usuario $usuario)
{
    try {
        $con = conectaPDO();
        $stmt = $con->prepare("UPDATE usuarios SET username = :username, nombre = :nombre, apellidos = :apellidos, contrasena = :contrasena, rol = :rol WHERE id = :id");

        // Almacenar en variables temporales
        $id = $usuario->getId();
        $username = $usuario->getUsername();
        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $contrasena = $usuario->getContrasena();
        $rol = $usuario->getRol();

        // Ahora pasar las variables a bindParam
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);

        // Si la contraseña está vacía, asignamos NULL, de lo contrario se actualiza la contraseña
        if (!empty($contrasena)) {
            $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT); // Asignar la contraseña hasheada a una variable
            $stmt->bindParam(':contrasena', $hashedPassword);
        } else {
            $stmt->bindValue(':contrasena', null, PDO::PARAM_NULL); // Asignamos NULL si no hay nueva contraseña
        }

        $stmt->bindParam(':rol', $rol);

        $stmt->execute();

        return $stmt->rowCount();  // Número de filas afectadas
    } catch (PDOException $e) {
        return null;  // Manejo de errores
    } finally {
        $con = null;  // Cerrar la conexión
    }
}



function borraUsuario($id)
{
    try {
        $con = conectaPDO();
        $stmt = $con->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Verificar cuántas filas fueron afectadas
        if ($stmt->rowCount() > 0) {
            return [true, 'Usuario borrado correctamente.'];
        } else {
            return [false, 'No se pudo encontrar el usuario o ya estaba borrado.'];
        }
    } catch (PDOException $e) {
        return [false, 'Error al borrar el usuario: ' . $e->getMessage()];
    } finally {
        $con = null;
    }
}


function buscaUsuario($id)
{
    try {
        $con = conectaPDO();
        $stmt = $con->prepare("SELECT id, username, nombre, apellidos, contrasena, rol FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $usuarioBD = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Usuario($usuarioBD['id'], $usuarioBD['username'], $usuarioBD['nombre'], $usuarioBD['apellidos'], $usuarioBD['contrasena'], $usuarioBD['rol']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        return null;
    } finally {
        $con = null;
    }
}

function buscaUsername($username)
{
    try
    {
        $con = conectaPDO();
        $stmt = $con->prepare('SELECT id, rol, contrasena FROM usuarios WHERE username = "' . $username . '"');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() == 1)
        {
            return $stmt->fetch();
        }
        else
        {
            return null;
        }
    }
    catch (PDOExcetion $e)
    {
        return null;
    }
    finally
    {
        $con = null;
    }
    
}

function listaFicheros($id_tarea)
{
    try
    {
        $con = conectaPDO();
        $sql = 'SELECT * FROM ficheros WHERE id_tarea = :id_tarea';
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $ficheros = array();
        while ($row = $stmt->fetch())
        {
            // Creación de objeto Fichero
            $fichero = new Fichero(
                $row['id'],
                $row['nombre'],
                $row['file'],
                $row['descripcion'],
                $row['id_tarea'] 
            );
            array_push($ficheros, $fichero);
        }
        return $ficheros;
    }
    catch (PDOException $e)
    {
        return array();
    }
    finally
    {
        $con = null;
    }
}

function buscaFichero($id)
{
    try {
        $con = conectaPDO();
        if (!$con) {
            return null;
        }

        $sql = 'SELECT * FROM ficheros WHERE id = :id';
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $fichero = null;
        if ($row = $stmt->fetch()) {
            $fichero = new Fichero(
                $row['id'],
                $row['nombre'],
                $row['file'],
                $row['descripcion'],
                $row['id_tarea']
            );
        }
        return $fichero;
    } catch (PDOException $e) {
        error_log("Error en buscaFichero: " . $e->getMessage());  // Registrar el error en el log
        return null;
    } finally {
        $con = null;
    }
}



function borraFichero($id)
{
    try
    {
        $con = conectaPDO();
        $sql = 'DELETE FROM ficheros WHERE id = :id';
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }
    catch (PDOException $e)
    {
        return false;
    }
    finally
    {
        $con = null;
    }
}


function nuevoFichero($file, $nombre, $descripcion, $idTarea)
{
    try {
        $con = conectaPDO();
        $stmt = $con->prepare("INSERT INTO ficheros (nombre, file, descripcion, id_tarea) VALUES (:nombre, :file, :descripcion, :idTarea)");
        $stmt->bindParam(':file', $file);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':idTarea', $idTarea);
        $stmt->execute();
        
        $stmt->closeCursor();
        return [true, null];
    } catch (PDOException $e) {
        error_log("Error al insertar el archivo: " . $e->getMessage());
        return [false, $e->getMessage()];
    } finally {
        $con = null;
    }
}


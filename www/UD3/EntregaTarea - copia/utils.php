<?php

$tareas = [
        [
            'id' => 1,
            'descripcion' => 'Corregir tarea unidad 2 grupo A',
            'estado' => 'Pendiente'
        ],
        [
            'id' => 2,
            'descripcion' => 'Corregir tarea unidad 2 grupo A',
            'estado' => 'Pendiente'
        ],
        [
            'id' => 3,
            'descripcion' => 'Preparación unidad 3',
            'estado' => 'En proceso'
        ],
        [
            'id' => 4,
            'descripcion' => 'Publicar en github solución de la tarea unidad 2',
            'estado' => 'Completada'
        ]
    ];

    function tareas() {
        $servername = "localhost";
        $username = "root";
        $password = ""; // Contraseña predeterminada en XAMPP
        $dbname = "tareas";
    
        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        // Verificar conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
    
        // Consulta SQL para obtener las tareas y los nombres de usuario
        $sql = "SELECT t.id AS tarea_id, t.descripcion, t.estado, u.username
                FROM tareas AS t
                INNER JOIN usuarios AS u ON t.id_usuario = u.id";
    
        $result = $conn->query($sql);
    
        if (!$result) {
            die("Error en la consulta: " . $conn->error);
        }
    
        $tareas = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tareas[] = $row;
            }
        }
    
        $conn->close();
        return $tareas;
    }
    
    function obtenerUsuarios() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "tareas";
    
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
    
        $sql = "SELECT id, username FROM usuarios";
        $result = $conn->query($sql);
    
        $usuarios = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
    
        $conn->close();
        return $usuarios;
    }

function filtraCampo($campo)
{
    $campo = trim($campo);
    $campo = stripslashes($campo);
    $campo = htmlspecialchars($campo);
    return $campo;
}

function esCampoValido($campo)
{
    return !empty(filtraCampo($campo));
}

function guardar($id, $desc, $est)
{
    if (esCampoValido($id) && esCampoValido($est) && esCampoValido($est))
    {
        global $tareas;
        $data =[
            'id' => filtraCampo($id),
            'descripcion' => filtraCampo($desc),
            'estado' => filtraCampo($est)
        ];
        array_push($tareas, $data);
        return true;
    }
    else
    {
        return false;
    }  
    
}


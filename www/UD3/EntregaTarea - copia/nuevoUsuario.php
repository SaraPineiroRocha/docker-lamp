<?php
// Configuración de la base de datos
$servername = "localhost";  // Cambia según tu configuración
$username = "root";         // Cambia según tu configuración
$password = "";             // Cambia según tu configuración
$dbname = "tareas";         // Cambia según el nombre de tu base de datos

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión es exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Variables de formulario
$username = $nombre = $apellidos = $contraseña = "";
$usernameErr = $nombreErr = $apellidosErr = $contraseñaErr = "";
$valido = true;

// Función para filtrar y limpiar los datos
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Procesamiento cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación de los campos
    if (empty($_POST["username"])) {
        $usernameErr = "El nombre de usuario es obligatorio";
        $valido = false;
    } else {
        $username = test_input($_POST["username"]);
    }

    if (empty($_POST["nombre"])) {
        $nombreErr = "El nombre es obligatorio";
        $valido = false;
    } else {
        $nombre = test_input($_POST["nombre"]);
    }

    if (empty($_POST["apellidos"])) {
        $apellidosErr = "Los apellidos son obligatorios";
        $valido = false;
    } else {
        $apellidos = test_input($_POST["apellidos"]);
    }

    if (empty($_POST["contraseña"])) {
        $contraseñaErr = "La contraseña es obligatoria";
        $valido = false;
    } else {
        $contraseña = test_input($_POST["contraseña"]);
    }

    // Si los datos son válidos, insertar en la base de datos
    if ($valido) {
        // Hashear la contraseña
        $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

        // Insertar el usuario en la base de datos
        $sql = "INSERT INTO usuarios (username, nombre, apellidos, contraseña)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $nombre, $apellidos, $contraseñaHash);

        if ($stmt->execute()) {
            echo "<p>El usuario $username ha sido creado correctamente.</p>";
        } else {
            echo "<p>Error al crear el usuario: " . $stmt->error . "</p>";
        }

        // Cerrar la declaración y la conexión
        $stmt->close();
    } else {
        echo "<p class='error'>Por favor, complete todos los campos correctamente.</p>";
    }
}

// Cerrar la conexión
$conn->close();
?>

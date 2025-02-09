<?php
    // Start the session
    session_start();
    $rol = $_SESSION['rol'] ?? null;
    $tema = isset($_COOKIE['tema']) ? $_COOKIE['tema']:null;
    echo $tema;
?>

<!DOCTYPE html>
<html>
  <body>
    
    <h1>Unidad 4. Ejemplos</h1>
    
    <?php
    // Establecer variables de sesión
    $color = 'amarillo';
    $animal = 'perro';
    $_SESSION["favcolor"] = $color;
    $_SESSION["favanimal"] = $animal;
    echo "Variables de sesión establecidas. <br>";

    echo 'DB USER: ' . $_ENV['DATABASE_USER'] . '<br>';
    echo 'DB HOST: ' . $_ENV['DATABASE_HOST'];
    ?>

  </body>
</html>

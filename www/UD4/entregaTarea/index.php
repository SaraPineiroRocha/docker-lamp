<?php
session_start();
$tema = isset($_COOKIE['tema']) ? $_COOKIE['tema']:null;

if ($tema == 'dark') {
    $background_color = 'black';
    $text_color = 'white';
} else {
    $background_color = 'white';
    $text_color = 'black';
}
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD4. Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: <?php echo $background_color; ?>;
            color: <?php echo $text_color; ?>;
        }

    </style>
</head>
<body>

    <?php include_once('vista/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            
            <?php include_once('vista/menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Inicio</h2>
                </div>

                <div class="container justify-content-between">
                    <p>Aquí va el contenido </p>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('vista/footer.php'); ?>
    
</body>
</html>

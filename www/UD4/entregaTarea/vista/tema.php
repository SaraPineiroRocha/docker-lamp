<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tema = $_GET['tema'] ?? 'light';

    if (!in_array($tema, ['light', 'dark', 'auto'])) {
        $tema = 'light';
    }

    setcookie('tema', $tema, time() + (30 * 24 * 60 * 60), "/");

    header("Location: ../index.php");
    exit();
}
?>
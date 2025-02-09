<?php
session_start();
session_destroy();
header("Location: /UD4/entregaTarea/login/login.php");
exit();
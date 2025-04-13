<?php

Flight::route('POST /register', function() {
    $db = Flight::get('db');
    $data = Flight::request()->data;

    if (!$data->nombre || !$data->email || !$data->password) {
        Flight::json(["error" => "Campos requeridos"], 400);
        return;
    }

    $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password, token, created_at) VALUES (?, ?, ?, '', NOW())");
    try {
        $stmt->execute([
            $data->nombre,
            $data->email,
            password_hash($data->password, PASSWORD_DEFAULT)
        ]);
        Flight::json(["success" => "Usuario registrado"]);
    } catch (PDOException $e) {
        Flight::json(["error" => "Email ya en uso"], 409);
    }
});

Flight::route('POST /login', function() {
    $db = Flight::get('db');
    $data = Flight::request()->data;

    $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$data->email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($data->password, $user['password'])) {
        $token = bin2hex(random_bytes(32));
        $update = $db->prepare("UPDATE usuarios SET token = ? WHERE id = ?");
        $update->execute([$token, $user['id']]);
        Flight::json(["token" => $token]);
    } else {
        Flight::json(["error" => "Credenciales incorrectas"], 401);
    }
});

function require_auth() {
    $token = Flight::request()->getHeader("X-Token");
    if (!$token) {
        Flight::halt(401, json_encode(["error" => "Token requerido"]));
    }

    $db = Flight::get('db');
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        Flight::halt(401, json_encode(["error" => "Token inválido"]));
    }

    Flight::set('user', $user);
}

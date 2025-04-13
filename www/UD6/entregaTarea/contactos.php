<?php

Flight::route('GET /contactos', function() {
    require_auth();
    $user = Flight::get('user');
    $db = Flight::get('db');

    $id = Flight::request()->query['id'] ?? null;

    if ($id) {
        $stmt = $db->prepare("SELECT * FROM contactos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $user['id']]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$contact) {
            Flight::halt(403, json_encode(["error" => "Contacto no encontrado o no permitido"]));
        }
        Flight::json($contact);
    } else {
        $stmt = $db->prepare("SELECT * FROM contactos WHERE usuario_id = ?");
        $stmt->execute([$user['id']]);
        Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
});

Flight::route('POST /contactos', function() {
    require_auth();
    $user = Flight::get('user');
    $db = Flight::get('db');
    $data = Flight::request()->data;

    $stmt = $db->prepare("INSERT INTO contactos (nombre, telefono, email, usuario_id, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$data->nombre, $data->telefono, $data->email, $user['id']]);
    Flight::json(["success" => "Contacto creado"]);
});

Flight::route('PUT /contactos', function() {
    require_auth();
    $user = Flight::get('user');
    $db = Flight::get('db');
    $data = Flight::request()->data;

    $stmt = $db->prepare("SELECT * FROM contactos WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$data->id, $user['id']]);
    if (!$stmt->fetch()) {
        Flight::halt(403, json_encode(["error" => "No autorizado para modificar este contacto"]));
    }

    $stmt = $db->prepare("UPDATE contactos SET nombre = ?, telefono = ?, email = ? WHERE id = ?");
    $stmt->execute([$data->nombre, $data->telefono, $data->email, $data->id]);
    Flight::json(["success" => "Contacto actualizado"]);
});

Flight::route('DELETE /contactos', function() {
    require_auth();
    $user = Flight::get('user');
    $db = Flight::get('db');
    $id = Flight::request()->data->id;

    $stmt = $db->prepare("SELECT * FROM contactos WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$id, $user['id']]);
    if (!$stmt->fetch()) {
        Flight::halt(403, json_encode(["error" => "No autorizado para eliminar este contacto"]));
    }

    $stmt = $db->prepare("DELETE FROM contactos WHERE id = ?");
    $stmt->execute([$id]);
    Flight::json(["success" => "Contacto eliminado"]);
});

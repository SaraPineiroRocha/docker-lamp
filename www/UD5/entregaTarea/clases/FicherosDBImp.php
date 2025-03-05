<?php

require_once('DatabaseException.php');
require_once('FicherosDBInt.php');
require_once('../modelo/pdo.php');  // Asumimos que conectaPDO es la función para la conexión PDO

class FicherosDBImp implements FicherosDBInt {
    
    private function executeQuery($sql, $params = []) {
        try {
            $con = conectaPDO();
            $stmt = $con->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            throw new DatabaseException("Error de conexión o ejecución en la base de datos", __METHOD__, $sql, $e->getCode(), $e);
        }
    }

    public function listaFicheros($id_tarea): array {
        $sql = "SELECT * FROM ficheros WHERE id_tarea = :id_tarea";
        $stmt = $this->executeQuery($sql, ['id_tarea' => $id_tarea]);
        $ficheros = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ficheros[] = new Fichero($row['id'], $row['nombre'], $row['file'], $row['descripcion'], $row['id_tarea']);
        }
        return $ficheros;
    }

    public function buscaFichero($id): Fichero {
        $sql = "SELECT * FROM ficheros WHERE id = :id";
        $stmt = $this->executeQuery($sql, ['id' => $id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Fichero($row['id'], $row['nombre'], $row['file'], $row['descripcion'], $row['id_tarea']);
        }
        throw new DatabaseException("Fichero no encontrado", __METHOD__, $sql);
    }

    public function borraFichero($id): bool {
        $sql = "DELETE FROM ficheros WHERE id = :id";
        $stmt = $this->executeQuery($sql, ['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function nuevoFichero($fichero): bool {
        $sql = "INSERT INTO ficheros (nombre, file, descripcion, id_tarea) VALUES (:nombre, :file, :descripcion, :id_tarea)";
        $stmt = $this->executeQuery($sql, [
            'nombre' => $fichero->getNombre(),
            'file' => $fichero->getFile(),
            'descripcion' => $fichero->getDescripcion(),
            'id_tarea' => $fichero->getIdTarea()
        ]);
        return $stmt->rowCount() > 0;
    }
}
?>

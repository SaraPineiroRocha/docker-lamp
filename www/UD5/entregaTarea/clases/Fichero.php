<?php

class Fichero
{
    // Propiedades privadas
    private $id;
    private $nombre;
    private $file;
    private $descripcion;
    private $tarea;

    // Constantes públicas estáticas
    public const FORMATOS = ['jpg', 'jpeg', 'png', 'pdf', 'txt', 'docx'];
    public const MAX_SIZE = 5000000; // Tamaño máximo de archivo en bytes (5MB)

    // Constructor
    public function __construct($id, $nombre, $file, $descripcion, $tarea)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->file = $file;
        $this->descripcion = $descripcion;
        $this->tarea = $tarea;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getTarea()
    {
        return $this->tarea;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setTarea($tarea)
    {
        $this->tarea = $tarea;
    }

    // Método estático para validar los campos
    public static function validarCampos($nombre, $file, $descripcion, $tarea)
    {
        $errores = [];

        // Validar nombre
        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre es obligatorio.';
        }

        // Validar archivo
        if ($file['error'] != 0) {
            $errores['file'] = 'Error al subir el archivo.';
        } else {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, self::FORMATOS)) {
                $errores['file'] = 'Formato de archivo no permitido. Solo se permiten: ' . implode(', ', self::FORMATOS);
            }

            if ($file['size'] > self::MAX_SIZE) {
                $errores['file'] = 'El archivo supera el tamaño máximo permitido de ' . (self::MAX_SIZE / 1024 / 1024) . ' MB.';
            }
        }

        // Validar descripción
        if (empty($descripcion)) {
            $errores['descripcion'] = 'La descripción es obligatoria.';
        }

        // Validar tarea
        if (empty($tarea)) {
            $errores['tarea'] = 'La tarea es obligatoria.';
        }

        return $errores;
    }
}

?>

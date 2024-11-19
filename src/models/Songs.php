<?php
require_once(__DIR__ . '/../../lib/getid3/getid3/getid3.php');

class Songs {
    private $sql;
    private $uploadDir;

    public function __construct($pdo) {
        $this->sql = $pdo;
        $this->uploadDir = __DIR__ . '/../../public/uploads/songs/';
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    private function formatDuration($seconds) {
        $minutes = floor($seconds / 60);
        $remainingSeconds = round($seconds - ($minutes * 60));
        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }

    public function addSong($nombre, $artista, $archivo) {
        if (empty($nombre) || empty($artista) || empty($archivo['name'])) {
            throw new Exception("Todos los campos son obligatorios.");
        }

        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ['mp3', 'ogg'])) {
            throw new Exception("Solo se permiten archivos de música en formato MP3 o OGG.");
        }

        $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
        $rutaCompleta = $this->uploadDir . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            throw new Exception("Error al subir el archivo.");
        }

        // Obtener duración usando getID3
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($rutaCompleta);
        
        // Duracion
        $duracionSegundos = isset($fileInfo['playtime_seconds']) ? $fileInfo['playtime_seconds'] : 0;
        $duracion = $this->formatDuration($duracionSegundos);

        $stmt = $this->sql->prepare("
            INSERT INTO songs (nombre, artista, duracion, archivo) 
            VALUES (:nombre, :artista, :duracion, :archivo)
        ");
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':artista', $artista);
        $stmt->bindParam(':duracion', $duracion);
        $stmt->bindParam(':archivo', $nombreArchivo);
        
        return $stmt->execute();
    }

    public function getAllSongs() {
        $stmt = $this->sql->prepare("SELECT * FROM songs ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateSong($id, $nombre, $artista) {
        $stmt = $this->sql->prepare("
            UPDATE songs 
            SET nombre = :nombre, 
                artista = :artista 
            WHERE id = :id
        ");
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':artista', $artista);
        
        return $stmt->execute();
    }

    public function updateSongFile($id, $archivo) {
        // Obtener el archivo antiguo
        $stmt = $this->sql->prepare("SELECT archivo FROM songs WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $oldFile = $stmt->fetchColumn();

        // Eliminar el archivo antiguo
        if ($oldFile && file_exists($this->uploadDir . $oldFile)) {
            unlink($this->uploadDir . $oldFile);
        }

        // Subir el nuevo archivo
        $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
        $rutaCompleta = $this->uploadDir . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            throw new Exception("Error al subir el archivo.");
        }

        // Obtener duración usando getID3
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($rutaCompleta);
        
        $duracionSegundos = isset($fileInfo['playtime_seconds']) ? $fileInfo['playtime_seconds'] : 0;
        $duracion = $this->formatDuration($duracionSegundos);

        // Actualizar la base de datos
        $stmt = $this->sql->prepare("
            UPDATE songs 
            SET archivo = :archivo,
                duracion = :duracion 
            WHERE id = :id
        ");
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':archivo', $nombreArchivo);
        $stmt->bindParam(':duracion', $duracion);
        
        if ($stmt->execute()) {
            return $nombreArchivo;
        }
        
        throw new Exception("Error al actualizar el archivo en la base de datos");
    }

    public function deleteSong($id) {
        try {
            // Primero obtenemos el archivo
            $stmt = $this->sql->prepare("SELECT archivo FROM songs WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $archivo = $stmt->fetchColumn();

            // Eliminamos el fichero si es que hay : )
            if ($archivo && file_exists($this->uploadDir . $archivo)) {
                unlink($this->uploadDir . $archivo);
            }

            // Eliminamos cosas de la base de datos si es que hay : )
            $stmt = $this->sql->prepare("DELETE FROM songs WHERE id = :id");
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error al eliminar la canción: " . $e->getMessage());
        }
    }
}

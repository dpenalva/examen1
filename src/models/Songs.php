<?php
require_once(__DIR__ . '/../../lib/getid3/getID3/getID3.php');

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
        $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
        $rutaCompleta = $this->uploadDir . $nombreArchivo;

        move_uploaded_file($archivo['tmp_name'], $rutaCompleta);

        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($rutaCompleta);
        $duracionSegundos = isset($fileInfo['playtime_seconds']) ? $fileInfo['playtime_seconds'] : 0;
        $duracion = $this->formatDuration($duracionSegundos);

        $query = "INSERT INTO songs (nombre, artista, duracion, archivo) VALUES (:nombre, :artista, :duracion, :archivo)";
        $stm = $this->sql->prepare($query);
        $stm->execute([
            ':nombre' => $nombre,
            ':artista' => $artista,
            ':duracion' => $duracion,
            ':archivo' => $nombreArchivo
        ]);
    }

    public function getAllSongs() {
        $query = "SELECT * FROM songs ORDER BY id DESC";
        $stm = $this->sql->prepare($query);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateSong($id, $nombre, $artista, $archivo = null) {
        if ($archivo) {
            $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
            $rutaCompleta = $this->uploadDir . $nombreArchivo;

            move_uploaded_file($archivo['tmp_name'], $rutaCompleta);

            $getID3 = new getID3();
            $fileInfo = $getID3->analyze($rutaCompleta);
            $duracionSegundos = isset($fileInfo['playtime_seconds']) ? $fileInfo['playtime_seconds'] : 0;
            $duracion = $this->formatDuration($duracionSegundos);

            $query = "UPDATE songs SET nombre = :nombre, artista = :artista, archivo = :archivo, duracion = :duracion WHERE id = :id";
            $stm = $this->sql->prepare($query);
            $stm->execute([
                ':id' => $id,
                ':nombre' => $nombre,
                ':artista' => $artista,
                ':archivo' => $nombreArchivo,
                ':duracion' => $duracion
            ]);

            return $nombreArchivo;
        } else {
            $query = "UPDATE songs SET nombre = :nombre, artista = :artista WHERE id = :id";
            $stm = $this->sql->prepare($query);
            $stm->execute([
                ':id' => $id,
                ':nombre' => $nombre,
                ':artista' => $artista
            ]);
        }
    }

    public function deleteSong($id) {
        $query = "DELETE FROM songs WHERE id = :id";
        $stm = $this->sql->prepare($query);
        $stm->execute([':id' => $id]);
    }
}

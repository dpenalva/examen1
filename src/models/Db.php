<?php

class Db {
    private $pdo;

    public function __construct($config) {
        $this->pdo = new PDO(
            "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["db"],
            $config["db"]["user"],
            $config["db"]["pass"],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    public function get() {
        return $this->pdo;
    }
} 
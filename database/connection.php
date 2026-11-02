<?php
namespace App\Database;
use \PDO as PDO;

function connectDatabase (): PDO {
    try {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=inventory_demo;charset=utf8mb4",
            'product_manager',
            'password',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => FALSE,
            ]
        );

        return $pdo;
    } catch (\PDOException $e) {
        echo "Catch block ran";
        die("DB connection failed: " . $e->getMessage());
    }
}
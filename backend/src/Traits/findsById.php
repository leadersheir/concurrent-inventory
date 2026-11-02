<?php
namespace App\Traits;

trait findsById {

    abstract protected function getPdo(): \PDO;
    abstract protected function tableName(): string;

    public function findById (int $id): ?array {
        $pdo = $this->getPdo();
        $table = $this->tableName();

        $query = "SELECT * FROM " . $table . " WHERE id=?";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);

        $record = $stmt->fetch();

        return !$record ? null : $record;
    }
}
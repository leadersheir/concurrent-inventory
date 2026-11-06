<?php
namespace App\Repositories;
use App\Traits\findsById;
use App\Contracts\OrderRepository as OrderRepositoryInterface;

require_once __DIR__ . "/../Contracts/OrderRepositoryInterface.php";
require_once __DIR__ . "/../Traits/findsById.php";

class OrderRepository implements OrderRepositoryInterface {
    public function __construct (
        private \PDO $pdo
    ) {}

    use findsById;

    protected function getPdo(): \PDO { return $this->pdo; }
    protected function tableName(): string { return 'orders'; }

    public function create(int $userId, int $productId): int {
        $query = "INSERT INTO " . $this->tableName() . " (user_id, product_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId, $productId]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findByUserId(int $userId): array {
        $query = "SELECT * FROM " . $this->tableName() . " WHERE user_id=?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userId]);

        $orders = $stmt->fetchAll();

        return $orders;
    }
}
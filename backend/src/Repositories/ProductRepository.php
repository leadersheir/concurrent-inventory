<?php
namespace App\Repositories;

use App\Contracts\ProductRepository as ProductRepositoryInterface;
use App\Traits\findsById;

require_once __DIR__ . '/../Contracts/ProductRepositoryInterface.php';
require_once __DIR__ . '/../Traits/findsById.php';

class ProductRepository implements ProductRepositoryInterface {
    public function __construct (
        private \PDO $pdo
    ) {}

    use findsById;

    protected function getPdo (): \PDO { return $this->pdo; }
    protected function tableName (): string { return 'products'; }

    public function increaseStock(int $id, int $quantity): bool {
    
        $query = "UPDATE " . $this->tableName() . " SET stock=stock+? WHERE id=?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$quantity, $id]);

        $affectedRows = $stmt->rowCount();

        return $affectedRows === 1; // WARNING: will also return false if multiple rows were affected
    }

    public function decreaseStockIfAvailable (int $id): bool {
        $query = "UPDATE " . $this->tableName() . " SET stock=stock-1 WHERE id=? AND stock>0";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        return $stmt->rowCount() === 1;
    }
}
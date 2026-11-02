<?php
namespace App\Repositories;

use App\Contracts\ProductRepository as ProductRepositoryInterface;
use App\Traits\findsById;
use \PDO as PDO;

require_once __DIR__ . '/../Contracts/ProductRepositoryInterface.php';
require_once __DIR__ . '/../Traits/findsById.php';

class ProductRepository implements ProductRepositoryInterface {
    public function __construct (
        private PDO $pdo
    ) {}

    use findsById;

    protected function getPdo (): PDO { return $this->pdo; }
    protected function tableName (): string { return 'products'; }

    public function increaseStock(int $id, int $quantity): bool {
    
        $query = "UPDATE " . $this->tableName() . " set stock=stock+? WHERE id=?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$quantity, $id]);

        $affectedRows = $stmt->rowCount();

        return $affectedRows === 1; // WARNING: will also return false if multiple rows were affected
    }
}
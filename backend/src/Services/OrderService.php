<?php
namespace App\Services;
use App\Contracts\{OrderRepository, ProductRepository};

require_once __DIR__ . "/../Contracts/OrderRepositoryInterface.php";
require_once __DIR__ . "/../Contracts/ProductRepositoryInterface.php";

class OrderService {
    public function __construct (
        private \PDO $pdo,
        private OrderRepository $orderRepo,
        private ProductRepository $productRepo
    ) {}

    private const PRODUCT_ID = 1;

    public function placeOrder (int $userId): int {
        try {
            $this->pdo->beginTransaction();
            $stockDecreased = $this->productRepo->decreaseStockIfAvailable(self::PRODUCT_ID);
            if (!$stockDecreased) { throw new \Exception("Unable to decrease stock"); }
            $newOrderId = $this->orderRepo->create($userId, self::PRODUCT_ID);
            $this->pdo->commit();
            return $newOrderId;
        } catch (\Throwable $th) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            throw $th;
        }
            
    }
}
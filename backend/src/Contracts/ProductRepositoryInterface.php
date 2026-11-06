<?php
namespace App\Contracts;

interface ProductRepository {
    public function findById(int $id): ?array;
    public function increaseStock(int $id, int $quantity): bool;
    public function decreaseStockIfAvailable (int $id): bool;
}
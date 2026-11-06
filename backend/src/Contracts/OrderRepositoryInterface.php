<?php
namespace App\Contracts;

interface OrderRepository {
    public function create (int $userId, int $productId): int;
    public function findById(int $id): ?array;
    public function findByUserId(int $userId): array;
}
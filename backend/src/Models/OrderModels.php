<?php
namespace App\Models;

final readonly class Order {
    public function __construct (
        public int $id,
        public int $userId,
        public int $productId,
    ) {}
}
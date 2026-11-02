<?php
namespace App\Models;

final readonly class Product {
    public function __construct (
        public int $id,
        public string $name,
        public int $price,
        public int $stock
    ) {}
}
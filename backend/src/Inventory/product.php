<?php
namespace App\Inventory;

function isInStock(int $stockQuantity): bool {
    return $stockQuantity > 0;
}

function formatProduct(
    array $product
) {
    return array_merge($product, ['inStock' => namespace\isInStock($product['stock'])]);
}
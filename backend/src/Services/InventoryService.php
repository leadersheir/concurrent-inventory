<?php
namespace App\Services;
use App\Models\Product;
use App\Contracts\ProductRepository;

require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Contracts/ProductRepositoryInterface.php';

class InventoryService {
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    private const PRODUCT_ID = 1;

    public function getProduct(): ?Product {
        $product = $this->productRepository->findById(self::PRODUCT_ID);
        
        

        return $product === null ? null : new Product(
            $product['id'],
            $product['name'],
            $product['price'],
            $product['stock']
        );
    }

    public function restock(int $quantity): Product {
    
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Illegal quantity provided.");
        }

        $restocked = $this->productRepository->increaseStock(self::PRODUCT_ID, $quantity);

        if ($restocked) {
            $updatedProduct = $this->getProduct();

            if ($updatedProduct !== null) {
                return $updatedProduct;
            } else {
                throw new \Exception("Product not found");
            }
        } else {
            throw new \Exception("Restock failed.");
        }

    }
}
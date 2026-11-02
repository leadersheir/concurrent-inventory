<?php


use function App\Http\sendJson;
use function App\Inventory\formatProduct;

use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;

use App\Services\{AuthService, InventoryService};

use App\Http\{AuthController, AdminInventory as AdminInventoryController};

use function App\Database\connectDatabase;

require_once __DIR__ . '/../src/Http/response.php';
require_once __DIR__ . '/../src/Http/AuthController.php';
require_once __DIR__ . '/../src/Http/AdminInventoryController.php';

require_once __DIR__ . '/../src/Inventory/product.php';

require_once __DIR__ . '/../src/Repositories/ProductRepository.php';
require_once __DIR__ . '/../src/Repositories/UserRepository.php';

require_once __DIR__ . '/../src/Services/AuthService.php';
require_once __DIR__ . '/../src/Services/InventoryService.php';

require_once __DIR__ . '/../../database/connection.php';

session_start();

$pdo = connectDatabase();

$pdoUserRepository = new UserRepository($pdo);
$pdoProductRepository = new ProductRepository($pdo);

$authService = new AuthService($pdoUserRepository);
$inventoryService = new InventoryService($pdoProductRepository);

$authController = new AuthController($authService);
$adminInventoryController = new AdminInventoryController($authService, $inventoryService);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {
    case "/api/product":
        $product_array = (array) $inventoryService->getProduct();
        if ($product_array !== null) {
            sendJson(
                formatProduct($product_array, 200)
            );
        } else {
            sendJson([
                'message' => 'Product not found'
            ], 404);
        } 
        break;

    case "/api/login":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            sendJson(['message' => 'not allowed'], 405);
        }
        break;

    case "/api/me":
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $authController->me();
        }
        break;
    
    case "/api/logout":
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $authController->logout();
        }
        break;

    case "/api/restock":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminInventoryController->restock();
        } else {
            sendJson(['message' => 'not allowed'], 405);
        }
        break;
    
    case "/api/health":
        sendJson([
            'health' => 'nominal',
        ], 200);
        break;
    
    default:
        sendJson([
            'request_uri'  => $_SERVER['REQUEST_URI'],
            'message' => 'Not Found'], 404);
        break;
}
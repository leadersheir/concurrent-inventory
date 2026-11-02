<?php
namespace App\Http;
use App\Services\{AuthService, InventoryService};

require_once __DIR__ . '/response.php';

class AdminInventory {
    public function __construct (
        private AuthService $authService,
        private InventoryService $inventoryService
    ) {}

    public function restock(): void {
        $userId = $_SESSION['user_id'];
        if (!$userId) {
            sendJson(
                [
                    'error' => 'not authenticated'
                ],
                401
            );
            return;
        }
        
        $userData = (array) $this->authService->getUser($userId);

        if ($userData['role'] !== 'admin') {
            sendJson(
                [
                    'error' => 'permission denied'
                ],
                403
            );
            return;
        }

        $quantityString = $_POST['quantity'];

        if (
            !is_string($quantityString) ||
            !((int) $quantityString == (float) $quantityString) ||
            trim($quantityString) === '' ||
            (int) $quantityString > 10 ||
            (int) $quantityString <= 0
        ) {
            sendJson([
                'error' => 'invalid quantity'
            ], 400);
            return;
        }

        $quantity = (int) $quantityString;

        $updatedProduct = $this->inventoryService->restock($quantity);

        sendJson((array) $updatedProduct, 200);
    }
}
<?php
namespace App\Http;
use App\Services\{OrderService, AuthService};

require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Services/OrderService.php';
require_once __DIR__ . '/response.php';

class OrderController {
    public function __construct (
        private AuthService $authService,
        private OrderService $orderService,
    ) {}

    public function placeOrder(): void {
        $userId = $_SESSION['user_id'];
        
        if (!$userId) {
            sendJson([
                'error' => 'not authenticated'
            ], 401);
            return;
        }

        if (
            ((array) $this->authService->getUser($userId))['role'] !== 'customer'
        ) {
            sendJson([
                'error' => 'invalid user role to place order'
            ], 403);
            return;
        }

        try {
            $orderId = $this->orderService->placeOrder($userId);

            sendJson(
                ['order_id' => $orderId],
                201
            );
        } catch (\Throwable $e) {
            sendJson([
                'error' => $e->getMessage()
            ], 500);
        }
        
    }
}
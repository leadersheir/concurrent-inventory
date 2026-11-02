<?php
namespace App\Http;
use App\Services\AuthService;

require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/response.php';

class AuthController {
    public function __construct (
        private AuthService $authService
    ) {}

    public function login(): void {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (!is_string($username) || !is_string($password)) {
            sendJson(['error' => 'malformed input'], 400);
            return;
        }

        $user = $this->authService->authenticate($username, $password);

        if ($user === null) {
            sendJson(['error' => 'invalid credentials'], 401);
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_role'] = $user->role;
            sendJson((array) $user, 200);
        }
    }

    public function me(): void {
    
        $userId = $_SESSION['user_id'];

        if (!$userId) {
            sendJson(['error' => 'not authenticated'], 401);
            return;
        }

        $userData = $this->authService->getUser($userId);

        if ($userData === null) {
            sendJson(['error' => 'user not found'], 401);
        } else {
            sendJson((array) $userData, 200);
        }
    }

    // more or less copy-pasted from php docs

    public function logout(): void {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly']
            ]);
        }

        session_destroy();
    }
}
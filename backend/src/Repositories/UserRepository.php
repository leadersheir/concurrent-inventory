<?php
namespace App\Repositories;

use App\Contracts\UserRepository as UserRepositoryInterface;
use App\Traits\findsById;
use \PDO as PDO;

require_once __DIR__ . '/../Contracts/UserRepositoryInterface.php';
require_once __DIR__ . '/../Traits/findsById.php';

class UserRepository implements UserRepositoryInterface {
    public function __construct (
        private PDO $pdo
    ) {}

    use findsById;

    protected function getPdo (): PDO { return $this->pdo; }
    protected function tableName (): string { return 'users'; }

    public function findByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE username=?"
        );
        $stmt->execute([$username]);

        $user = $stmt->fetch();

        return !$user ? null : $user;
    }
}
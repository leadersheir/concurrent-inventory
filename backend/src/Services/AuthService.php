<?php
namespace App\Services;
use App\Contracts\UserRepository;
use App\Models\User;

require_once __DIR__ . '/../Contracts/UserRepositoryInterface.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AuthService {
    public function __construct (
        private UserRepository $userRepository
    ) {}

    public function authenticate (
        string $username,
        string $password
    ): ?User {
        $userData = $this->userRepository->findByUsername($username);

        if (
            $userData === null ||
            !password_verify($password, $userData['password_hash'])
        ) {
            return null;
        }

        return new User(
            $userData['id'],
            $userData['username'],
            $userData['role']
        );
    }

    public function getUser (int $id): ?User {
        $userData = $this->userRepository->findById($id);

        if ($userData === null) {
            return null;
        }

        return new User(
            $userData['id'],
            $userData['username'],
            $userData['role']
        );
    }
}
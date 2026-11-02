<?php
namespace App\Contracts;

interface UserRepository {
    public function findById(int $id): ?array;
    public function findByUsername (string $username): ?array;
}
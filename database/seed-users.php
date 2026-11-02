<?php
namespace App\Database;
require __DIR__ . "/connection.php";

$pdo = connectDatabase();

$userSeeds = [
    [
        'username' => 'iamadmin',
        'password' => 'difficultpassword',
        'role' => 'admin'
    ],
    [
        'username' => 'cust1',
        'password' => 'pass1',
        'role' => 'customer'
    ],
    [
        'username' => 'cust2',
        'password' => 'pass2',
        'role' => 'customer'
    ]
];

function seedUsers(\PDO $pdo, array $userSeeds): void {
    foreach ($userSeeds as $userSeed) {
    
        [$username, $pwd, $role] = array_values($userSeed);

        $stmt = $pdo->prepare(
            "INSERT INTO users (username, password_hash, role) VALUES (?,?,?)"
        );

        $stmt->execute([
            $username,
            password_hash($pwd, PASSWORD_BCRYPT),
            $role
        ]);
    }

    echo "Database seeded" . '\n';
}
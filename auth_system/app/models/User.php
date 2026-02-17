<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

   public function create(string $username, string $email, string $password, string $role = 'user'): bool
{
    error_log("User::create called with: $username, $email, $role"); // Debug
    try {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $result = $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hash,
            ':role' => $role
        ]);
        error_log("User::create result: " . ($result ? 'true' : 'false')); // Debug
        if (!$result) {
            $errorInfo = $stmt->errorInfo();
            error_log("User create failed: " . implode(", ", $errorInfo));
        }
        return $result;
    } catch (\PDOException $e) {
        error_log("User create exception: " . $e->getMessage());
        return false;
    }
}

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users WHERE email = :email LIMIT 1
        ");

        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Nuevo: Buscar por username
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users WHERE username = :username LIMIT 1
        ");

        $stmt->execute([':username' => $username]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT id, username, email, role, created_at 
            FROM users WHERE id = :id LIMIT 1
        ");

        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateRememberToken(int $userId, string $tokenHash, string $expiry): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET remember_token_hash = :token,
                token_expiry = :expiry
            WHERE id = :id
        ");

        return $stmt->execute([
            ':token' => $tokenHash,
            ':expiry' => $expiry,
            ':id' => $userId
        ]);
    }

    public function findByTokenHash(string $tokenHash): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users
            WHERE remember_token_hash = :token
            AND token_expiry > NOW()
            LIMIT 1
        ");

        $stmt->execute([':token' => $tokenHash]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function clearRememberToken(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET remember_token_hash = NULL,
                token_expiry = NULL
            WHERE id = :id
        ");

        return $stmt->execute([':id' => $userId]);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT id, username, email, role, created_at
            FROM users
            ORDER BY created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM users WHERE id = :id
        ");

        return $stmt->execute([':id' => $id]);
    }
}
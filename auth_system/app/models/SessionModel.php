<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class SessionModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(int $userId): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO user_sessions
            (user_id, session_id, ip_address, user_agent, last_activity)
            VALUES (:user_id, :session_id, :ip, :agent, NOW())
            ON DUPLICATE KEY UPDATE last_activity = NOW(), ip_address = VALUES(ip_address), user_agent = VALUES(user_agent)
        ");

        return $stmt->execute([
            ':user_id' => $userId,
            ':session_id' => session_id(),
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN',
            ':agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN'
        ]);
    }

    public function updateActivity(string $sessionId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE user_sessions
            SET last_activity = NOW()
            WHERE session_id = :session_id
        ");

        return $stmt->execute([':session_id' => $sessionId]);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT us.id,
                   u.username,
                   u.email,
                   us.session_id,
                   us.ip_address,
                   us.user_agent,
                   us.last_activity
            FROM user_sessions us
            JOIN users u ON us.user_id = u.id
            ORDER BY us.last_activity DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteBySession(string $sessionId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM user_sessions
            WHERE session_id = :session_id
        ");

        return $stmt->execute([':session_id' => $sessionId]);
    }

    public function deleteByUser(int $userId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM user_sessions
            WHERE user_id = :user_id
        ");

        return $stmt->execute([':user_id' => $userId]);
    }

    public function cleanExpired(int $minutes = 15): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM user_sessions
            WHERE last_activity < (NOW() - INTERVAL :minutes MINUTE)
        ");

        return $stmt->execute([':minutes' => $minutes]);
    }

    public function exists(string $sessionId): bool
    {
        $stmt = $this->db->prepare("
            SELECT id FROM user_sessions
            WHERE session_id = :session_id
            LIMIT 1
        ");

        $stmt->execute([':session_id' => $sessionId]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
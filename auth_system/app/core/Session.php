<?php
namespace App\Core;

use App\Core\Database;
use App\Models\SessionModel;
use PDO;

class DBSessionHandler implements \SessionHandlerInterface {
    private PDO $pdo;
    private SessionModel $sessionModel;

    public function __construct(PDO $pdo, SessionModel $sessionModel) {
        $this->pdo = $pdo;
        $this->sessionModel = $sessionModel;
    }

    public function open(string $savePath, string $sessionName): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read(string $id): string {
        return '';
    }

    public function write(string $id, string $data): bool {
        if (!isset($_SESSION['user_id'])) {
            return true; // No hay usuario, no escribir pero no fallar
        }
        $user_id = $_SESSION['user_id'];
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        try {
            $stmt = $this->pdo->prepare("INSERT INTO user_sessions (user_id, session_id, ip_address, user_agent, last_activity) 
                                         VALUES (?, ?, ?, ?, NOW()) 
                                         ON DUPLICATE KEY UPDATE last_activity = NOW(), ip_address = VALUES(ip_address), user_agent = VALUES(user_agent)");
            return $stmt->execute([$user_id, $id, $ip, $user_agent]);
        } catch (\PDOException $e) {
            error_log("Session write error: " . $e->getMessage());
            return false;
        }
    }

    public function destroy(string $id): bool {
        try {
            return $this->sessionModel->deleteBySession($id);
        } catch (\PDOException $e) {
            error_log("Session destroy error: " . $e->getMessage());
            return false;
        }
    }

    public function gc(int $max_lifetime): int|false {
        try {
            $minutes = (int) ($max_lifetime / 60);
            return $this->sessionModel->cleanExpired($minutes) ? 1 : false;
        } catch (\PDOException $e) {
            error_log("Session gc error: " . $e->getMessage());
            return false;
        }
    }
}

class Session {
    const TIMEOUT = 900;

    public static function start(): void {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => false, 
        'cookie_samesite' => 'Lax'
    ]);
    self::checkTimeout();
}

    public static function regenerate(): void {
        session_regenerate_id(true);
    }

    private static function checkTimeout(): void {
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > self::TIMEOUT)) {
            self::destroy();
            header("Location: ?route=login&timeout=1");
            exit;
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public static function flash(string $key, string $message = ''): ?string {
        if ($message) {
            $_SESSION['flash'][$key] = $message;
            return null;
        }
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function getUserId(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    public static function destroy(): void {
        session_unset();
        session_destroy();
    }
}
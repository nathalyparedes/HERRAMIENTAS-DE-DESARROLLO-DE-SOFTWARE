<?php
namespace App\Core;

class CSRF {

    public static function validate(string $token): bool {
        $stored = $_SESSION['csrf_token'] ?? '';
        error_log("CSRF validate: stored='$stored', received='$token'");

        if (!$stored || !$token) {
            return false;
        }

        $valid = hash_equals($stored, $token);

        if ($valid) {
            unset($_SESSION['csrf_token']); // invalida después de uso
        }

        error_log("CSRF result: " . ($valid ? 'true' : 'false'));

        return $valid;
    }


    public static function generate(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        error_log("CSRF generated: " . $_SESSION['csrf_token']);
    }

    return $_SESSION['csrf_token'];
}

}
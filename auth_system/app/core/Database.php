<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    private const DB_HOST = '127.0.0.1';
    private const DB_NAME = 'auth_system';
    private const DB_USER = 'root';
    private const DB_PASS = '275764533'; 
    private const DB_CHARSET = 'utf8mb4';

    private function __construct() {}

    public static function connect(): PDO
    {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . self::DB_HOST .
                   ";dbname=" . self::DB_NAME .
                   ";charset=" . self::DB_CHARSET;

            try {
                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_PERSISTENT         => false
                ]);
            } catch (PDOException $e) {
                error_log("Database connection error: " . $e->getMessage());
                die("Error de conexión a la base de datos. Inténtalo más tarde.");
            }
        }

        return self::$instance;
    }

    public static function disconnect(): void
    {
        self::$instance = null;
    }
}
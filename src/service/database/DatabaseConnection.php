<?php

declare(strict_types=1);

namespace App\Service\Database;
use Exception;
use PDO;

final class DatabaseConnection implements DatabaseConnectionInterface
{
    private static ?PDO $connection = null;
    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (null === self::$connection) {
            try {
                self::$connection = (new DatabaseConnection())->setConnection();
                return self::$connection;
            } catch (Exception $e) {
                throw $e;
            }
        }
        
        return self::$connection;
    }

    private function setConnection(): PDO
    {
        return new PDO(
            "{$_ENV['DB_CONNECTION']}:host={$_ENV['DB_HOST']}:{$_ENV['DB_PORT']};dbname={$_ENV['DB_DATABASE']}",
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
    }

    /**
     * @throws Exception
     */
    public function __clone()
    {
        throw new Exception('DatabaseConnection is singleton - it cannot be cloned');
    }
 
    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception('DatabaseConnection is singleton - it cannot be unserialized');
    }
 
    /**
     * @throws Exception
     */
    public function __unserialize(array $data)
    {
        throw new Exception('DatabaseConnection is singleton - it cannot be unserialized');
    }
}
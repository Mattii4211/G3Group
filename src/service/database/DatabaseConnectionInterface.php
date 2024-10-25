<?php

declare(strict_types=1);

namespace App\Service\Database;
use PDO;

interface DatabaseConnectionInterface 
{
    public static function getConnection(): PDO;
}
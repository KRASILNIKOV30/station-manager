<?php

declare(strict_types=1);

namespace App\Common\Database;

final class ConnectionProvider
{
    public static function getConnection(): Connection
    {
        static $connection = null;
        if ($connection === null) {
            $connection = new Connection();
        }
        return $connection;
    }
}

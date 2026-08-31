<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;
use PDOException;
use stdClass;

class Database
{
    private static ?Database $instance = null;
    private static PDO $pdo;

    private function __construct()
    {
        $dsn = "pgsql:host=localhost;port=5432;dbname=commandes";

        try {
            self::$pdo = new PDO($dsn, "postgres", "passer123", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        } catch (PDOException $e) {
            $sqlitePath = dirname(__DIR__, 2) . "/schemaSql/erp.db";
            self::$pdo = new PDO("sqlite:" . $sqlitePath, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
            self::$pdo->exec("PRAGMA foreign_keys = ON;");
        }
    }

    private static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private static function getConnexion(): PDO
    {
        self::getInstance();
        return self::$pdo;
    }

    public static function query(string $sql, bool $single = true): array|false|stdClass
    {
        $stmt = self::getConnexion()->query($sql);
        return $single ? $stmt->fetch() : $stmt->fetchAll();
    }

    public static function prepare(string $sql, array $datas): PDOStatement
    {
        $stmt = self::getConnexion()->prepare($sql);
        
        foreach ($datas as $key => $value) {
            // Déterminer le type PDO approprié
            $type = match (true) {
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                is_int($value)  => PDO::PARAM_INT,
                default         => PDO::PARAM_STR,
            };

            // Les index numérotés (?) commencent à 1 dans PDO, les paramètres nommés (:param) restent identiques
            $parameter = is_int($key) ? $key + 1 : $key;

            $stmt->bindValue($parameter, $value, $type);
        }

        $stmt->execute();
        return $stmt;
    }

    public static function executeQuery(string $sql, array $datas, bool $single = true): array|false|stdClass
    {
        $stmt = self::prepare($sql, $datas);
        return $single ? $stmt->fetch() : $stmt->fetchAll();
    }

    public static function executeUpdate(string $sql, array $datas): int
    {
        $stmt = self::prepare($sql, $datas);

        if (str_starts_with(strtoupper(trim($sql)), 'INSERT')) {
            return (int) self::getConnexion()->lastInsertId();
        }

        return $stmt->rowCount();
    }

    public static function beginTransaction(): bool
    {
        return self::getConnexion()->beginTransaction();
    }

    public static function commit(): bool
    {
        return self::getConnexion()->commit();
    }

    public static function rollBack(): bool
    {
        return self::getConnexion()->rollBack();
    }
}
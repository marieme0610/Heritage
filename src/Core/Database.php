<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;
use PDOException;
use Exception;
use stdClass;

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn = "pgsql:host=localhost;port=5432;dbname=commandes";

        try {
            $this->pdo = new PDO($dsn, "postgres", "passer123", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        } catch (PDOException $e) {
            $sqlitePath = dirname(__DIR__, 2) . "/schemaSql/erp.db";
            $this->pdo = new PDO("sqlite:" . $sqlitePath, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
            
            $this->pdo->exec("PRAGMA foreign_keys = ON;");
        }
    }

    private function __clone(){}

   
    private function getConnexion(): PDO
    {
        return $this->pdo;
    }

    public function query(string $sql, bool $single = true): array|false|stdClass
    {
        $stmt = $this->getConnexion()->query($sql);
        return $single ? $stmt->fetch() : $stmt->fetchAll();
    }

    public function prepare(string $sql, array $datas): PDOStatement
    {
        $stmt = $this->getConnexion()->prepare($sql);

        // Liaison explicite des types pour éviter le bug des booléens PostgreSQL
        foreach ($datas as $key => $value) {
            $param = str_starts_with((string)$key, ':') ? $key : ':' . $key;
            
            if (is_bool($value)) {
                $stmt->bindValue($param, $value, PDO::PARAM_BOOL);
            } elseif (is_int($value)) {
                $stmt->bindValue($param, $value, PDO::PARAM_INT);
            } elseif (is_null($value)) {
                $stmt->bindValue($param, $value, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue($param, $value, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt;
    }

    public function executeQuery(string $sql, array $datas, bool $single = true): array|false|stdClass
    {
        $stmt = $this->prepare($sql, $datas);
        return $single ? $stmt->fetch() : $stmt->fetchAll();
    }

    public function executeUpdate(string $sql, array $datas): int
    {
        $stmt = $this->prepare($sql, $datas);

        if (str_starts_with(strtoupper(trim($sql)), 'INSERT')) {
            return (int) $this->getConnexion()->lastInsertId();
        }

        return $stmt->rowCount();
    }

    public function beginTransaction(): bool
    {
        return $this->getConnexion()->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->getConnexion()->commit();
    }

    public function rollBack(): bool
    {
        return $this->getConnexion()->rollBack();
    }
}
<?php

declare(strict_types=1);

namespace Vigihdev\PdoDev\Service;

use Vigihdev\PdoDev\Contract\PDOConnectionContract;
use PDO;
use PDOException;
use RuntimeException;
use Exception;


final class DatabaseService
{


    private PDO $pdo;

    public function __construct(
        private readonly PDOConnectionContract $connection
    ) {}



    /**
     * Membuat database beserta user dan privileges
     */
    public function setupDatabase(
        string $databaseName,
        string $username,
        string $password,
        string $host = 'localhost',
        string $charset = 'utf8mb4',
        string $collation = 'utf8mb4_unicode_ci',
        array $privileges = ['ALL']
    ): bool {
        try {
            // Create database
            $this->createDatabase($databaseName, $charset, $collation);

            // Create user
            $this->createUser($username, $password, $host);

            // Grant privileges
            $this->grantPrivileges($username, $databaseName, $host, $privileges);

            return true;
        } catch (Exception $e) {
            throw new RuntimeException("Failed to setup database: " . $e->getMessage());
        }
    }

    /**
     * Membuat database baru
     */
    protected function createDatabase(
        string $databaseName,
        string $charset = 'utf8mb4',
        string $collation = 'utf8mb4_unicode_ci',
        bool $ifNotExists = true
    ): bool {

        try {

            $ifNotExistsClause = $ifNotExists ? 'IF NOT EXISTS' : '';
            $sql = "CREATE DATABASE $ifNotExistsClause `$databaseName` 
                    CHARACTER SET $charset 
                    COLLATE $collation";

            return $this->pdo->exec($sql) !== false;
        } catch (PDOException $e) {
            throw new \RuntimeException("Failed to create database: " . $e->getMessage());
        }
    }

    /**
     * Membuat user untuk database
     */
    protected function createUser(
        string $username,
        string $password,
        string $host = 'localhost',
        array $privileges = ['ALL']
    ): bool {

        try {
            $pdo = $this->pdo;

            // Create user
            $createUserSql = "CREATE USER '" . $pdo->quote($username) . "'@'" . $pdo->quote($host) . "' 
                             IDENTIFIED BY '" . $pdo->quote($password) . "'";

            $pdo->exec($createUserSql);
            return true;
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to create user: " . $e->getMessage());
        }
    }

    /**
     * Memberikan privileges ke user untuk database tertentu
     */
    protected function grantPrivileges(
        string $username,
        string $databaseName,
        string $host = 'localhost',
        array $privileges = ['ALL']
    ): bool {
        try {
            $pdo = $this->pdo;

            $privilegesList = implode(', ', $privileges);
            $grantSql = "GRANT $privilegesList ON `$databaseName`.* 
                        TO '" . $pdo->quote($username) . "'@'" . $pdo->quote($host) . "'";

            $pdo->exec($grantSql);
            $pdo->exec("FLUSH PRIVILEGES");

            return true;
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to grant privileges: " . $e->getMessage());
        }
    }
}

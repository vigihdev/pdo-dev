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
    ) {
        $this->pdo = $connection->getConnection();
    }


    /**
     * Setup database dan user baru
     *
     * @param string $databaseName Nama database yang akan dibuat
     * @param string $username Username untuk user baru
     * @param string $password Password untuk user baru
     * @param string $host Host untuk user (default: localhost)
     * @param string $charset Character set database (default: utf8mb4)
     * @param string $collation Collation database (default: utf8mb4_unicode_ci)
     * @param array $privileges Hak akses user (default: ALL)
     * @return bool True jika berhasil
     * @throws RuntimeException Jika setup gagal
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
            $pdo = $this->pdo;

            // Create database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET {$charset} COLLATE {$collation}");

            // buat user baru
            $pdo->exec("CREATE USER IF NOT EXISTS '{$username}'@'{$host}' IDENTIFIED BY '{$password}'");

            // kasih ALL PRIVILEGES ke user itu untuk semua database
            $pdo->exec("GRANT ALL PRIVILEGES ON *.* TO '{$username}'@'{$host}' WITH GRANT OPTION");

            // reload hak akses
            $pdo->exec("FLUSH PRIVILEGES");

            return true;
        } catch (Exception $e) {
            throw new RuntimeException("Failed to setup database: " . $e->getMessage());
        }
    }

    /**
     * Jalankan query dengan binding parameter
     *
     * @param string $sql Query SQL yang akan dijalankan
     * @param array $params Parameter untuk binding
     * @return \PDOStatement Statement yang sudah dieksekusi
     * @throws PDOException Jika query gagal
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Ambil satu baris hasil query
     *
     * @param string $sql Query SQL
     * @param array $params Parameter untuk binding
     * @return array|null Hasil query atau null jika tidak ada
     * @throws PDOException Jika query gagal
     */
    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Ambil semua hasil query sebagai array
     *
     * @param string $sql Query SQL
     * @param array $params Parameter untuk binding
     * @return array Semua hasil query
     * @throws PDOException Jika query gagal
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Jalankan query INSERT, UPDATE, DELETE
     *
     * @param string $sql Query SQL non-select
     * @param array $params Parameter untuk binding
     * @return bool True jika berhasil
     * @throws PDOException Jika query gagal
     */
    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Mulai transaksi database
     *
     * @return void
     * @throws PDOException Jika transaksi gagal dimulai
     */
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Commit transaksi
     *
     * @return void
     * @throws PDOException Jika commit gagal
     */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * Rollback transaksi
     *
     * @return void
     * @throws PDOException Jika rollback gagal
     */
    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }
}

<?php

declare(strict_types=1);

namespace Vigihdev\PdoDev\Connection;

use PDO;
use PDOException;
use Vigihdev\CryptoDev\CryptoOpenssl;
use Vigihdev\PdoDev\Contract\PDOConnectionContract;
use Vigihdev\PdoDev\GlobalContainer;

/**
 * PdoConnection
 *
 * Implementasi untuk mengelola koneksi database PDO
 *
 * @author Vigih Dev
 */
final class PdoConnection implements PDOConnectionContract
{
    /**
     * @var PDO|null Instance PDO yang aktif
     */
    private ?PDO $connection = null;

    public function __construct(
        private readonly string $dsn,
        private readonly string $username,
        private readonly string $password,
        private readonly array $options = []
    ) {


        $key = GlobalContainer::getContainer()->getParameter('path.secrets') . DIRECTORY_SEPARATOR . '.key';

        $this->connect(
            $this->dsn,
            $this->username,
            CryptoOpenssl::decrypt($this->password, $key),
            $this->options
        );
    }

    public function getDatabases(): array
    {
        $stmt = $this->getConnection()->query("SHOW DATABASES");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function useDb(string $dbName)
    {

        $this->getConnection()->exec("USE `$dbName`");
        return $this->getConnection();
    }

    /**
     * Membuat koneksi PDO baru
     *
     * @param string $dsn Data Source Name untuk koneksi database
     * @param string|null $username Username untuk koneksi database
     * @param string|null $password Password untuk koneksi database
     * @param array $options Opsi tambahan untuk koneksi PDO
     * @return PDO Instance PDO yang sudah terkoneksi
     * @throws PDOException Jika koneksi gagal
     */
    public function connect(string $dsn, ?string $username = null, ?string $password = null, array $options = []): PDO
    {
        $this->connection = new PDO($dsn, $username, $password, $options);
        return $this->connection;
    }

    /**
     * Mendapatkan instance PDO yang aktif
     *
     * @return PDO|null Instance PDO atau null jika belum terkoneksi
     */
    public function getConnection(): ?PDO
    {
        return $this->connection;
    }

    /**
     * Menutup koneksi database
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->connection = null;
    }

    /**
     * Mengecek status koneksi database
     *
     * @return bool True jika terkoneksi, false jika tidak
     */
    public function isConnected(): bool
    {
        return $this->connection !== null;
    }
}

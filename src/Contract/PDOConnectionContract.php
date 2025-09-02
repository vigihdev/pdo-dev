<?php

declare(strict_types=1);

namespace Vigihdev\PdoDev\Contract;

use PDO;

/**
 * PDOConnectionContract
 *
 * Interface untuk mengelola koneksi database PDO
 *
 * @author Vigih Dev
 */
interface PDOConnectionContract
{
    /**
     * Membuat koneksi PDO baru
     *
     * @param string $dsn Data Source Name untuk koneksi database
     * @param string|null $username Username untuk koneksi database
     * @param string|null $password Password untuk koneksi database
     * @param array $options Opsi tambahan untuk koneksi PDO
     * @return PDO Instance PDO yang sudah terkoneksi
     * @throws \PDOException Jika koneksi gagal
     */
    public function connect(string $dsn, ?string $username = null, ?string $password = null, array $options = []): PDO;

    /**
     * Mendapatkan instance PDO yang aktif
     *
     * @return PDO|null Instance PDO atau null jika belum terkoneksi
     */
    public function getConnection(): ?PDO;

    /**
     * Menutup koneksi database
     *
     * @return void
     */
    public function disconnect(): void;

    /**
     * Mengecek status koneksi database
     *
     * @return bool True jika terkoneksi, false jika tidak
     */
    public function isConnected(): bool;
}

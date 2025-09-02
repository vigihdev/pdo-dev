<?php

declare(strict_types=1);

namespace Vigihdev\PdoDev\Service;

use Vigihdev\PdoDev\Contract\PDOConnectionContract;
use PDO;


final class BackupService
{


    private PDO $pdo;

    public function __construct(
        private readonly PDOConnectionContract $connection
    ) {}
}

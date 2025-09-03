<?php

declare(strict_types=1);

namespace Vigihdev\PdoDev\Connection;

use Vigihdev\CryptoDev\CryptoDefuse;
use Vigihdev\PdoDev\ServiceLocator;
use Yiisoft\Cache\ArrayCache;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Mysql\Connection;
use Yiisoft\Db\Driver\Pdo\AbstractPdoConnection;
use Yiisoft\Db\Mysql\Driver;

final class YiiDbConnection
{


    public function __construct(
        private readonly string $dsn,
        private readonly string $username,
        private string $password
    ) {

        $key = ServiceLocator::getContainer()->getParameter('path.secrets') . DIRECTORY_SEPARATOR . '.defuse.key';
        $this->password = CryptoDefuse::decrypt($this->password, $key);
    }

    public function connection(): Connection
    {
        return new Connection(
            driver: new Driver(
                dsn: $this->dsn,
                username: $this->username,
                password: $this->password
            ),
            schemaCache: new SchemaCache(new ArrayCache())
        );
    }
}

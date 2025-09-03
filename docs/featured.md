```php
/** @var Yiisoft\Db\Mysql\Connection $db  */
$db = ServiceLocator::get('yii.db');
$db->createCommand("USE pdo_dev")->execute();
$db->getActivePDO()->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN)

```

```php

/** @var PDO $pdo  */
/** @var Vigihdev\PdoDev\Connection\PdoConnection $pdo  */
$pdo = $container->get('pdo.connection');
// List semua database
$stmt = $pdo->getConnection()->query("SHOW DATABASES");
$databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
$pdo->useDb('pdo_dev');
$db = new DatabaseService($pdo);
```

```php
use Vigihdev\PdoDev\ServiceLocator;
use Yiisoft\Db\Mysql\Column;
use Yiisoft\Db\Mysql\ColumnSchema;
use Yiisoft\Db\Schema\SchemaInterface;

$builder = $db->getQueryBuilder();
$sql = $builder->addColumn(
    table: 'tujuan',
    column: 'nama_tujuan',
    type: (new Column(SchemaInterface::TYPE_STRING, 20))->notNull()->asString()
);

$db->createCommand($sql)->execute();

$sql = $builder->alterColumn(
    table: 'tujuan',
    column: 'nama_tujuan',
    type: (new Column(SchemaInterface::TYPE_TEXT))->notNull()->asString()
);
$db->createCommand($sql)->execute();
```

```php

$databaseName = 'vigih_dev';
$username = 'vigih_dev';
$password = 'vigih_dev';
$host = 'localhost';
$charset = 'utf8mb4';
$collation = 'utf8mb4_unicode_ci';
$pdo = $db->getActivePDO();

// Create database
$pdo->exec("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET {$charset} COLLATE {$collation}");
$pdo->exec("CREATE USER IF NOT EXISTS '{$username}'@'{$host}' IDENTIFIED BY '{$password}'");
$pdo->exec("GRANT ALL PRIVILEGES ON *.* TO '{$username}'@'{$host}' WITH GRANT OPTION");
$pdo->exec("FLUSH PRIVILEGES");
```

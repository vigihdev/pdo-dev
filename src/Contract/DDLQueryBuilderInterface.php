<?php

declare(strict_types=1);

namespace Vigihdev\PdoDev\Contract;


interface DDLQueryBuilderInterface
{

    public function addColumn(string $table, string $column, string $type): string;

    public function addForeignKey(
        string $table,
        string $name,
        array|string $columns,
        string $referenceTable,
        array|string $referenceColumns,
        string|null $delete = null,
        string|null $update = null
    ): string;

    public function addPrimaryKey(string $table, string $name, array|string $columns): string;

    public function addUnique(string $table, string $name, array|string $columns): string;

    public function alterColumn(string $table, string $column, string $type): string;

    public function createIndex(
        string $table,
        string $name,
        array|string $columns,
        ?string $indexType = null,
        ?string $indexMethod = null
    ): string;

    public function createTable(string $table, array $columns, ?string $options = null): string;
    public function dropColumn(string $table, string $column): string;
    public function dropDefaultValue(string $table, string $name): string;
    public function dropForeignKey(string $table, string $name): string;
    public function dropIndex(string $table, string $name): string;
    public function dropPrimaryKey(string $table, string $name): string;
    public function dropTable(string $table, bool $ifExists = false, bool $cascade = false): string;
    public function renameColumn(string $table, string $oldName, string $newName): string;
    public function renameTable(string $oldName, string $newName): string;
    public function truncateTable(string $table): string;
}

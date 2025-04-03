<?php

abstract class BaseMigration
{
    /**
     * Returns the DB instance.
     *
     * @return DB
     */
    protected static function db(): DB
    {
        return DB::getInstance();
    }

    /**
     * Checks if a table exists.
     *
     * @param string $table The table name to check.
     * @return bool True if the table exists, false otherwise.
     */
    protected static function hasTable(string $table): bool
    {
        return self::db()->showTables($table);
    }

    /**
     * Checks if a column exists in a table.
     *
     * @param string $table The table name to check.
     * @param string $column The column name to check.
     * @return bool True if the column exists, false otherwise.
     */
    protected static function hasColumn(string $table, string $column): bool
    {
        return self::db()->query("SHOW COLUMNS FROM `nl2_{$table}` LIKE '{$column}'")->exists();
    }

    /**
     * Drops a table if it exists.
     *
     * @param string $table The table name to drop.
     * @return void
     */
    protected static function dropTable(string $table): void
    {
        self::db()->query("DROP TABLE IF EXISTS {$table}");
    }

    /**
     * Creates a table with the given schema.
     *
     * @param string $table The table name to create.
     * @param string $table_schema The schema definition for the table.
     * @return void
     */
    protected static function createTable(string $table, string $table_schema): void
    {
        self::db()->createTable($table, $table_schema);
    }

    /**
     * Adds a column to a table.
     *
     * @param string $table The table name to add the column to.
     * @param string $column The name of the column to add. (e.g., "new_column").
     * @param string $attributes The attributes of the column. (e.g., "VARCHAR(255) NOT NULL").
     */
    protected static function addColumn(string $table, string $column, string $attributes): bool
    {
        return self::db()->addColumn($table, $column, $attributes);
    }


}

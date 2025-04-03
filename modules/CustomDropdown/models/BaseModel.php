<?php

abstract class BaseModel
{
    // The table name (should be defined in child classes)
    protected static $table;

    // Default column for sorting
    protected static $orderBy = 'id';

    // Array of attributes for the object
    protected array $attributes = [];

    /**
     * Constructor.
     *
     * @param array $attributes Array of attributes for the model.
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Magic getter to access attributes.
     *
     * @param string $key The attribute key.
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Magic setter to set attributes.
     *
     * @param string $key The attribute key.
     * @param mixed $value The attribute value.
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Saves the current model.
     * If an 'id' is set, it updates the record; otherwise, it creates a new record.
     *
     * @return bool True on success, false on failure.
     */
    public function save(): bool
    {
        if (isset($this->attributes['id'])) {
            $attributes = $this->attributes;
            unset($attributes['id']);

            return static::updateById($this->attributes['id'], $attributes);
        }

        return static::create($this->attributes);
    }


    /**
     * Converts the model instance to an array.
     *
     * @return array Array of attributes.
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Returns all records from the table.
     *
     * @return array Array of model instances.
     */
    public static function all(): array
    {
        $result = DB::getInstance()->get(static::$table, ['id', '<>', 0])->results();
        return static::buildModels($result);
    }

    /**
     * Returns records that match the given condition.
     *
     * @param string $column The column name.
     * @param string $operator The operator (e.g., '=', '<>', etc.).
     * @param mixed $value The value to compare.
     * @return array Array of model instances.
     */
    public static function where(string $column, string $operator, $value): array
    {
        $result = DB::getInstance()->get(static::$table, [$column, $operator, $value])->results();
        return static::buildModels($result);
    }

    /**
     * Returns the first record that matches the given condition.
     *
     * @param string $column The column name.
     * @param string $operator The operator.
     * @param mixed $value The value to compare.
     * @return mixed|null The first model instance or null if none found.
     */
    public static function first(string $column, string $operator, $value)
    {
        $result = self::where($column, $operator, $value);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Returns the first record by its ID.
     *
     * @param mixed $id The record ID.
     * @return mixed|null The model instance or null if not found.
     */
    public static function find($id)
    {
        return static::first('id', '=', $id);
    }

    /**
     * Creates a new record in the table.
     *
     * @param array $attributes Attributes for the new record.
     * @return bool True on success, false on failure.
     */
    public static function create(array $attributes): bool
    {
        return DB::getInstance()->insert(static::$table, $attributes);
    }

    /**
     * Updates a record by its ID.
     *
     * @param mixed $id The record ID.
     * @param array $attributes Attributes to update.
     * @return bool True on success, false on failure.
     */
    public static function updateById($id, array $attributes): bool
    {
        return DB::getInstance()->update(static::$table, $id, $attributes);
    }

    /**
     * Deletes a record by its ID.
     *
     * @param mixed $id The record ID.
     * @return DB|false
     */
    public static function deleteById($id)
    {
        return DB::getInstance()->delete(static::$table, ['id', '=', $id]);
    }

    /**
     * Deletes records based on a condition.
     *
     * @param array $where Deletion condition (e.g., ['column', '=', 'value']).
     * @return DB|false
     */
    public static function deleteWhere(array $where)
    {
         return DB::getInstance()->delete(static::$table, $where);
    }

    /**
     * Returns records ordered by a specific column.
     *
     * @param string $where Condition for ordering (e.g., "id = 5").
     * @param string|null $orderBy
     * @param string $sort Sort direction: 'ASC' or 'DESC'.
     * @return array Array of model instances.
     */
    public static function whereOrder(string $where, string $orderBy = null, string $sort = 'ASC'): array
    {
        $orderBy = $orderBy ?? static::$orderBy;
        $result = DB::getInstance()->orderWhere(static::$table, $where, $orderBy, $sort)->results();
        return static::buildModels($result);
    }

    /**
     * Public method to build model instances from query results.
     *
     * @param array $rows Array of query result rows.
     * @return array Array of model instances.
     */
    public static function buildModels(array $rows): array
    {
        return array_map(fn($row) => new static((array)$row), $rows);
    }
}

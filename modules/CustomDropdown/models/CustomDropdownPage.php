<?php

class CustomDropdownPage extends BaseModel {
    // Set the table name
    protected static $table = 'custom_dropdown_pages';

    // Set the default order by column
    protected static $orderBy = 'page_order';

    public function getAllowedGroupsId(): array
    {
        $results = DB::getInstance()->get('custom_pages_permissions', [
            ['page_id', '=', $this->default_page_id],
            ['view', '=', 1]
        ])->results();

        return array_column($results, 'group_id', 'group_id');
    }

    /**
     * Returns pages for a particular dropdown menu sorted by order
     */
    public static function getByDropdownId($dropdown_id): array
    {
        $table = static::$table;
        $result = self::where('dropdown_id', '=', $dropdown_id);
        $models = [];
        foreach ($result as $row) {
            $models[] = new static((array)$row);
        }
        // Sort the models by page_order
        usort($models, function($a, $b) {
            return $a->page_order <=> $b->page_order;
        });
        return $models;
    }
}

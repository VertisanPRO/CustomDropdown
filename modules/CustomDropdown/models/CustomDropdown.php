<?php


class CustomDropdown extends BaseModel
{
    // Set the table name
    protected static $table = 'custom_dropdown';

    /**
     * Returns the dropdown pages for this dropdown.
     *
     * @return array
     */
    public function getPages(): array
    {
        return CustomDropdownPage::where('dropdown_id', '=', $this->id);
    }

    /**
     * Returns all active records.
     *
     * @return array
     */
    public static function allActive(): array
    {
        return self::where('enabled', '=', 1);
    }

    /**
     * Returns all dropdowns with related pages and allowed groups.
     *
     * @param bool $onlyActive If true, only return records where enabled = 1.
     * @return array Array of CustomDropdown objects with pages containing allowed_groups IDs.
     */
    public static function allWithRelations(bool $onlyActive = true): array
    {
        $db = DB::getInstance();

        $dropdownSql = "SELECT * FROM nl2_custom_dropdown" . ($onlyActive ? " WHERE enabled = 1" : "");
        $dropdowns = $db->query($dropdownSql)->results();

        if (empty($dropdowns)) {
            return [];
        }

        $dropdownIds = array_column($dropdowns, 'id');

        $placeholders = implode(',', array_fill(0, count($dropdownIds), '?'));
        $pagesSql = "
        SELECT cdp.*, cpp.group_id
        FROM nl2_custom_dropdown_pages cdp
        LEFT JOIN nl2_custom_pages_permissions cpp 
            ON cpp.page_id = cdp.default_page_id AND cpp.view = 1
        WHERE cdp.dropdown_id IN ($placeholders)
        ORDER BY cdp.page_order
    ";

        $pagesResult = $db->query($pagesSql, $dropdownIds)->results();

        // We group pages for dropdown_id
        $pagesGrouped = [];
        foreach ($pagesResult as $page) {
            $dropdownId = $page->dropdown_id;
            $pageId = $page->id;

            if (!isset($pagesGrouped[$dropdownId][$pageId])) {
                $pagesGrouped[$dropdownId][$pageId] = [
                    'id' => $pageId,
                    'page_title' => $page->page_title,
                    'page_link' => $page->page_link,
                    'page_location' => $page->page_location,
                    'page_target' => $page->page_target,
                    'page_icon' => $page->page_icon,
                    'page_order' => $page->page_order,
                    'default_page_id' => $page->default_page_id,
                    'allowed_groups' => [],
                ];
            }

            if ($page->group_id) {
                $pagesGrouped[$dropdownId][$pageId]['allowed_groups'][$page->group_id] = $page->group_id;
            }
        }

        $models = [];
        foreach ($dropdowns as $dropdown) {
            $dropdownData = (array)$dropdown;
            $dropdownData['pages'] = isset($pagesGrouped[$dropdown->id])
                ? CustomDropdownPage::buildModels(array_values($pagesGrouped[$dropdown->id]))
                : [];

            $models[] = new static($dropdownData);
        }

        return $models;
    }
}

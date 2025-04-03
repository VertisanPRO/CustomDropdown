<?php
global $template;
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/cs-dropdown'));
}
$setting_dropdown = CustomDropdown::first('id', '=', $_GET['id']);
if (empty($setting_dropdown)) {
    Redirect::to(URL::build('/panel/cs-dropdown'));
}
if (Input::exists()) {
    try {
        if (Token::check()) {
            // Add page through standard form
            if (isset($_POST['addLink'])) {
                $df_page_id = Input::get('addLink');
                $df_page = DB::getInstance()->get('custom_pages', ['id', '=', $df_page_id])->first();
                $exist_page = CustomDropdownPage::first('dropdown_id', '=', $_GET['id']);
                if (!empty($df_page) && empty($exist_page)) {
                    CustomDropdownPage::create([
                        'dropdown_id' => $_GET['id'],
                        'page_title' => $df_page->title,
                        'page_link' => $df_page->url,
                        'page_location' => $setting_dropdown->dropdown_location,
                        'page_target' => $df_page->target,
                        'page_icon' => $df_page->icon,
                        'page_order' => Input::get('order') ?? 1,
                        'default_page_id' => $df_page_id
                    ]);
                } else {
                    $errors[] = csd_trans('page_already_exists');
                }
            }
            // Delete page
            if (isset($_POST['deletePage'])) {
                try {
                    CustomDropdownPage::deleteById(Input::get('deletePage'));
                } catch (Exception $e) {
                    die($e->getMessage());
                }
                Session::flash('staff_cs_dropdown', csd_trans('deleted_successfully'));
            }
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

// Get dropdown pages ordered by page_order
$dropdown_pages_list = CustomDropdownPage::whereOrder('dropdown_id = ' . $_GET['id']);
$dropdown_pages = [];
$used_page_ids = [];

if (!empty($dropdown_pages_list)) {
    foreach ($dropdown_pages_list as $value) {
        $dropdown_pages[] = [
            'edit_link' => URL::build('/panel/cs-dropdown/setting', 'action=setting_edit&id=' . $value->id),
            'id' => $value->id,
            'title' => $value->page_title,
            'link' => $value->page_link,
            'location' => $value->page_location,
            'target' => $value->page_target,
            'order' => $value->page_order,
            'icon' => $value->page_icon,
            'df_page_id' => $value->default_page_id,
        ];

        // We collect ID already added pages
        if ($value->default_page_id) {
            $used_page_ids[] = (int)$value->default_page_id;
        }
    }
}

$template->getEngine()->addVariables([
    'BACK_LINK' => URL::build('/panel/cs-dropdown'),
    'DROPDOWN_PAGES' => $dropdown_pages
]);

// Get all pages with custom_pages except for already used
$default_page_array = [];
$default_page_list = DB::getInstance()->get('custom_pages', ['id', '<>', 0])->results();

if (!empty($default_page_list)) {
    foreach ($default_page_list as $value) {
        if (!in_array($value->id, $used_page_ids)) {
            $default_page_array[] = [
                'id' => $value->id,
                'title' => $value->title,
                'url' => $value->url,
                'link' => $value->link,
                'icon' => $value->icon,
            ];
        }
    }
}

$template->getEngine()->addVariables([
    'BACK_LINK' => URL::build('/panel/cs-dropdown'),
    'DEFAULT_PAGES' => $default_page_array,
    'DROPDOWN_ID' => $setting_dropdown->id,
    'DROPDOWN_NAME' => $setting_dropdown->dropdown_title,
    'DROPDOWN_ORDER' => $setting_dropdown->dropdown_order
]);
$template_file = 'custom_dropdown/setting_dropdown.tpl';
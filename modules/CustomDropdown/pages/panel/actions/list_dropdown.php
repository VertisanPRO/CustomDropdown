<?php
/**
 * @var TemplateBase $template
 */

if (Input::exists()) {
    try {
        if (Token::check()) {
            $validation = Validate::check($_POST, [
                'dropdown_title' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 2,
                    Validate::MAX => 150
                ],
                'dropdown_order' => [
                    Validate::REQUIRED => true,
                    Validate::NUMERIC => true,
                    Validate::MIN => 1,
                    Validate::MAX => 100
                ]

            ]);
            if ($validation->passed()) {
                $data = [
                    'dropdown_title' => Input::get('dropdown_title'),
                    'dropdown_location' => Input::get('dropdown_location'),
                    'dropdown_icon' => Input::get('dropdown_icon'),
                    'dropdown_order' => Input::get('dropdown_order')
                ];
                if (CustomDropdown::create($data)) {
                    Session::flash('staff_cs_dropdown', csd_trans('new_dropdown_successfully'));
                } else {
                    $errors[] = csd_trans('new_dropdown_errors');
                }
            } else {
                $errors = $validation->errors();
            }
        } else {
            $errors[] = csd_trans('invalid_token');
        }
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}

// --- Listing all dropdown menus ---
$all_dropdowns = CustomDropdown::all();
$dropdown_list_array = [];
if (!empty($all_dropdowns)) {
    foreach ($all_dropdowns as $dropdown) {
        $dropdown_list_array[] = array_merge([
            'enabled_link' => URL::build('/panel/cs-dropdown/', 'action=enabled&id=' . Output::getClean($dropdown->id)),
            'edit_link' => URL::build('/panel/cs-dropdown/', 'action=edit&id=' . Output::getClean($dropdown->id)),
            'setting_link' => URL::build('/panel/cs-dropdown/setting', 'action=setting&id=' . Output::getClean($dropdown->id)),
            'delete_link' => URL::build('/panel/cs-dropdown/', 'action=delete&id=' . Output::getClean($dropdown->id)),
        ], $dropdown->toArray());
    }
    $template->getEngine()->addVariables(['DROPDOWN_LIST' => $dropdown_list_array]);
} else {
    $template->getEngine()->addVariables(['NO_DROPDOWN' => csd_trans('no_dropdown')]);
}
$template_file = 'custom_dropdown/index.tpl';
<?php

/**
 * @var TemplateBase $template
 */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/cs-dropdown'));
}
$edit_dropdown = CustomDropdown::first('id', '=', $_GET['id']);
if (empty($edit_dropdown)) {
    Redirect::to(URL::build('/panel/cs-dropdown'));
}
if (Input::exists()) {
    try {
        if (Token::check()) {
            $validation = Validate::check($_POST, [
                'dropdown_title' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 2,
                    Validate::MAX => 150
                ]
            ]);
            if ($validation->passed()) {
                $updateData = [
                    'dropdown_title' => htmlspecialchars(Input::get('dropdown_title')),
                    'dropdown_location' => Input::get('dropdown_location'),
                    'dropdown_icon' => Input::get('dropdown_icon'),
                    'dropdown_order' => Input::get('dropdown_order')
                ];
                if (CustomDropdown::updateById($edit_dropdown->id, $updateData)) {
                    // Update dropdown pages location
                    $dropdown_pages = CustomDropdownPage::where('dropdown_id', '=', $edit_dropdown->id);
                    if (!empty($dropdown_pages)) {
                        foreach ($dropdown_pages as $page) {
                            CustomDropdownPage::updateById($page->id, [
                                'page_location' => Input::get('dropdown_location')
                            ]);
                        }
                    }
                    Session::flash('staff_cs_dropdown', csd_trans('edit_successfully'));
                    Redirect::to(URL::build('/panel/cs-dropdown'));
                } else {
                    $errors[] = csd_trans('edit_errors');
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

$template->getEngine()->addVariables([
    'BACK_LINK' => URL::build('/panel/cs-dropdown'),
    'EDIT_TITLE' => Output::getClean($edit_dropdown->dropdown_title),
    'EDIT_LOCATION' => Output::getClean($edit_dropdown->dropdown_location),
    'EDIT_ICON' => Output::getClean($edit_dropdown->dropdown_icon),
    'EDIT_ORDER' => Output::getClean($edit_dropdown->dropdown_order),
    'EDITE_DROPDOWN' => csd_trans('edite')
]);

$template_file = 'custom_dropdown/edit_dropdown.tpl';
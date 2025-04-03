<?php
/**
 * @var TemplateBase $template
 */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Redirect::to(URL::build('/panel/cs-dropdown'));
}
$edit_page = CustomDropdownPage::first('id', '=', $_GET['id']);
if (empty($edit_page)) {
    Redirect::to(URL::build('/panel/cs-dropdown'));
}

if (Input::exists()) {
    $errors = [];
    try {
        if (Token::check()) {
            $validation = Validate::check($_POST, [
                'page_title' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 2,
                    Validate::MAX => 150
                ]
            ]);
            $edit_page_target = (Input::get('page_target') == 1) ? '_blank' : null;
            if ($validation->passed()) {
                try {
                    CustomDropdownPage::updateById($edit_page->id, [
                        'page_title' => htmlspecialchars(Input::get('page_title')),
                        'page_link' => Input::get('page_link'),
                        'page_target' => $edit_page_target,
                        'page_icon' => Input::get('page_icon'),
                        'page_order' => Input::get('page_order')
                    ]);
                    Session::flash('staff_cs_dropdown', csd_trans('edit_successfully'));
                    Redirect::to(URL::build('/panel/cs-dropdown/setting', 'action=setting&id=' . Output::getClean($edit_page->dropdown_id)));
                } catch (Exception $e) {
                    $errors[] = $e->getMessage();
                }
            } else {
                $errors[] = csd_trans('edit_errors');
            }
        } else {
            $errors[] = csd_trans('invalid_token');
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}
$template->getEngine()->addVariables([
    'BACK_LINK' => URL::build('/panel/cs-dropdown/setting', 'action=setting&id=' . Output::getClean($edit_page->dropdown_id)),
    'EDIT_TITLE' => Output::getClean($edit_page->page_title),
    'EDIT_LINK' => Output::getClean($edit_page->page_link),
    'EDIT_ICON' => Output::getClean($edit_page->page_icon),
    'EDIT_ORDER' => Output::getClean($edit_page->page_order),
    'EDITE_DROPDOWN' => csd_trans('edite'),
    'PAGE_TITLE' => csd_trans('page_title'),
    'PAGE_LINK' => csd_trans('page_link'),
    'PAGE_TARGET' => csd_trans('page_target'),
    'PAGE_ICON' => csd_trans('page_icon'),
    'PAGE_ORDER' => csd_trans('page_order')
]);
$template_file = 'custom_dropdown/edit_page.tpl';
<?php

$csLanguage = $GLOBALS['csLanguage'];

if ($user->isLoggedIn()) {
    if (!$user->canViewStaffCP()) {

        Redirect::to(URL::build('/'));
    }
    if (!$user->isAdmLoggedIn()) {

        Redirect::to(URL::build('/panel/auth'));
    } else {
        if (!$user->hasPermission('admincp.csdropdown')) {
            require_once(ROOT_PATH . '/403.php');
        }
    }
} else {
    // Not logged in
    Redirect::to(URL::build('/login'));
}

const PAGE = 'panel';
const PARENT_PAGE = 'cs_dropdown_items';
const PANEL_PAGE = 'cs_dropdown_items';


require_once(ROOT_PATH . '/core/templates/backend_init.php');

$smarty->assign([
    'SUBMIT' => $language->get('general', 'submit'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'BACK' => $language->get('general', 'back'),
    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
    'CONFIRM_DELETE' => $language->get('general', 'confirm_delete'),
    'TITLE' => $csLanguage->get('general', 'title'),
    'ADD_NEW_DROPDOWN' => $csLanguage->get('general', 'add_new_dropdown'),
    'DROPDOWN_TITLE' => $csLanguage->get('general', 'dropdown_title'),
    'DROPDOWN_LOCATION' => $csLanguage->get('general', 'dropdown_location'),
    'DROPDOWN_ICON' => $csLanguage->get('general', 'dropdown_icon'),
    'DROPDOWN_ORDER' => $csLanguage->get('general', 'dropdown_order'),
    'REMOVE' => $csLanguage->get('general', 'remove'),
    'SETTINGS' => $csLanguage->get('general', 'settings'),
    'EDIT' => $csLanguage->get('general', 'edit'),
    'SELECT_PAGE' => $csLanguage->get('general', 'select_page'),
    'SELECT_IFRAME_PAGE' => $csLanguage->get('general', 'select_iframe_page'),
    'ADD_PAGE' => $csLanguage->get('general', 'add_page'),
    'NO_CREATED_PAGES' => $csLanguage->get('general', 'no_created_pages'),
    'LINK_NAVBAR' => $language->get('admin', 'page_link_navbar'),
    'LINK_FOOTER' => $language->get('admin', 'page_link_footer')
]);

$template_file = 'CustomDropdown/index.tpl';

if (!isset($_GET['action'])) {

    if (Input::exists()) {
        $errors = [];
        try {
            if (Token::check(Input::get('token'))) {

                $validation = Validate::check($_POST, [
                    'dropdown_title' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 2,
                        Validate::MAX => 150
                    ]
                ]);

                if ($validation->passed()) {
                    try {

                        DB::getInstance()->insert('custom_dropdown', [
                            'dropdown_title' => htmlspecialchars(Input::get('dropdown_title')),
                            'dropdown_location' => Input::get('dropdown_location'),
                            'dropdown_icon' => Input::get('dropdown_icon'),
                            'dropdown_order' => Input::get('dropdown_order')
                        ]);

                        Session::flash('staff_cs_dropdown', $csLanguage->get('general', 'new_dropdown_successfully'));
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                } else {
                    $errors[] = $csLanguage->get('general', 'new_dropdown_errors');
                }
            } else {
                $errors[] = $language->get('general', 'invalid_token');
            }
        } catch (Exception $e) {
        }
    }
} else {

    switch ($_GET['action']) {

        case 'edit':
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                Redirect::to(URL::build('/panel/cs-dropdown'));
            }
            $edit_dropdown = DB::getInstance()->get('custom_dropdown', ['id', '=', $_GET['id']])->results();
            if (!count($edit_dropdown)) {
                Redirect::to(URL::build('/panel/cs-dropdown'));
            }

            $edit_dropdown = $edit_dropdown[0];

            if (Input::exists()) {
                $errors = [];
                try {
                    if (Token::check(Input::get('token'))) {

                        $validation = Validate::check($_POST, [
                            'dropdown_title' => [
                                Validate::REQUIRED => true,
                                Validate::MIN => 2,
                                Validate::MAX => 150
                            ]
                        ]);

                        if ($validation->passed()) {
                            try {

                                DB::getInstance()->update('custom_dropdown', $edit_dropdown->id, [
                                    'dropdown_title' => htmlspecialchars(Input::get('dropdown_title')),
                                    'dropdown_location' => Input::get('dropdown_location'),
                                    'dropdown_icon' => Input::get('dropdown_icon'),
                                    'dropdown_order' => Input::get('dropdown_order')
                                ]);

                                if (Input::get('dropdown_location') == 1) {
                                    $location = 'top';
                                } else {
                                    $location = 'footer';
                                }

                                DB::getInstance()->query('UPDATE `nl2_custom_dropdown_pages` SET `page_location` = ? WHERE `dropdown_id` = ?', [$location, $edit_dropdown->id]);


                                Session::flash('staff_cs_dropdown', $csLanguage->get('general', 'edit_successfully'));
                                Redirect::to(URL::build('/panel/cs-dropdown'));
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }
                        } else {
                            $errors[] = $csLanguage->get('general', 'edit_errors');
                        }
                    } else {
                        $errors[] = $language->get('general', 'invalid_token');
                    }
                } catch (Exception $e) {
                }
            }

            $smarty->assign([
                'BACK_LINK' => URL::build('/panel/cs-dropdown'),
                'EDIT_TITLE' => Output::getClean($edit_dropdown->dropdown_title),
                'EDIT_LOCATION' => Output::getClean($edit_dropdown->dropdown_location),
                'EDIT_ICON' => Output::getClean($edit_dropdown->dropdown_icon),
                'EDIT_ORDER' => Output::getClean($edit_dropdown->dropdown_order),
                'EDITE_DROPDOWN' => $csLanguage->get('general', 'edite'),
            ]);


            $template_file = 'CustomDropdown/edit_dropdown.tpl';

            break;

        case 'setting':
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                Redirect::to(URL::build('/panel/cs-dropdown'));
            }
            $setting_dropdown = DB::getInstance()->get('custom_dropdown', ['id', '=', $_GET['id']])->results();
            if (!count($setting_dropdown)) {
                Redirect::to(URL::build('/panel/cs-dropdown'));
            }


            if (Input::exists()) {

                try {
                    if (Token::check(Input::get('token'))) {

                        if (isset($_POST['addLink'])) {

                            $df_page_id = $_POST['addLink'];

                            $df_page = DB::getInstance()->get('custom_pages', ['id', '=', $df_page_id])->results();

                            DB::getInstance()->insert('custom_dropdown_pages', [
                                'dropdown_id' => $_GET['id'],
                                'page_title' => $df_page[0]->title,
                                'page_link' => $df_page[0]->url,
                                'page_icon' => $df_page[0]->icon,
                                'page_order' => $_POST['order'],
                                'default_page_id' => $df_page_id

                            ]);
                        }

                        if (isset($_POST['addIframe'])) {

                            $if_page_id = $_POST['addIframe'];

                            $if_page = DB::getInstance()->get('iframe_pages', ['id', '=', $if_page_id])->results();

                            DB::getInstance()->insert('custom_dropdown_pages', [
                                'dropdown_id' => $_GET['id'],
                                'page_title' => $if_page[0]->name,
                                'page_link' => $if_page[0]->url,
                                'page_icon' => '',
                                'page_order' => '1',
                                'default_page_id' => $if_page_id

                            ]);
                        }

                        if (isset($_POST['deletePage'])) {

                            try {

                                DB::getInstance()->delete('custom_dropdown_pages', ['id', '=', $_POST['deletePage']]);
                            } catch (Exception $e) {
                                die($e->getMessage());
                            }

                            Session::flash('staff_cs_dropdown', $csLanguage->get('general', 'deleted_successfully'));
                        }
                    }
                } catch (Exception $e) {
                }
            }


            $setting_dropdown = $setting_dropdown[0];

            $dropdown_pages_list = DB::getInstance()->orderWhere('custom_dropdown_pages', 'dropdown_id = ' . $_GET['id'], 'page_order', 'ASC')->results();
            $dropdown_pages = [];

            if (count($dropdown_pages_list)) {

                foreach ($dropdown_pages_list as $value) {
                    $dropdown_pages[] = [
                        'edit_link' => URL::build('/panel/cs-dropdown/setting', 'action=setting_edit&id=' . Output::getClean($value->id)),
                        'id' => Output::getClean($value->id),
                        'title' => Output::getClean($value->page_title),
                        'link' => Output::getClean($value->page_link),
                        'location' => Output::getClean($value->page_location),
                        'target' => Output::getClean($value->page_target),
                        'order' => Output::getClean($value->page_order),
                        'icon' => Output::getClean($value->page_icon),
                        'df_page_id' => Output::getClean($value->default_page_id)
                    ];
                }
            }

            $smarty->assign([
                'BACK_LINK' => URL::build('/panel/cs-dropdown'),
                'DROPDOWN_PAGES' => $dropdown_pages
            ]);

            if (DB::getInstance()->showTables('iframe_pages')) {
                $iframe_page_list = DB::getInstance()->get('iframe_pages', ['id', '<>', 0])->results();

                $iframe_page_array = [];
                if (count($iframe_page_list)) {

                    foreach ($iframe_page_list as $value) {
                        $iframe_page_array[] = [
                            'id' => Output::getClean($value->id),
                            'title' => Output::getClean($value->name),
                            'url' => Output::getClean($value->url)
                        ];
                    }
                }

                $smarty->assign([
                    'IFRAME_PAGES' => $iframe_page_array
                ]);
            }

            $default_page_list = DB::getInstance()->get('custom_pages', ['id', '<>', 0])->results();

            $default_page_array = [];
            if (count($default_page_list)) {

                foreach ($default_page_list as $value) {
                    $default_page_array[] = [
                        'id' => Output::getClean($value->id),
                        'title' => Output::getClean($value->title),
                        'url' => Output::getClean($value->url),
                        'link' => Output::getClean($value->link),
                        'icon' => Output::getClean($value->icon)
                    ];
                }
            }

            $smarty->assign([
                'BACK_LINK' => URL::build('/panel/cs-dropdown'),
                'DEFAULT_PAGES' => $default_page_array,
                'DROPDOWN_ID' => $setting_dropdown->id,
                'DROPDOWN_NAME' => $setting_dropdown->dropdown_title,
                'DROPDOWN_ORDER' => $setting_dropdown->dropdown_order
            ]);

            $template_file = 'CustomDropdown/setting_dropdown.tpl';

            break;

        case 'setting_edit':
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                Redirect::to(URL::build('/panel/cs-dropdown'));
            }
            $edit_page = DB::getInstance()->get('custom_dropdown_pages', ['id', '=', $_GET['id']])->results();
            if (!count($edit_page)) {
                Redirect::to(URL::build('/panel/cs-dropdown'));
            }

            $edit_page = $edit_page[0];

            if (Input::exists()) {
                $errors = [];
                try {
                    if (Token::check(Input::get('token'))) {

                        $validation = Validate::check($_POST, [
                            'page_title' => [
                                Validate::REQUIRED => true,
                                Validate::MIN => 2,
                                Validate::MAX => 150
                            ]
                        ]);

                        if (Input::get('page_target') == 1) {
                            $edit_page_target = '_blank';
                        } else {
                            $edit_page_target = NULL;
                        }

                        if ($validation->passed()) {
                            try {

                                DB::getInstance()->update('custom_dropdown_pages', $edit_page->id, [
                                    'page_title' => htmlspecialchars(Input::get('page_title')),
                                    'page_link' => Input::get('page_link'),
                                    'page_target' => $edit_page_target,
                                    'page_icon' => Input::get('page_icon'),
                                    'page_order' => Input::get('page_order')
                                ]);


                                Session::flash('staff_cs_dropdown', $csLanguage->get('general', 'edit_successfully'));
                                Redirect::to(URL::build('/panel/cs-dropdown/setting', 'action=setting&id=' . Output::getClean($edit_page->dropdown_id)));
                                // die();


                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }
                        } else {
                            $errors[] = $csLanguage->get('general', 'edit_errors');
                        }
                    } else {
                        $errors[] = $language->get('general', 'invalid_token');
                    }
                } catch (Exception $e) {
                }
            }

            $smarty->assign([
                'BACK_LINK' => URL::build('/panel/cs-dropdown/setting', 'action=setting&id=' . Output::getClean($edit_page->dropdown_id)),
                'EDIT_TITLE' => Output::getClean($edit_page->page_title),
                'EDIT_LINK' => Output::getClean($edit_page->page_link),
                'EDIT_ICON' => Output::getClean($edit_page->page_icon),
                'EDIT_ORDER' => Output::getClean($edit_page->page_order),
                'EDITE_DROPDOWN' => $csLanguage->get('general', 'edite'),
                'PAGE_TITLE' => $csLanguage->get('general', 'page_title'),
                'PAGE_LINK' => $csLanguage->get('general', 'page_link'),
                'PAGE_TARGET' => $csLanguage->get('general', 'page_target'),
                'PAGE_ICON' => $csLanguage->get('general', 'page_icon'),
                'PAGE_ORDER' => $csLanguage->get('general', 'page_order')
            ]);


            $template_file = 'CustomDropdown/edit_page.tpl';

            break;

        case 'delete':
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                try {

                    DB::getInstance()->delete('custom_dropdown', ['id', '=', $_GET['id']]);
                } catch (Exception $e) {
                    die($e->getMessage());
                }

                Session::flash('staff_cs_dropdown', $csLanguage->get('general', 'deleted_successfully'));
                Redirect::to(URL::build('/panel/cs-dropdown'));
            }

            break;

        case 'enabled':
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                Redirect::to(URL::build('/panel/cs-dropdown'));
            }

            $edit_dropdown = DB::getInstance()->get('custom_dropdown', ['id', '=', $_GET['id']])->results();
            if (!count($edit_dropdown)) {
                Redirect::to(URL::build('/panel/cs-dropdown'));
            }

            $edit_dropdown = $edit_dropdown[0];

            if ($edit_dropdown->enabled == 1) {
                $enabled = 0;
            } else {
                $enabled = 1;
            }


            DB::getInstance()->update('custom_dropdown', $_GET['id'], [
                'enabled' => $enabled
            ]);
            Redirect::to(URL::build('/panel/cs-dropdown'));
        default:
            Redirect::to(URL::build('/panel/cs-dropdown'));
    }
}


$dropdown_list = DB::getInstance()->get('custom_dropdown', ['id', '<>', 0])->results();
$dropdown_list_array = [];
if (count($dropdown_list)) {
    foreach ($dropdown_list as $dropdown) {
        $dropdown_list_array[] = [
            'enabled_link' => URL::build('/panel/cs-dropdown/', 'action=enabled&id=' . Output::getClean($dropdown->id)),
            'edit_link' => URL::build('/panel/cs-dropdown/', 'action=edit&id=' . Output::getClean($dropdown->id)),
            'setting_link' => URL::build('/panel/cs-dropdown/setting', 'action=setting&id=' . Output::getClean($dropdown->id)),
            'delete_link' => URL::build('/panel/cs-dropdown/', 'action=delete&id=' . Output::getClean($dropdown->id)),
            'dropdown_title' => Output::getClean($dropdown->dropdown_title),
            'enabled' => Output::getClean($dropdown->enabled)
        ];
    }
    $smarty->assign([
        'DROPDOWN_LIST' => $dropdown_list_array
    ]);
} else {
    $smarty->assign([
        'NO_SERVER' => $csLanguage->get('general', 'no_servers')
    ]);
}


// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);
$template->onPageLoad();

if (Session::exists('staff_cs_dropdown'))
    $success = Session::flash('staff_cs_dropdown');

if (isset($success))
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);

if (isset($errors) && count($errors))
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);

$smarty->assign([
    'TOKEN' => Token::get(),
]);

require(ROOT_PATH . '/core/templates/panel_navbar.php');

$template->displayTemplate($template_file, $smarty);

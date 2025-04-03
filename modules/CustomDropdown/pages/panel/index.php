<?php

global $user, $pages, $cache, $widgets, $template, $navigation, $cc_nav, $staffcp_nav, $template_file;

// Authentication and access rights check
if ($user->isLoggedIn()) {
    if (!$user->canViewStaffCP()) {
        Redirect::to(URL::build('/'));
    }
    if (!$user->isAdmLoggedIn()) {
        Redirect::to(URL::build('/panel/auth'));
    } elseif (!$user->hasPermission('admincp.csdropdown')) {
        require_once(ROOT_PATH . '/403.php');
    }
} else {
    Redirect::to(URL::build('/login'));
}

const PAGE = 'panel';
const PARENT_PAGE = 'cs_divider';
const PANEL_PAGE = 'cs_dropdown';

require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Assign template variables
$template->getEngine()->addVariables([
    'SUBMIT' => csd_trans('submit'),
    'YES' => csd_trans('yes'),
    'NO' => csd_trans('no'),
    'BACK' => csd_trans('back'),
    'ARE_YOU_SURE' => csd_trans('are_you_sure'),
    'CONFIRM_DELETE' => csd_trans('confirm_delete'),
    'TITLE' => csd_trans('title'),
    'ADD_NEW_DROPDOWN' => csd_trans('add_new_dropdown'),
    'DROPDOWN_TITLE' => csd_trans('dropdown_title'),
    'DROPDOWN_LOCATION' => csd_trans('dropdown_location'),
    'DROPDOWN_ICON' => csd_trans('dropdown_icon'),
    'DROPDOWN_ORDER' => csd_trans('dropdown_order'),
    'REMOVE' => csd_trans('remove'),
    'SETTINGS' => csd_trans('settings'),
    'EDIT' => csd_trans('edit'),
    'SELECT_PAGE' => csd_trans('select_page'),
    'ADD_PAGE' => csd_trans('add_page'),
    'NO_CREATED_PAGES' => csd_trans('no_created_pages'),
    'LINK_NAVBAR' => csd_trans('page_link_navbar'),
    'LINK_FOOTER' => csd_trans('page_link_footer'),
]);

$errors = [];

// --- Dispatching the action ---
$action = $_GET['action'] ?? null;

if ($action === null) {
    require_once __DIR__ . '/actions/list_dropdown.php';
} else {
    $file = match ($action) {
        'edit'         => __DIR__ . '/actions/edit_dropdown.php',
        'setting'      => __DIR__ . '/actions/setting_dropdown.php',
        'setting_edit' => __DIR__ . '/actions/edit_page.php',
        'delete'       => __DIR__ . '/actions/delete_dropdown.php',
        'enabled'      => __DIR__ . '/actions/toggle_dropdown.php',
        default        => null,
    };

    if ($file === null) {
        Redirect::to(URL::build('/panel/cs-dropdown'));
    }

    require_once $file;
}

// Load modules and template
$navs = [$navigation, $cc_nav, $staffcp_nav];
Module::loadPage($user, $pages, $cache, null, $navs, $widgets, $template);
$template->onPageLoad();

if (Session::exists('staff_cs_dropdown')) {
    $success = Session::flash('staff_cs_dropdown');
}

if (isset($success)) {
    $template->getEngine()->addVariables([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => csd_trans('success')
    ]);
}

if (!empty($errors)) {
    $template->getEngine()->addVariables([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => csd_trans('error')
    ]);
}

$template->getEngine()->addVariables([
    'TOKEN' => Token::get(),
    'PARENT_PAGE' => PARENT_PAGE,
    'PAGE' => PANEL_PAGE,
]);

require(ROOT_PATH . '/core/templates/panel_navbar.php');
$template->displayTemplate($template_file);

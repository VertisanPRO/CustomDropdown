<?php

require_once (__DIR__ . '/migrations/CustomDropdownPagesMigration.php');
require_once (__DIR__ . '/migrations/CustomDropdownMigration.php');
require_once (__DIR__ . '/models/CustomDropdownPage.php');
require_once (__DIR__ . '/models/CustomDropdown.php');
require_once(__DIR__ . '/module.php');

$GLOBALS['csd_language'] = new Language(__DIR__ . '/language');

function csd_trans($key, $default = null, $variables = []): string
{
    $variables['defaultValue'] = $default;
    return $GLOBALS['csd_language']->get('general', $key, $variables);
}

$module = new CustomDropdown_Module();

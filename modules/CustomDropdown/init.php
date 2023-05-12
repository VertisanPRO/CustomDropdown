<?php

$INFO_MODULE = [
    'name' => 'CustomDropdown',
    'author' => '<a href="https://github.com/VertisanPRO" target="_blank" rel="nofollow noopener">Vertisan</a>',
    'module_ver' => '1.2.4',
    'nml_ver' => '2.1.0',
];

$csLanguage = new Language(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/language', LANGUAGE);

$GLOBALS['csLanguage'] = $csLanguage;

require_once(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/module.php');

$module = new CustomDropdown_Module($language, $pages, $INFO_MODULE);

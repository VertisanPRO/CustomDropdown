<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/tree/v2/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  CustomDropdown By xGIGABAITx
 */

$INFO_MODULE = array(
	'name' => 'CustomDropdown',
	'author' => '<a href="https://tensa.co.ua" target="_blank" rel="nofollow noopener">xGIGABAITx</a>',
	'module_ver' => '1.2.1',
	'nml_ver' => '2.0.0-pr12',
);

$CustomDropdownLanguage = new Language(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/language', LANGUAGE);

$GLOBALS['CustomDropdownLanguage'] = $CustomDropdownLanguage;

require_once(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/module.php');

$module = new CustomDropdown_Module($language, $pages, $INFO_MODULE);

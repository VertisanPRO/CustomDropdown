<?php

$INFO_MODULE = array(
  'name' => 'CustomDropdown',
  'author' => '<a href="https://tensa.co.ua" target="_blank" rel="nofollow noopener">xGIGABAITx</a>',
  'module_ver' => '1.2.3',
  'nml_ver' => '2.0.0-pr13',
);

$csLanguage = new Language(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/language', LANGUAGE);

$GLOBALS['csLanguage'] = $csLanguage;

require_once(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/module.php');

$module = new CustomDropdown_Module($language, $pages, $INFO_MODULE);

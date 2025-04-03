<?php

$GLOBALS['csd_language'] = new Language(__DIR__ . '/language');

function csd_trans($key, $default = null, $variables = []): string
{
    $variables['defaultValue'] = $default;
    return $GLOBALS['csd_language']->get('general', $key, $variables);
}

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/migrations/' . $class . '.php',
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


require_once(__DIR__ . '/module.php');
$module = new CustomDropdown_Module();

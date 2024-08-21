<?php
// Do not change this line
define('MODULE_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('MODULE_SRC_DIR', __DIR__ . DIRECTORY_SEPARATOR);

$moduleTemplatesFile = MODULE_DIR . REL_CONFIG_DIR . 'templates';
$moduleTemplatesDir = file_exists($moduleTemplatesFile) ? file_get_contents($moduleTemplatesFile) : 'WebComponents';

define('CONFIG_WEBCOMPONENTS', file_exists(CONFIG_DIR . 'webcomponents') ? trim(file_get_contents(CONFIG_DIR . 'webcomponents')) : $moduleTemplatesDir);
define('CUSTOM_WEBCOMPONENTS_ROOT', SRC_ROOT . CONFIG_WEBCOMPONENTS . DIRECTORY_SEPARATOR);


<?php
$configDir = siteConfigPath();

$moduleTemplatesFile = SRC_ROOT . REL_CONFIG_DIR . 'templates';
$configTemplatesDir = file_exists($moduleTemplatesFile) ? SRC_ROOT . file_get_contents($moduleTemplatesFile) : 'WebComponents';

define('CONFIG_WEBCOMPONENTS', file_exists($configDir . 'webcomponents') ? trim(file_get_contents($configDir . 'webcomponents')) : $configTemplatesDir);
define('CUSTOM_WEBCOMPONENTS_ROOT', siteSrcPath() . CONFIG_WEBCOMPONENTS . DIRECTORY_SEPARATOR);

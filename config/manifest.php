<?php
//$moduleTemplatesFile = MODULE_DIR . REL_CONFIG_DIR . 'templates';
//$moduleTemplatesDir = file_exists($moduleTemplatesFile) ? file_get_contents($moduleTemplatesFile) : 'WebComponents';

//$configWebComponents = file_exists(CONFIG_DIR . 'webcomponents') ? trim(file_get_contents(CONFIG_DIR . 'webcomponents')) : $moduleTemplatesDir;
//$customWebComponentsRoot =  SRC_ROOT . $configWebComponents . DIRECTORY_SEPARATOR;

return [
    "tag" => "WebComponent",
    "name" => "ephect-io/web-component",
    "entrypoint" => \Ephect\Modules\WebComponent\WebComponent::class,
    "templates" => "WebComponents",
    "description" => "An Ephect framework module to build web components.",
    "version" => "1.0.0",
];
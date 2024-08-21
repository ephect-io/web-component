<?php

namespace Ephect\Modules\WebComponent;

use Ephect\Forms\Components\Application\ApplicationComponent;
use Ephect\Forms\Components\Plugin;
use Ephect\Forms\Registry\ComponentRegistry;
use Ephect\Framework\Templates\TemplateMaker;

class WebComponent extends Plugin
{
    public function makeComponent(string $filename, string &$html): void
    {
        $info = (object)pathinfo($filename);
        $namespace = CONFIG_NAMESPACE;
        $function = $info->filename;

        $componentTextMaker = new TemplateMaker(MODULE_SRC_DIR . 'Templates' . DIRECTORY_SEPARATOR . 'Component.tpl');
        $componentTextMaker->make(['funcNamespace' => $namespace, 'funcName' => $function, 'funcBody' => '', 'html' => $html]);
        $componentTextMaker->save(COPY_DIR . $filename);
    }
}

<?php

namespace Ephect\Modules\WebComponent;

use Ephect\Forms\Components\Component;
use Ephect\Framework\Templates\TemplateMaker;

class WebComponent extends Component
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

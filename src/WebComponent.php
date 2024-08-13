<?php

namespace Ephect\Plugins\WebComponent;

use Ephect\Framework\Components\Application\ApplicationComponent;
use Ephect\Framework\Modules\ModuleMaker;
use Ephect\Framework\Registry\ComponentRegistry;
use Ephect\Framework\Utils\File;

class WebComponent extends ApplicationComponent
{

    public function makeComponent(string $filename, string &$html): void
    {
        $info = (object) pathinfo($filename);
        $namespace = CONFIG_NAMESPACE;
        $function = $info->filename;

        $html = ModuleMaker::makeTemplate('Component.tpl', ['funcNamespace' => $namespace, 'funcName' => $function, 'funcBody' => '', 'html' => $html]);

        File::safeWrite(COPY_DIR . $filename, $html);

    }

    public function analyse(): void
    {
        parent::analyse();

        ComponentRegistry::write($this->getFullyQualifiedFunction(), $this->getSourceFilename());
        ComponentRegistry::safeWrite($this->getFunction(), $this->getFullyQualifiedFunction());
        ComponentRegistry::save();
    }

    public function parse(): void
    {
        parent::parse();
        $this->cacheHtml();
    }

}

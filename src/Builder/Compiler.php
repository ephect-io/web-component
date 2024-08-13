<?php

namespace Ephect\Plugins\WebComponent\Builder;

use Ephect\Framework\Modules\ModuleMaker;
use Ephect\Framework\Utils\File;
use Ephect\Plugins\WebComponent\Manifest\ManifestStructure;
use Ephect\Plugins\WebComponent\Manifest\ManifestWriter;
use Exception;

class Compiler
{

    /**
     * Second creation step of the WebComponent
     *
     * Create a manifest file include all details passed to the command line
     *
     * @param string $tagName
     * @param string $className
     * @param string $entrypoint
     * @param array $arguments
     * @param string $destDir
     * @return void
     * @throws Exception
     */
    function saveManifest(string $tagName, string $className, string $entrypoint, array $arguments, string $destDir): void
    {

        $struct = new ManifestStructure([
            'tag' => $tagName,
            'class' => $className,
            'entrypoint' => $entrypoint,
            'arguments' => $arguments,
        ]);

        $writer = new ManifestWriter($struct, $destDir);
        $writer->write();
    }

    /**
     * Third and last creation step of the WebComponent
     *
     * Read templates text, replace the markups and save into user application directory
     *
     * @param string $tagName
     * @param string $className
     * @param bool $hasBackendProps
     * @param string $entrypoint
     * @param array $arguments
     * @param string $srcDir
     * @param string $destDir
     * @return void
     */
    function copyTemplates(string $tagName, string $className, bool $hasBackendProps, string $entrypoint, array $arguments, string $srcDir, string $destDir): void
    {

        $classText = ModuleMaker::makeTemplate('Base.class.tpl', ['Base' => $className, 'entrypoint' => $entrypoint,]);

        $objectName = lcfirst($className);
        $componentText = ModuleMaker::makeTemplate('Base.tpl', [
            'Base' => $className,
            'tag-name' => $tagName,
            'entrypoint' => $entrypoint,
            'objectName' => $objectName,
        ]);

        $baseElementText = ModuleMaker::makeTemplate('BaseElement.tpl', ['Base' => $className,]);

        $parameters = $arguments;
        $arguments[] = 'styles';
        $arguments[] = 'classes';

        if (count($arguments) == 0) {
            $classText = str_replace('({{DeclaredAttributes}})', "()", $classText);

            $baseElementText = str_replace('{{GetAttributes}}', '', $baseElementText);
            $componentText = str_replace('{{Attributes}}', '', $componentText);

            File::safeWrite($destDir . "$className.class.js", $classText);
            File::safeWrite($destDir . "$className.phtml", $componentText);
            File::safeWrite($destDir . $className . "Element.js", $baseElementText);

            return;
        }

        $properties = '';
        foreach ($arguments as $property) {
            $properties .= <<< HTML
                this.$property\n
                HTML;
            $properties .= '            ';
        }

        $baseElementText = str_replace('{{Properties}}', $properties, $baseElementText);

        $attributes = array_map(function ($item) {
            return "'$item'";
        }, $arguments);

        $thisParameters = array_map(function ($item) {
            return "this." . $item;
        }, $parameters);

        $declaredAttributes = implode(", ", $parameters);
        $attributes = implode(", ", $attributes);

        $argumentListAndResult = $thisParameters;
        $thisAttributeList = implode(", ", $thisParameters);

        $observeAttributes = <<< HTML
                static get observeAttributes() {
                        /**
                        * Attributes passed inline to the component
                        */
                        return [$attributes]
                    }
            HTML;

        $baseElementText = str_replace('{{ObserveAttributes}}', $observeAttributes, $baseElementText);

        $getAttributes = '';
        foreach ($arguments as $attribute) {
            $getAttributes .= <<< HTML
                    get $attribute() {
                            return this.getAttribute('$attribute') ?? null
                        }\n
                HTML;
            $getAttributes .= '    ';
        }

        $classText = str_replace('({{DeclaredAttributes}})', "(" . $declaredAttributes . ")", $classText);

        $baseElementText = str_replace('{{GetAttributes}}', $getAttributes, $baseElementText);
        $componentText = str_replace('{{AttributeList}}', $thisAttributeList, $componentText);

        File::safeWrite($destDir . $className . CLASS_JS_EXTENSION, $classText);
        File::safeWrite($destDir . $className . "Element" . JS_EXTENSION, $baseElementText);

        if ($hasBackendProps) {
            $namespace = CONFIG_NAMESPACE;

            $componentText = str_replace("</template>", "    <h2>{{ foo }}</h2>\n</template>", $componentText);

            $funcBody = <<< FUNC_BODY
            useEffect(function (\$slot, /* string */ \$foo) {
                \$foo = "It works!"; 
            });
            FUNC_BODY;

            $componentText = ModuleMaker::makeTemplate('Component.tpl', ['funcNamespace' => $namespace, 'funcName' => $className, 'funcBody' => $funcBody, 'html' => $componentText]);

        }

        File::safeWrite($destDir . "$className.phtml", $componentText);

    }
}

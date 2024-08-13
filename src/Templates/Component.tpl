<?php
namespace {{funcNamespace}};

use Ephect\Plugins\WebComponent\Attributes\WebComponentZeroConf;
use function Ephect\Hooks\useEffect;

#[WebComponentZeroConf]
function {{funcName}} ($slot): string
{
{{funcBody}}
return (<<< HTML
<WebComponent>
{{html}}
</WebComponent>
HTML);
}
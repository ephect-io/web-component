<?php

namespace Ephect\Modules\WebComponent\Middlewares;

use Ephect\Forms\Components\ComponentEntityInterface;
use Ephect\Forms\Middlewares\ComponentParserMiddlewareInterface;
use Ephect\Framework\Registry\StateRegistry;
use function Ephect\Hooks\useState;

class WebComponentParserMiddleware implements ComponentParserMiddlewareInterface
{
    public function parse(ComponentEntityInterface|null $parent, string $motherUID, string $funcName, string $props, array $arguments): void
    {
        StateRegistry::load();
        useState(["middlewares" => [WebComponentBuilderMiddleware::class => (object) $arguments],]);
        StateRegistry::saveByMotherUid($motherUID);
    }
}
<?php

namespace Ephect\Plugins\WebComponent\Manifest;


use Ephect\Framework\Structure\Structure;

class ManifestStructure extends Structure
{
    public string $tag = '';
    public string $class = '';
    public string $entrypoint = '';
    public array $arguments = [];
}

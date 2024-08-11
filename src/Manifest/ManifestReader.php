<?php

namespace Ephect\Plugins\WebComponent\Manifest;

use Ephect\Framework\Utils\File;

class ManifestReader
{
    public function __construct(private readonly string $motherUID, private readonly string $name)
    {
    }

    public function read(): ManifestEntity
    {

        $manifestFilename = 'manifest.json';
        $manifestCache = CACHE_DIR . $this->motherUID . DIRECTORY_SEPARATOR . $this->name . '.' . $manifestFilename;

        $moduleTemplatesFile = SRC_ROOT . REL_CONFIG_DIR . 'templates';
        $configTemplatesDir = file_exists($moduleTemplatesFile) ? SRC_ROOT . file_get_contents($moduleTemplatesFile) : null;

        if (!file_exists($manifestCache) && file_exists($configTemplatesDir)) {
            copy($configTemplatesDir . $this->name . DIRECTORY_SEPARATOR . $this->name . '.' . $manifestFilename, $manifestCache);
        }

        $manifestJson = File::safeRead($manifestCache);
        $manifest = json_decode($manifestJson, JSON_OBJECT_AS_ARRAY);

        $struct = new ManifestStructure($manifest);

        return new ManifestEntity($struct);
    }
}

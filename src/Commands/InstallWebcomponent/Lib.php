<?php

namespace Ephect\Commands\InstallWebcomponent;

use Ephect\Framework\Commands\AbstractCommandLib;
use Ephect\Framework\Components\PluginInstaller;

class Lib extends AbstractCommandLib
{
    public function install(string $workingDirectory): void
    {
        PluginInstaller::install($workingDirectory);
    }
}


<?php

namespace Ephect\Commands\InstallWebcomponent;

use Ephect\Framework\Commands\AbstractCommand;
use Ephect\Framework\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "install", subject: "web-component")]
#[CommandDeclaration(desc: "Install web-component plugin.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $workingDirectory = $this->application->getArgi(2);
        
        $lib = new Lib($this->application);
        $lib->install($workingDirectory);

        return 0;
    }
}

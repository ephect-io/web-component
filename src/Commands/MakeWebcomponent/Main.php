<?php

namespace Ephect\Plugins\WebComponent\Commands\MakeWebComponent;

use Ephect\Framework\Commands\AbstractCommand;
use Ephect\Framework\Commands\Attributes\CommandDeclaration;

#[CommandDeclaration(verb: "make", subject: "webcomponent")]
#[CommandDeclaration(desc: "Create the base tree of a webComponent.")]
class Main extends AbstractCommand
{
    public function run(): int
    {
        $lib = new Lib($this->application);
        $lib->createWebComponentBase();

        return 0;
    }
}

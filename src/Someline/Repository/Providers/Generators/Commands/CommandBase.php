<?php

namespace Someline\Repository\Providers\Generators\Commands;

use File;
use Illuminate\Console\Command;

class CommandBase extends Command
{

    public function handle()
    {
        $this->fire();
    }

    public function fire()
    {
        // ...
    }

}

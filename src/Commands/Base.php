<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Psy\Readline\Hoa\Console;

class Base extends Command
{
    protected $signature = 'xtnd:make:base {model} {structure} {--api}';

    protected $description = 'Add a column to a table in a migration';

    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass(
            $this->replaceType($stub, $this->getNameInput()),
            $name
        );
    }

    protected function replaceType($stub, $name): string
    {
        $type = strtolower(str_replace('Component', '', $name));

        return str_replace(['{{ type }}', '{{type}}'], $type, $stub);
    }

    public function handle()
    {
        $this->getFiles();
    }

    private function getFiles()
    {

        $files = Storage::files("../stubs");
        echo $files;
    }


}

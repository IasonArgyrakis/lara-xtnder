<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Base extends Command
{
    protected $signature = 'xtnd:make:base {model} {structure} {--api}';

    protected $description = 'Test ';
    /**
     * @var \Symfony\Component\Finder\SplFileInfo[]
     */
    private array $files;

    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass(
            $this->replaceType($stub, $this->getNameInput()),
            $name
        );
    }

    protected function replace($stub, $name): string
    {
        $type = strtolower(str_replace('Component', '', $name));

        return str_replace(['{{ type }}', '{{type}}'], $type, $stub);
    }

    protected function replacePlaceholder($stub_content, $param_name,$param_value): string
    {
        return str_replace(["{{ $param_name }}", "{{$param_name}}"], $param_value, $stub_content);
    }



    public function handle()
    {
        $this->getFiles();
    }

    private function getFiles()
    {
        $this->files = File::files(__DIR__."/../stubs");

    }

    private function generateMigration(){
        $template = file_get_contents(__DIR__."/../stubs/migration.create.stub");

        $text=$this->replacePlaceholder($template,"model_attributes","ok");


        dd($text);







    }


}

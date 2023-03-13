<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Base extends Command
{
    protected $signature = 'xtnd:make:base {modelName} {structure} {--api}';

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


    protected function replacePlaceholder($stub_content, $param_name, $param_value): string
    {
        return str_replace(["{{ $param_name }}", "{{$param_name}}"], $param_value, $stub_content);
    }


    public function handle()
    {
        $this->makeNames();
        $this->makePropeties();
        $this->getFiles();
        $this->generateCreateMigration();
    }

    private function makeNames()
    {
        $modelName = $this->argument('modelName');
        $this->modelname = Str::of($modelName);
        $this->names['migration'] = [
            "file_name" => $this->modelname->snake()->plural(),
            "table_name" => $this->modelname->snake()->plural(),
        ];
        $this->names['model'] = [
            "file_name" => $this->modelname->studly()->singular(),
            "class_name" => $this->modelname->studly()->singular(),
        ];


    }

    private function makePropeties()
    {
        $structure = $this->argument('structure');
        $props =explode(" ",$structure);

        $this->modelproperites=[];
        foreach ($props as $prop) {
            $prop =explode(":",$prop);
            $this->modelproperites[]=["name"=>Arr::get($prop,0,""),"type"=>Arr::get($prop,1,"string")];

        }





    }

    private function getFiles()
    {
        $this->files = File::files(__DIR__."/../stubs");

    }



    private function generateCreateMigration()
    {
        $template = file_get_contents(__DIR__."/../stubs/migration.create.stub");
        $template = $this->replacePlaceholder($template, "table",$this->names['migration']['table_name']);
        $template = $this->replacePlaceholder($template, "model_attributes", "ok");
        $file_name = date("Y_m_d_His")."_create_".$this->names['migration']['file_name'];
        $new_file_path = database_path('migrations')."/".$file_name.".php";
        File::put($new_file_path, $template);
    }

    private function generateFactory()
    {
        $template = file_get_contents(__DIR__."/../stubs/migration.create.stub");
        $template = $this->replacePlaceholder($template, "table",$this->names['migration']['table_name']);
        $template = $this->replacePlaceholder($template, "model_attributes", "ok");
        $file_name = date("Y_m_d_His")."_create_".$this->names['migration']['file_name'];
        $new_file_path = database_path('migrations')."/".$file_name.".php";
        File::put($new_file_path, $template);
    }


}

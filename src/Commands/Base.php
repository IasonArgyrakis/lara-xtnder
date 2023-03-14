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
    private array $files;
    protected array $templates;
    protected array $modelproperites;

    protected function replacePlaceholder($stub_content, $param_name, $param_value): string
    {
        return str_replace(["{{ $param_name }}", "{{$param_name}}"], $param_value, $stub_content);
    }


    public function handle()
    {
        $this->info("Making Factory");
        $this->readStructure();

    }

    protected function readName()
    {
   
        $this->modelname = Str::of($this->argument('modelName'));
        $this->names['migration'] = [
            "file_name" => $this->modelname->snake()->plural(),
            "table_name" => $this->modelname->snake()->plural(),
        ];
        $this->names['factory'] = [
            "file_name" => $this->modelname->studly()->singular()."Factory",
            "factoryNamespace" => 'Database\Factories',
            "namespacedModel" => 'App\Models\\'.$this->modelname->studly()->singular(),
            "factory" => $this->modelname->studly()->singular(),
        ];
        $this->names['store_request'] = [
            "file_name" => $this->modelname->studly()->singular()."StoreRequest",
            "requestNamespace" => 'App\Http\Requests',
            "class" => $this->modelname->studly()->singular()."StoreRequest",
        ];
        $this->names['update_request'] = [
            "file_name" => $this->modelname->studly()->singular()."UpdateRequest",
            "requestNamespace" => 'App\Http\Requests',
            "class" => $this->modelname->studly()->singular()."UpdateRequest",
        ];
        $this->names['model'] = [
            "file_name" => $this->modelname->studly()->singular(),
            "class_name" => $this->modelname->studly()->singular(),
        ];

    }
    function convertToJSON($input)
    {
        $json = str_replace('{', '{"', $input);
        $json = str_replace(':', '":"', $json);
        $json = str_replace(',', '","', $json);
        $json = str_replace('}', '"}', $json);
        return $json;
    }

    protected function readStructure()
    {
   

        $structure = $this->argument('structure');

        $structure = $this->convertToJSON($structure);

        $props = json_decode($structure, true);
        foreach ($props as $key => $value) {
            $is_relation = false;
            if (Str::contains($key, "_id")) {
                $is_relation = true;
            }
            $this->modelproperites[] = [
                "is_releation" => $is_relation,
                "name" => $key,
                "type" => $value
            ];


        }
    }


  




}

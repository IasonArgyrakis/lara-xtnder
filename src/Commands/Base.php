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
    private array $templates;
    private array $modelproperites;

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
        $this->readName();
        $this->readStructure();
        //$this->getFiles();
        $this->generateCreateTableMigration();
    }

    private function readName()
    {
        $this->modelname = Str::of($this->argument('modelName'));
        $this->names['migration'] = [
            "file_name" => $this->modelname->snake()->plural(),
            "table_name" => $this->modelname->snake()->plural(),
        ];
        $this->names['model'] = [
            "file_name" => $this->modelname->studly()->singular(),
            "class_name" => $this->modelname->studly()->singular(),
        ];

    }

    private function readStructure()
    {
        function convertToJSON($input)
        {
            $json = str_replace('{', '{"', $input);
            $json = str_replace(':', '":"', $json);
            $json = str_replace(',', '","', $json);
            $json = str_replace('}', '"}', $json);
            return $json;
        }

        $structure = $this->argument('structure');

        $structure = convertToJSON($structure);

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


    private function getFiles()
    {
        $this->files = File::files(__DIR__."/../stubs");

    }


    private function generateCreateTableMigration()
    {
        $this->templates['migration']['file_content'] = file_get_contents(__DIR__."/../stubs/migration.create.stub");
        $this->templates['migration']['file_name'] = date("Y_m_d_His")."_create_".$this->names['migration']['file_name'].".php";
        $this->fillCreateTableMigration();
        $this->saveCreateTableMigration();
    }


    private function fillCreateTableMigration()
    {
        $template = $this->templates['migration']['file_content'];
        $template = $this->replacePlaceholder($template, "table", $this->names['migration']['table_name']);
        $template = $this->replacePlaceholder($template, "model_attributes", $this->migrationPropertyList());
        $this->templates['migration']['file_content'] = $template;
    }

    private function saveCreateTableMigration()
    {
        $new_file_path = database_path('migrations')."/".$this->templates['migration']['file_name'];
        File::put($new_file_path, $this->templates['migration']['file_content']);
    }

    private function generateFactory()
    {
        $template = file_get_contents(__DIR__."/../stubs/migration.create.stub");
        $template = $this->replacePlaceholder($template, "table", $this->names['migration']['table_name']);
        $template = $this->replacePlaceholder($template, "model_attributes", $this->migrationPropertyList());
        $file_name = date("Y_m_d_His")."_create_".$this->names['migration']['file_name'];
        $new_file_path = database_path('migrations')."/".$file_name.".php";
        File::put($new_file_path, $template);
    }


    private function migrationPropertyList()
    {
        $migration_text = '';
        foreach ($this->modelproperites as $property) {

            if ($property['is_releation']) {
                $table_name = Str::of($property['type'])->plural()->snake();
                $migration_text .= "\$table->foreign('{$property['name']}')->references('id')->on('{$table_name}')->onUpdate('cascade')->onDelete('cascade')";
            } else {
                $migration_text .= match ($property['type']) {
                    "bool" => "\$table->boolean('{$property['name']}')",
                    "int" => "\$table->integer('{$property['name']}')",
                    default => "\$table->string('{$property['name']}')",
                };
            }
            $migration_text .= ";\r\t\t\t";


        }

        return $migration_text;
    }


}

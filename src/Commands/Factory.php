<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Factory extends Base
{
    protected $signature = 'xtnd:make:factory {modelName} {structure}';

    protected $description = 'Test ';

    protected $file_type = "factory";

    public function handle()
    {
        $this->readName();
        $this->readStructure();
        $this->generateFactory();
    }


    private function generateFactory()
    {
        $this->templates[$this->file_type]['file_content'] = file_get_contents(__DIR__."/../stubs/factory.stub");
        $this->templates[$this->file_type]['file_name'] = $this->names[$this->file_type]['file_name'].".php";
        $this->makeFactory();

    }

    private function makeFactory()
    {
        //FIll
        $template = $this->templates[$this->file_type]['file_content'];
        $template = $this->replacePlaceholder($template, "factoryNamespace",
            $this->names[$this->file_type]['factoryNamespace']);
        $template = $this->replacePlaceholder($template, "namespacedModel",
            $this->names[$this->file_type]['namespacedModel']);
        $template = $this->replacePlaceholder($template, "factory", $this->names[$this->file_type]['factory']);
        $template = $this->replacePlaceholder($template, "definition", $this->makeFactoryDefinition());
        $this->templates[$this->file_type]['file_content'] = $template;
        //Save
        $this->saveFactory();


    }

    private function makeFactoryDefinition()
    {

        $factory_text = '';
        foreach ($this->modelproperites as $property) {

            if ($property['is_releation']) {
                //skip
                $factory_text .= "";
            } else {
                $factory_text .= match ($property['type']) {
                    "bool" => "\"{$property['name']}\" => fake()->boolean()",
                    "int" => "\"{$property['name']}\" => fake()->numberBetween()",
                    "string" => "\"{$property['name']}\" => fake()->name()",
                    default => "\"{$property['name']}\" => fake()->name()",
                };

                $factory_text .= ",\r\t\t\t";
            }


        }
         return $factory_text;
    }

    private function saveFactory()
    {

        $new_file_path = database_path(Str::plural($this->file_type))."/".$this->templates[$this->file_type]['file_name'];
        if (File::exists($new_file_path)) {
            if ($this->confirm('File exists this will overwrite! Do you wish to continue?', false)) {

                File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
            } else {
                $new_file_path = database_path(Str::plural($this->file_type))."/".date("Y_m_d_His")."_".$this->templates[$this->file_type]['file_name'];
                File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
                $this->info("file saved with name $new_file_path");

            }
        }else{
            File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
        }

    }


}

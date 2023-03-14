<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UpdateRequest extends Base
{
    protected $signature = 'xtnd:make:update-request {modelName} {structure}';

    protected $description = 'Test ';

    protected $file_type = "update_request";

    public function handle()
    {
        $this->info("Making Store Request");
        $this->readName();
        $this->readStructure();
        $this->generateUpdateRequest();
    }


    private function generateUpdateRequest()
    {
        $this->templates[$this->file_type]['file_content'] = file_get_contents(__DIR__."/../stubs/request.stub");
        $this->templates[$this->file_type]['file_name'] = $this->names[$this->file_type]['file_name'].".php";
        $this->makeUpdateRequest();

    }

    private function makeUpdateRequest()
    {
        //FIll
        $template = $this->templates[$this->file_type]['file_content'];
        $template = $this->replacePlaceholder($template, "requestNamespace",
            $this->names[$this->file_type]['requestNamespace']);
        $template = $this->replacePlaceholder($template, "class", $this->names[$this->file_type]['class']);
        $template = $this->replacePlaceholder($template, "definition", $this->makeUpdateRequestDefinition());
        $this->templates[$this->file_type]['file_content'] = $template;
        //Save
        $this->saveUpdateRequest();


    }

    private function makeUpdateRequestDefinition()
    {

        $request_text = '';
        $note = "//@toDo Validate-the-Validation";
        foreach ($this->modelproperites as $property) {

            if ($property['is_releation']) {
                //skip
                $request_text .= "";
            } else {
                $request_text .= match ($property['type']) {
                    "bool" => "\"{$property['name']}\" => 'boolean'",
                    "int" => "\"{$property['name']}\" => 'integer'",
                    "string" => "\"{$property['name']}\" => 'string'",
                    default => "\"{$property['name']}\" => 'string'",
                };

                $request_text .= ", $note \r\t\t\t";
            }


        }
        return $request_text;
    }

    private function saveUpdateRequest()
    {

        $new_file_path = app_path('/Http/Requests')."/".$this->templates[$this->file_type]['file_name'];
        if (File::exists($new_file_path)) {

            $this->warn("File {$this->templates[$this->file_type]['file_name']} exists");
            if ($this->confirm("Overwrite ?", false)) {

                File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
            } else {
                $new_file_path = app_path('/Http/Requests')."/"."_temp_".date("His")."_".$this->templates[$this->file_type]['file_name'];
                File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
                $this->info("file saved with name $new_file_path");

            }
        } else {
            File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
        }

    }


}

<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Controller extends Base
{
    protected $signature = 'xtnd:make:controller {modelName} {structure}';

    protected $description = 'Genrates model with $fillable props based on structure  ';


    protected $file_type = "controller";

    public function handle()
    {
        $this->info("Making Controller");
        $this->readName();
        $this->readStructure();
        $this->generateController();
    }


    private function generateController()
    {
        $this->templates[$this->file_type]['file_content'] = file_get_contents(__DIR__."/../stubs/controller.model.stub");
        $this->templates[$this->file_type]['file_name'] = $this->names[$this->file_type]['file_name'].".php";
        $this->makeController();

    }

    private function makeController()
    {
        //FIll
        $template = $this->templates[$this->file_type]['file_content'];
        $template = $this->replacePlaceholder($template, "modelNamespace",
            $this->names[$this->file_type]['modelNamespace']);
        $template = $this->replacePlaceholder($template, "class", $this->names[$this->file_type]['class']);
        $template = $this->replacePlaceholder($template, "model", $this->names[$this->file_type]['model']);
        $template = $this->replacePlaceholder($template, "updateRequest", $this->names[$this->file_type]['updateRequest']);
        $template = $this->replacePlaceholder($template, "storeRequest", $this->names[$this->file_type]['storeRequest']);
        $template = $this->replacePlaceholder($template, "modelVariable", $this->names[$this->file_type]['modelVariable']);
        $this->templates[$this->file_type]['file_content'] = $template;
        //Save
        $this->saveController();


    }
    
    private function saveController()
    {

        $new_file_path = app_path('/Http/Controllers')."/".$this->templates[$this->file_type]['file_name'];
        if (File::exists($new_file_path)) {

            $this->warn("File {$this->templates[$this->file_type]['file_name']} exists");
            if ($this->confirm("Overwrite ?", false)) {

                File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
            } else {
                $new_file_path = app_path('/Http/Controllers')."/"."_temp_".date("His")."_".$this->templates[$this->file_type]['file_name'];
                File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
                $this->info("file saved with name $new_file_path");

            }
        } else {
            File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
        }

    }


}

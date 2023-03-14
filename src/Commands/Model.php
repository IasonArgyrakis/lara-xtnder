<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Model extends Base
{
    protected $signature = 'xtnd:make:model {modelName} {structure}';

    protected $description = 'Genrates model with $fillable props based on structure  ';


    protected $file_type = "model";

    public function handle()
    {
        $this->info("Making Model");
        $this->readName();
        $this->readStructure();
        $this->generateModel();
    }


    private function generateModel()
    {
        $this->templates[$this->file_type]['file_content'] = file_get_contents(__DIR__."/../stubs/model.stub");
        $this->templates[$this->file_type]['file_name'] = $this->names[$this->file_type]['file_name'].".php";
        $this->makeModel();

    }

    private function makeModel()
    {
        //FIll
        $template = $this->templates[$this->file_type]['file_content'];
        $template = $this->replacePlaceholder($template, "modelNamespace",
            $this->names[$this->file_type]['modelNamespace']);
        $template = $this->replacePlaceholder($template, "class", $this->names[$this->file_type]['class']);
        $template = $this->replacePlaceholder($template, "fillable", $this->makeModelFilable());
        $this->templates[$this->file_type]['file_content'] = $template;
        //Save
        $this->saveModel();


    }

    private function makeModelFilable()
    {

        $text = '';
        $last=Arr::last($this->modelproperites);
        foreach ($this->modelproperites as $property) {
            if($last['name']!==$property['name']){
                $text.="'{$property['name']}',\r\t\t\t";
            }else{
                $text.="'{$property['name']}'\r\t\t\t";
            }
           
             
                



        }
        return $text;
    }

    private function saveModel()
    {

        $new_file_path = app_path('/Models')."/".$this->templates[$this->file_type]['file_name'];
        if (File::exists($new_file_path)) {

            $this->warn("File {$this->templates[$this->file_type]['file_name']} exists");
            if ($this->confirm("Overwrite ?", false)) {

                File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
            } else {
                $new_file_path = app_path('/Models')."/"."_temp_".date("His")."_".$this->templates[$this->file_type]['file_name'];
                File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
                $this->info("file saved with name $new_file_path");

            }
        } else {
            File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
        }

    }


}

<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Migration extends Base
{
    protected $signature = 'xtnd:make:migration {modelName} {structure}';

    protected $description = 'Test ';

    protected $file_type = "migration";

    public function handle()
    {
        $this->readName();
        $this->readStructure();
        $this->generateCreateTableMigration();
    }


    private function generateCreateTableMigration()
    {
        $this->templates[$this->file_type]['file_content'] = file_get_contents(__DIR__."/../stubs/migration.create.stub");
        $this->templates[$this->file_type]['file_name'] = date("Y_m_d_His")."_create_".$this->names[$this->file_type]['file_name'].".php";
        $this->fillCreateTableMigration();
        $this->saveCreateTableMigration();
    }


    private function fillCreateTableMigration()
    {
        $template = $this->templates[$this->file_type]['file_content'];
        $template = $this->replacePlaceholder($template, "table", $this->names[$this->file_type]['table_name']);
        $template = $this->replacePlaceholder($template, "model_attributes", $this->migrationPropertyList());
        $this->templates[$this->file_type]['file_content'] = $template;
    }

    private function saveCreateTableMigration()
    {
        $new_file_path = database_path(Str::plural($this->file_type))."/".$this->templates[$this->file_type]['file_name'];
        File::put($new_file_path, $this->templates[$this->file_type]['file_content']);
    }

    private function generateFactory()
    {
        $template = file_get_contents(__DIR__."/../stubs/migration.create.stub");
        $template = $this->replacePlaceholder($template, "table", $this->names[$this->file_type]['table_name']);
        $template = $this->replacePlaceholder($template, "model_attributes", $this->migrationPropertyList());
        $file_name = date("Y_m_d_His")."_create_".$this->names[$this->file_type]['file_name'];
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

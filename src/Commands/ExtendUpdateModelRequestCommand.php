<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ExtendUpdateModelRequestCommand extends Command
{
    protected $signature = 'xtnd:update-model-request:add {name} {type} {modelName}';

    protected $description = 'Add a key-value pair to the rules';

    public function handle()
    {
        //make:model -a
        $property_name = $this->argument('name');
        $property_type = $this->argument('type');
        $modelName = Str::lower($this->argument('modelName'));


        // Get the latest factory file

        $factoryFiles = File::files(app_path('/Http/Requests'));

        foreach ($factoryFiles as $factoryFile) {
            $name = (Str::lower($factoryFile->getFilename()));

            if (Str::contains($name, "update" . $modelName)) {
                $latestFactoryFile = $factoryFile;
                $factoryFileContents = File::get($factoryFile);


            }

        }


        $note = "//@toDo Validate-the-Validation";

        $factory_text = match ($property_type) {
            "bool" => " \"$property_name\" => \"boolean\" ,$note",
            "int" => " \"$property_name\" => \"integer\" ,$note",
            default => " \"$property_name\" => \"string\" ,$note",
        };

        if (!str_contains($factoryFileContents, $factory_text)) {


            // Add the column to the end of the schema ask if exits
            $factoryFileContents = str_replace(
                "//\n        ];\n",
                "$factory_text\n            //\n        ];\n",
                $factoryFileContents
            );
            // Write the modified factory file
            File::put($latestFactoryFile, $factoryFileContents);
        } else {
            echo  ("File already contains '$factory_text'' \n");


        }
    }
}

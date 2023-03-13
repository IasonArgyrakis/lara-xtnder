<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ExtendFactoryCommand extends Command
{
    protected $signature = 'xtnd:factory:add {name} {type} {modelName}';

    protected $description = 'Add a key-value pair to the definition';

    public function handle()
    {
        $property_name = $this->argument('name');
        $property_type = $this->argument('type');
        $modelName = Str::lower($this->argument('modelName'));


        // Get the latest factory file

        $factoryFiles = File::files(database_path('factories'));

        foreach ($factoryFiles as $factoryFile) {
            $name = (Str::lower($factoryFile->getFilename()));

            if (Str::contains($name, $modelName)) {
                $latestFactoryFile = $factoryFile;
                $factoryFileContents = File::get($factoryFile);


            }

        }


        $factory_text = match ($property_type) {
            "bool" => " \"$property_name\" => fake()->boolean()",
            "int" => " \"$property_name\" => fake()->numberBetween()",
            default => " \"$property_name\" => fake()->name()",
        };

        if (!str_contains($factoryFileContents, $factory_text)) {

            // Add the column to the end of the schema ask if exits
            $factoryFileContents = str_replace(
                "//\n        ];\n",
                "$factory_text,\n            //\n        ];\n",
                $factoryFileContents
            );
            // Write the modified factory file
            File::put($latestFactoryFile, $factoryFileContents);
        }else{

            echo ("File already contains '$factory_text'' \n");

//            if ($this->confirm('Do you wish to continue?', false)) {
//                // Add the column to the end of the schema ask if exits
//                $factoryFileContents = str_replace(
//                    "//\n        ];\n",
//                    "//@todo Fix-> $factory_text,\n            //\n        ];\n",
//                    $factoryFileContents
//                );
//                // Write the modified factory file
//                File::put($latestFactoryFile, $factoryFileContents);
//            }


        }
    }
}

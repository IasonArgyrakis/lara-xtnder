<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtendMigrationCommand extends Command
{
    protected $signature = 'xtnd:migration:add {property} {type}';

    protected $description = 'Add a column to a table in a migration';

    public function handle()
    {
        //make:model -a
        $property_name = $this->argument('property');
        $property_type = $this->argument('type');


        // Get the latest migration file
        $migrationFiles = File::files(database_path('migrations'));
        $latestMigrationFile = last($migrationFiles);
        $migrationFileContents = File::get($latestMigrationFile);


        $migration_text = match ($property_type) {
            "bool" => "\$table->boolean('$property_name')",
            "int" => "\$table->integer('$property_name')",
            default => "\$table->string('$property_name')",
        };

        if (!str_contains($migrationFileContents, $migration_text)) {
            // Add the column to the end of the schema ask if exits
            $migrationFileContents = str_replace(
                "\$table->timestamps();",
                "$migration_text;\n            \$table->timestamps();",
                $migrationFileContents
            );

            // Write the modified migration file
            File::put($latestMigrationFile, $migrationFileContents);
        } else {
            echo ("File already contains '$migration_text'' \n");

//            if ( $this->confirm('Do you wish to continue?', false)) {
//
//                // Add the column to the end of the schema ask if exits
//                $migrationFileContents = str_replace(
//                    "\$table->timestamps();",
//                    "//@todo Fix-> $migration_text;\n            \$table->timestamps();",
//                    $migrationFileContents
//                );
//                File::put($latestMigrationFile, $migrationFileContents);
//            }

        }


    }
}

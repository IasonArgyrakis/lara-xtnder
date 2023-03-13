<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ExtendApiRoutesCommand extends Command
{
    protected $signature = 'xtnd:routes-api:add {name} {type} {modelName}';

    protected $description = 'Add a resource route to the api.php to the definition';

    public function handle()
    {
        $property_name = $this->argument('name');
        $property_type = $this->argument('type');
        $modelName = $this->argument('modelName');
        $route_name = Str::lower($modelName);

        $route_text = "Route::resource('/$route_name',$modelName" . "Controller::class);";
        $route_contents = File::get("routes/api.php");
        if (!str_contains($route_contents, $route_text)) {
            File::append("routes/api.php", "\n" . $route_text);
        }else{
            $route_text="//@todo Fix-> ".$route_text;
            echo  ("File already contains '$route_text'' \n");
            //File::append("routes/api.php", "\n" . $route_text);
        }


    }
}

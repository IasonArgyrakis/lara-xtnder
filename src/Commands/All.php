<?php

namespace IasonArgyrakis\LaraXtnder\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Output\BufferedOutput;

class All extends Command
{
    protected $signature = 'xtnd:make:all {modelName} {structure} ';

    protected $description = 'Genrates migration,model,factory,store-request,update-request, ';


    public function handle()
    {

        $modelName=Str::of($this->argument('modelName'));
        $structure=Str::of($this->argument('structure'));
        $commands =
            [
                "migration",
                "model",
                "factory",
                "store-request",
                "update-request",
            ];
        foreach ($commands as $command) {
           
            $this->call("xtnd:make:".$command,[
                "modelName"=>$modelName,
                "structure"=>$structure,
                ]);
           
        }


    }


}

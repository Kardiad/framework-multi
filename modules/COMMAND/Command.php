<?php

use Interfaces\ModuleLoader;

class Command extends ModuleLoader{
    private static array $arguments;

    public function __construct(stdClass $config, string $module, string $basePath){
        parent::__construct($config, $module, $basePath);
    }

    private function argumentsSetter(mixed $arguments){
        unset($arguments[0]);
        foreach($arguments as $val){
            $baseArgument = explode('=',$val);            
            self::$arguments[$baseArgument[0]] = $baseArgument[1];
        }
    }

    private function launchCommand(stdClass $app){
        $commandsAvailable = array_keys(self::$arguments);
        if(!empty(self::$config->commandValidValues)){
            foreach(self::$config->commandValidValues as $command){
                if(in_array($command->fieldName, $commandsAvailable)){                    
                    eval($command->ruleProgrammation[0]->execute);
                }
            }
        }
    }

    public function run(mixed $arguments, $app){
        self::argumentsSetter($arguments);        
        self::launchCommand($app);
    }
}
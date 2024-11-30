<?php

namespace Interfaces;

use stdClass;

abstract class ModelsLoader{

    private array $arrayModels;

    public function loadModels(string $basePath, stdClass $config){
        $modelPath = $basePath.DIRECTORY_SEPARATOR.$config->ormFolderEntity.DIRECTORY_SEPARATOR;
        foreach(glob($modelPath.'*.php') as $model){
            require_once $model;
        }
    }

}
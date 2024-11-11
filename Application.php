<?php 

class Application{
    private static array $instances = [];
    
    private const BASE_PATH = __DIR__;

    private static stdClass $programInstances;

    protected static stdClass $config;

    protected function __construct(){}

    protected function __clone(){}

    public function __wakeup(){
        throw new Exception("Cannot unserialize a singleton");
    }

    private static function instanceOfGenerate(string $module, string $class){
        return str_replace($module, '', 
        str_replace('.php', '', 
        str_replace('/', '', 
        str_replace('modules', '', $class))));
    }

    private static function loadClass(){
        $folders = self::$config->folders_autoload;
        foreach($folders as $module){
            foreach(glob("modules/$module/*.php") as $class){
                require_once $class;            
                $instanceOf = self::instanceOfGenerate($module, $class);
                //print_r("$class => $instanceOf \n");
                if(class_exists($instanceOf)){
                    self::$instances[strtolower($module)] = new $instanceOf(self::$config, $module, self::BASE_PATH);
                    //print_r("$instanceOf Loaded suscessfully \n");
                }else{
                    throw new Exception("Class don't found ", 404);
                }
            }
        }
        self::$programInstances = (object) self::$instances;
        self::$instances = [];
    }

    private static function loadConfig(){
        if(file_exists('config.json')){
            self::$config = json_decode(file_get_contents('config.json'));
        }else{
            throw new Exception("Config don't found", 404);
        }
    }

    private static function loadInterfaces(){        
        foreach(glob('interfaces/*.php') as $interface){             
            require_once $interface;
        }
    }

    public static function getInstance(){
        //crear método que permite sacar todo lo perteneciente a las intancias
        //print_r("Loading App Interfaces\n");
        self::loadInterfaces();
        //print_r("Loaded App Interfaces\n");
        //print_r("Loading config \n");
        self::loadConfig();
        //print_r("Loaded config \n");
        //print_r("Instanciating class \n");
        self::loadClass();
        //print_r("Intanciated class \n");
        return self::$programInstances;
    }

}
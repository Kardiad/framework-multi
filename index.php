<?php
require_once 'Application.php';
$app = Application::getInstance();
//Run http server
if(isset($_SERVER['REMOTE_ADDR'])){
    $app->server->http();
}
//Run cli
if(!$_SERVER || isset($_SERVER['PROCESSOR_IDENTIFIER'])){
    $app->command->run($argv, $app);
}
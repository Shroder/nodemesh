<?php
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__FILE__)."/_classes".PATH_SEPARATOR.dirname(__FILE__).'/../../../../../../Program Files (x86)/PHP/PEAR'.PATH_SEPARATOR.dirname(__FILE__).'/../classes'.PATH_SEPARATOR.dirname(__FILE__).'/../includes');
include_once 'interface.DatabaseAdapterInterface.php';

function autoloader($class)
{
    require "class.$class.php";
}
spl_autoload_register("autoloader");

Environment::SetEnvironment('main');
?>

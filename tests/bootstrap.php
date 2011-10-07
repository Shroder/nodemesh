<?php
set_include_path(get_include_path().PATH_SEPARATOR.'../classes:../includes');
include_once 'interface.DatabaseAdapterInterface.php';

function autoloader($class)
{
    if (file_exists("{$class}.php"))
    {
        require "{$class}.php";
    }
    require "class.$class.php";
}
spl_autoload_register("autoloader");

Environment::SetEnvironment('setup');
?>

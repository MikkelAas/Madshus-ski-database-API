<?php

require_once('config/config_template.php');

# This is a setup file that will help setup the project for you
# NOTE: You should run this as a PHP script
# You can do this by running 'php -f setup.php' from command-line


# Some initial functions used by setup script

function getInput () : string {
    return rtrim(fgets(STDIN));
}

function gatherConfigInfo () : array {
    $info = [];

    foreach (config as $key=>$value) {
        echo sprintf("%s (%s): ", $key, $value);
        $info[$key] = getInput();
    }

    return $info;
}

function configInfoToPhpString (array $configInfo) : string {
    $configString = "const config = array(\n";

    foreach ($configInfo as $key=>$value) {
        $configString .= sprintf("    '%s' => '%s',\n", $key, $value);
    }

    $configString .= ");";

    return $configString;
}

function generateConfigFile (string $file, array $configInfo) {
    $configFile = fopen($file, "w") or die("could not write to " + $file);

    $configString = "<?php \n\n";
    $configString .= "# Generated using setup.php\n\n";
    $configString .= sprintf("set_include_path(\"%s\");\n\n", __DIR__);
    $configString .= configInfoToPhpString($configInfo);

    fwrite($configFile, $configString);
    fclose($configFile);
}

# Gather information and create config files
echo "This setup script will setup the project for you, but before it can do that you need to provide some information\n";
echo "Please fill in the information required in the terminal. 'Default' values (taken from template) are shown in parenthesis (), but you have to manually type the values you want\n";

echo "Info regarding production database:\n";
$configInfo = gatherConfigInfo();
generateConfigFile("config/config.php", $configInfo);
echo "\nconfig/config.php has been updated successfully\n\n";
echo "--- EVERYTHING REGARDING PRODUCTION DATABASE HAS BEEN SETUP ---\n";
echo "--- IF YOU DO NOT WISH TO SETUP LOCAL TESTING, YOU CAN TERMINATE THIS SCRIPT ---\n\n";

echo "Info regarding testing database (this can be skipped if you are not interested in testing locally):\n";
$configInfo = gatherConfigInfo();
generateConfigFile("config/config_test.php", $configInfo);
echo "\nconfig/config_test.php has been updated successfully\n\n";
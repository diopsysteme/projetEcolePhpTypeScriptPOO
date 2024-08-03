<?php

require_once '/var/www/html/skul/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
use App\App;
if (!defined('ROO')) {
    define('ROO', $_ENV["ROOT"]);
}

if (!defined('WEBROO')) {
    define('WEBROO', $_ENV['WEBROOT ']);
}
// Error reporting for debugging
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// Get the instance of the App
$app = App::getInstance();

// Initialize the application with the configuration file
$app->initialize("/var/www/html/skul/app.yaml");
use Symfony\Component\Yaml\Yaml;

$configFilePath = '/var/www/html/skul/app.yaml';



// echo "Loading YAML file from: $configFilePath\n";

try {
    $config = Yaml::parseFile($configFilePath);
    // print_r($config);
} catch (Exception $e) {
    echo "Failed to load YAML file: ", $e->getMessage(), "\n";
    exit;
}

// if (isset($config['application']['session'])) {
//     $sessionClass = $config['application']['session'];
// } else {
//     echo "session class not found in YAML file.\n";
// }

// if (isset($config['application']['file'])) {
//     $fileClass = $config['application']['file'];
// } else {
//     echo "file class not found in YAML file.\n";
// }

// if (isset($config['application']['database'])) {
//     $databaseClass = $config['application']['database'];
// } else {
//     echo "database class not found in YAML file.\n";
// }

// if (isset($config['application']['route'])) {
//     $routeClass = $config['application']['route'];
// } else {
//     echo "route class not found in YAML file.\n";
// }

// if (isset($config['application']['model'])) {
//     $modelClass = $config['application']['model'];
// } else {
//     echo "model class not found in YAML file.\n";
// }

// if (isset($config['application']['controller'])) {
//     $controllerClass = $config['application']['controller'];
// } else {
//     echo "controller class not found in YAML file.\n";
// }

// if (isset($config['application']['validator'])) {
//     $validatorClass = $config['application']['validator'];
// } else {
//     echo "validator class not found in YAML file.\n";
// }

// echo "ssdsdsdClient Controller: $clientController\n";
// echo "Dette Controller: $detteController\n";
// echo "Paiement Controller: $paiementController\n";
// echo "Session Class: $sessionClass\n";
// echo "File Class: $fileClass\n";
// echo "Database Class: $databaseClass\n";
// echo "Route Class: $routeClass\n";
// echo "Model Class: $modelClass\n";
// echo "Controller Class: $controllerClass\n";
// echo "Validator Class: $validatorClass\n";
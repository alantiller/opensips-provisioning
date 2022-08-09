<?php /* OpenSIPS Provisioner - (C) Copyright Alan Tiller 2022 */

// Include modules
require '../vendor/autoload.php';

// Start session
session_start();

// Load .env config and if no .env exists start installer
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Error Handling
if ($_ENV['DEBUG'] === 'true') {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register(); 
}

// Set timezone
date_default_timezone_set($_ENV['TIMEZONE']);

// Connect to the opensips database
$opensips = new \Medoo\Medoo([
    'type' => $_ENV['OPSDB_DRIVER'],
    'host' => $_ENV['OPSDB_HOST'],
    'database' => $_ENV['OPSDB_NAME'],
    'username' => $_ENV['OPSDB_USER'],
    'password' => $_ENV['OPSDB_PASS']
]);

// Connect to the opensips database
$local = new \Medoo\Medoo([
    'type' => $_ENV['LOCALDB_DRIVER'],
    'host' => $_ENV['LOCALDB_HOST'],
    'database' => $_ENV['LOCALDB_NAME'],
    'username' => $_ENV['LOCALDB_USER'],
    'password' => $_ENV['LOCALDB_PASS']
]);

// Include controllers
spl_autoload_register(function ($class) {
    $class_path = str_replace('\\', '/', $class);
    require_once '../controllers/' . $class_path . '.php';
});

// Setup Router
$router = new \AltoRouter();

// Rendering Engine
$templates = new \League\Plates\Engine();

// Add folders to rendering engine
$templates->addFolder('App', '../views/App');
$templates->addFolder('Components', '../views/Components');

// Get user data
if (\Auth\Auth::check())
{ 
    $user = \Auth\Auth::get(); 
    $templates->addData(['user' => $user]);
}

// Add global data to rendering engine
$templates->addData(['router' => $router]);

// Include endpoint files
require_once '../routes/api.php';
require_once '../routes/web.php';

// match current request url
$match = $router->match();
if (is_array($match)) 
{
	call_user_func_array( $match['target'], $match['params'] ); 
}
else
{
    http_response_code(404);
	echo $templates->render('App::NotFound');
}

// Add to audit log
\Audit\Audit::log();
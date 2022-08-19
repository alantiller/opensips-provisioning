<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Dashboard
$router->map('GET', '/', 'App\Dashboard::view', 'dashboard');

// Subscribers
$router->map('GET', '/login', 'App\Login::view', 'login');

// Subscribers
$router->map('GET', '/subscribers', 'App\Subscribers::view', 'subscribers');

// Servers
$router->map('GET', '/servers', 'App\Servers::view', 'servers');

// Provisions
$router->map('GET', '/provisions', 'App\Provisions::view', 'provisions');

// Users
$router->map('GET', '/users', 'App\Users::view', 'users');

// Permissions
$router->map('GET', '/permissions', 'App\Permissions::view', 'permissions');

// Audit
$router->map('GET', '/logs/audit', 'App\Logs\Audit::view', 'logs_audit');

// Audit
$router->map('GET', '/logs/opensips', 'App\Logs\OpenSIPS::view', 'logs_opensips');

// Audit
$router->map('GET', '/account', 'App\Account::view', 'account');
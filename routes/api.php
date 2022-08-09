<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// V1 - Auth - Login
$router->map('POST', '/api/v1/login', 'Api\V1\Auth::authenticate', 'v1_auth_login');

// V1 - Auth - Logout
$router->map('POST', '/api/v1/logout', 'Api\V1\Auth::destroy', 'v1_auth_logout');

// V1 - Dashboard - Total Subscriber
$router->map('GET', '/api/v1/dashboard/total-subscribers', 'Api\V1\Dashboard\Subscribers::total', 'get_subscribers_count');

// V1 - Dashboard - Subscriber by Server
$router->map('GET', '/api/v1/dashboard/subscribers-by-server', 'Api\V1\Dashboard\Subscribers::all', 'get_subscribers_by_server');

// V1 - Dashboard - Total Enabled Servers
$router->map('GET', '/api/v1/dashboard/total-enabled-servers', 'Api\V1\Dashboard\Servers::total_enabled', 'get_total_enabled_servers');

// V1 - Dashboard - Total Enabled Provisions
$router->map('GET', '/api/v1/dashboard/total-enabled-provisions', 'Api\V1\Dashboard\Provisions::total_enabled', 'get_total_enabled_provisions');

// V1 - Dashboard - Total Enabled Provisions
$router->map('GET', '/api/v1/dashboard/audit-errors', 'Api\V1\Dashboard\Audit::errors', 'get_audit_errors');

// V1 - Subscribers - Create
$router->map('POST', '/api/v1/subscribers', 'Api\V1\Subscribers\Create::create', 'create_a_subscriber');

// V1 - Subscribers - Delete
$router->map('DELETE', '/api/v1/subscribers/[i:id]', 'Api\V1\Subscribers\Delete::delete', 'delete_a_subscriber');

// V1 - Servers - Create
$router->map('POST', '/api/v1/servers', 'Api\V1\Servers\Create::create', 'create_server');

// V1 - Servers - Delete
$router->map('DELETE', '/api/v1/servers/[i:id]', 'Api\V1\Servers\Delete::delete', 'delete_server');

// V1 - Users - Create
$router->map('POST', '/api/v1/users', 'Api\V1\Users\Create::create', 'create_user');

// V1 - Users - Delete
$router->map('DELETE', '/api/v1/users/[i:id]', 'Api\V1\Users\Delete::delete', 'delete_user');

// V1 - Permissions - Get Groups
$router->map('GET', '/api/v1/permissions/groups', 'Api\V1\Permissions\Get::groups', 'get_group_groups');

// V1 - Permissions - Create
$router->map('POST', '/api/v1/permissions', 'Api\V1\Permissions\Create::create', 'create_permission');

// V1 - Permissions - Delete
$router->map('DELETE', '/api/v1/permissions/[i:id]', 'Api\V1\Permissions\Delete::delete', 'delete_permission');



// V1 - Datatable - Subscribers
$router->map('GET', '/api/v1/datatables/subscribers', 'Api\V1\Datatables\Subscribers::get', 'get_subscribers_datatable');

// V1 - Datatable - Servers
$router->map('GET', '/api/v1/datatables/servers', 'Api\V1\Datatables\Servers::get', 'get_servers_datatable');

// V1 - Datatable - Provisions
$router->map('GET', '/api/v1/datatables/provisions', 'Api\V1\Datatables\Provisions::get', 'get_provisions_datatable');

// V1 - Datatable - Users
$router->map('GET', '/api/v1/datatables/users', 'Api\V1\Datatables\Users::get', 'get_users_datatable');

// V1 - Datatable - Permissions
$router->map('GET', '/api/v1/datatables/permissions', 'Api\V1\Datatables\Permissions::get', 'get_permissions_datatable');

// V1 - Datatable - Audit
$router->map('GET', '/api/v1/datatables/audit', 'Api\V1\Datatables\Audit::get', 'get_auditlogs_datatable');
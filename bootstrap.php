<?php echo "\e[0;32m[OpenSIPS Provisioner V0.0.1 - Bootstrapping Tool]\e[0m\n";

if (!isset($argv[1]) || !isset($argv[2]) || !isset($argv[3]) || !isset($argv[4]) || !isset($argv[5]) || !isset($argv[6]) || $argv[1] != '-n' || $argv[3] != '-u' || $argv[5] != '-p')
{
    die("You need to create a first user please call this via the following command: bootstrap.php -n \"User Name\" -u username -p password");
}

// Include modules
require 'vendor/autoload.php';

use \Tokenly\TokenGenerator\TokenGenerator;

// Load .env config and if no .env exists start installer
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Connect to the local database
$local = new \Medoo\Medoo([
    'type' => $_ENV['LOCALDB_DRIVER'],
    'host' => $_ENV['LOCALDB_HOST'],
    'database' => $_ENV['LOCALDB_NAME'],
    'username' => $_ENV['LOCALDB_USER'],
    'password' => $_ENV['LOCALDB_PASS']
]);

// Create the Servers table
$local->create("osp_servers", [
	"id" => [
		"INT",
		"NOT NULL",
		"AUTO_INCREMENT",
		"PRIMARY KEY"
	],
	"description" => [
		"VARCHAR(100)",
		"NOT NULL"
	],
    "address" => [
		"VARCHAR(100)",
		"NOT NULL"
	],
    "enabled" => [
		"boolean",
		"NOT NULL"
	],
    "default_domain" => [
		"VARCHAR(64)",
		"NOT NULL"
	],
    "default_attribute" => [
		"VARCHAR(32)",
		"NOT NULL"
	],
    "default_type" => [
		"INT",
		"NOT NULL"
	],
    "timestamp" => [
        "DATETIME",
        "NOT NULL"
    ]
]);

// Create the Provisions table
$local->create("osp_provisions", [
	"id" => [
		"INT",
		"NOT NULL",
		"AUTO_INCREMENT",
		"PRIMARY KEY"
	],
    "server" => [
		"VARCHAR(50)",
		"NOT NULL"
	],
    "enabled" => [
		"boolean",
		"NOT NULL"
	],
	"description" => [
		"VARCHAR(100)",
		"NOT NULL"
	],
	"request_url" => [
		"VARCHAR(100)"
	],
    "request_method" => [
		"VARCHAR(20)",
		"NOT NULL"
	],
	"request_auth" => [
		"TEXT"
	],
	"request_body" => [
		"TEXT"
	],
    "timestamp" => [
        "DATETIME",
        "NOT NULL"
    ]
]);

// Create the Audit Log table
$local->create("osp_audit", [
	"id" => [
		"INT",
		"NOT NULL",
		"AUTO_INCREMENT",
		"PRIMARY KEY"
	],
    "method" => [
		"VARCHAR(50)",
		"NOT NULL"
	],
    "request_url" => [
		"VARCHAR(100)",
		"NOT NULL"
	],
	"request_body" => [
		"TEXT"
	],
	"response_code" => [
		"INT"
	],
	"response_body" => [
		"TEXT"
	],
    "timestamp" => [
        "DATETIME",
        "NOT NULL"
    ]
]);

// Create the Users table
$local->create("osp_users", [
	"id" => [
		"INT",
		"NOT NULL",
		"AUTO_INCREMENT",
		"PRIMARY KEY"
	],
    "name" => [
		"VARCHAR(50)",
		"NOT NULL"
	],
    "username" => [
		"VARCHAR(50)",
		"NOT NULL"
	],
    "password" => [
		"TEXT",
		"NOT NULL"
	],
    "salt" => [
		"TEXT",
		"NOT NULL"
	],
    "group" => [
		"VARCHAR(50)",
		"NOT NULL"
	],
    "timestamp" => [
        "DATETIME",
        "NOT NULL"
    ]
]); 

// Create the Tokens table
$local->create("osp_tokens", [
	"id" => [
		"INT",
		"NOT NULL",
		"AUTO_INCREMENT",
		"PRIMARY KEY"
	],
    "user" => [
		"INT"
	],
    "token" => [
		"TEXT",
		"NOT NULL"
	],
    "description" => [
		"VARCHAR(100)",
		"NOT NULL"
	],
    "expiry" => [
        "DATETIME",
        "NOT NULL"
    ],
    "timestamp" => [
        "DATETIME",
        "NOT NULL"
    ]
]); 

// Create the Permissions table
$local->create("osp_permissions", [
	"id" => [
		"INT",
		"NOT NULL",
		"AUTO_INCREMENT",
		"PRIMARY KEY"
	],
    "group" => [
		"VARCHAR(50)",
		"NOT NULL"
	],
    "entity" => [
		"VARCHAR(50)",
		"NOT NULL"
	],
    "value" => [
		"INT",
		"NOT NULL"
	],
    "timestamp" => [
        "DATETIME",
        "NOT NULL"
    ]
]); 

// Create the first user
$password_salt = (new TokenGenerator())->generateToken(20);
$salted_hash = hash('sha256', $argv[6] . $password_salt);
$local->insert("osp_users", [
    "name" => $argv[2],
    "username" => $argv[4],
    "password" => $salted_hash,
    "salt" => $password_salt,
    "group" => "admin",
    "timestamp" => date("Y-m-d H:i:s")
]);
$user = $local->id();

// Create the admin group permissions
$local->insert("osp_permissions", ["group" => "admin", "entity" => "usr_preferences", "value" => 15, "timestamp" => date("Y-m-d H:i:s")]);
$local->insert("osp_permissions", ["group" => "admin", "entity" => "osp_servers", "value" => 15, "timestamp" => date("Y-m-d H:i:s")]);
$local->insert("osp_permissions", ["group" => "admin", "entity" => "osp_provisions", "value" => 15, "timestamp" => date("Y-m-d H:i:s")]);
$local->insert("osp_permissions", ["group" => "admin", "entity" => "osp_audit", "value" => 15, "timestamp" => date("Y-m-d H:i:s")]);
$local->insert("osp_permissions", ["group" => "admin", "entity" => "osp_users", "value" => 15, "timestamp" => date("Y-m-d H:i:s")]);
$local->insert("osp_permissions", ["group" => "admin", "entity" => "osp_tokens", "value" => 15, "timestamp" => date("Y-m-d H:i:s")]);
$local->insert("osp_permissions", ["group" => "admin", "entity" => "osp_permissions", "value" => 15, "timestamp" => date("Y-m-d H:i:s")]);



if (isset($argv[7]) && $argv[7] === '-d')
{
    // Insert demo servers
    $local->insert("osp_servers", [
        "description" => "Voice Server 1",
        "address" => "10.10.0.1",
        "enabled" => 1,
        "default_domain" => "opensips.domain.com",
        "default_attribute" => "fs",
        "default_type" => 2,
        "last_modified" => date('Y-m-d H:i:s')
    ]);
    $local->insert("osp_servers", [
        "description" => "Voice Server 2",
        "address" => "10.10.0.2",
        "enabled" => 1,
        "default_domain" => "opensips.domain.com",
        "default_attribute" => "fs",
        "default_type" => 2,
        "last_modified" => date('Y-m-d H:i:s')
    ]);
    $local->insert("osp_servers", [
        "description" => "Voice Server 3",
        "address" => "10.10.0.3",
        "enabled" => 1,
        "default_domain" => "opensips.domain.com",
        "default_attribute" => "fs",
        "default_type" => 2,
        "last_modified" => date('Y-m-d H:i:s')
    ]);
}
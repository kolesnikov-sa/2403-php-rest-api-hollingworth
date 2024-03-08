<?php

// Based on https://www.youtube.com/watch?v=X51KOJKrofU

declare(strict_types = 1);

spl_autoload_register(function($class) {
	require __DIR__ . "/src/" . $class . ".php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-Type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if( "products" != $parts[1] ) {
	http_response_code(404);
	exit;
}

$id = $parts[2] ?? null;

// Database
$database = new Database("localhost", "phprestapihollingworth", "root", "root");

// Gateway - receives data from database
$gateway = new ProductGateway($database);

// Controller - processes data received by gateway
$controller = new ProductController($gateway);
$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
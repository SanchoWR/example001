<?php

$_SERVER['PATH_INFO'] = preg_replace("/^(\/[^?]*).*/", "$1", $_SERVER['REQUEST_URI']);

use App\Http\Request;
use App\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$kernel = new Kernel();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

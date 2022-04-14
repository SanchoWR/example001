<?php

use App\Http\Request;
use App\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$kernel = new Kernel();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

<?php

require '../vendor/autoload.php';

session_start();

// Boot Application, Initialize configuration and run
$kernel = new \App\Boot\Kernel();
$kernel->bootApplication();
$kernel->run();

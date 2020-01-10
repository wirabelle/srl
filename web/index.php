<?php

$loader = require 'vendor/autoload.php';
$loader->addPsr4('Framework\\', realpath(__DIR__.'/../src/Framework'));
$loader->addPsr4('App\\', realpath(__DIR__.'/../src/App'));

$kernel = new Framework\Kernel(__DIR__.'/..');
echo $kernel->run();

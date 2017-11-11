<?php
require __DIR__.'/vendor/autoload.php';
use Symfony\Component\Console\Application;
use DLS\Console\Converter;

$application = new Application();
$application->add(new Converter());
$application->run();

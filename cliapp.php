<?php
// File: cliapp.php

require __DIR__.'/vendor/autoload.php';
require_once 'curl-master/curl.php';
require_once __DIR__.'/commands/JqueryTestCommand.php';

use Symfony\Component\Console\Application;


$application = new Application('CLI TechnologyTest', '0.1.0');

$application->add(new JqueryTestCommand());

$application->run();

?>
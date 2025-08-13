<?php

require __DIR__ . '/vendor/autoload.php';

use silverorange\DevTest\Config;
use silverorange\DevTest\Database;
use silverorange\DevTest\Importer\PostImporter;

// Initialize config and DB connection
$config = new Config();
$db = (new Database($config->dsn))->getConnection();

// Create and run the importer
$importer = new PostImporter($db, __DIR__ . '/data');
$importer->import();

echo "Post import complete.\n";

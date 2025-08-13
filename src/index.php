<?php

namespace silverorange\DevTest;

require __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_URI'] === '/highres-assets/product.jpg') {
    $file = __DIR__ . '/../highres-assets/product.jpg';
    header('Content-type: image/jpeg');
    readfile($file);
    exit;
}

$config = new Config();
$db = (new Database($config->dsn))->getConnection();

$app = new App($db);
return $app->run();

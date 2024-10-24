<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';
session_start();

$d = new \iutnc\deefy\dispatch\Dispatcher();
$d->run();

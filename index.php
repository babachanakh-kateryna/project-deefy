<?php
declare(strict_types=1);

use iutnc\deefy\repository\DeefyRepository;

require_once 'vendor/autoload.php';
session_start();

DeefyRepository::setConfig(__DIR__ . '/conf/db.config.ini');

$d = new \iutnc\deefy\dispatch\Dispatcher();
$d->run();

<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$url = sprintf("%s/projects/search_by_custom_domain/%s", $_ENV['APP_URL'], $_SERVER['X-Forwarded-Host ']);

$projects = json_decode(file_get_contents($url), true);

if (!$projects) {
    exit("Cant find the project");
}

$name = $projects[0]['name'];
$path = $name . '/' . $_SERVER['REQUEST_URI'];

header('X-Accel-Redirect: ' . $path);

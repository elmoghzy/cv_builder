<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'cv_builder',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Check CV data
$cv = $capsule->table('cvs')->where('id', 1)->first();

echo "CV Data Structure:\n";
echo "================\n";
echo "ID: " . $cv->id . "\n";
echo "Title: " . $cv->title . "\n";
echo "Content: " . $cv->content . "\n";
echo "================\n";

// Parse JSON content
$content = json_decode($cv->content, true);
echo "Parsed Content:\n";
print_r($content);

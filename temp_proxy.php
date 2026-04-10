<?php
require 'c:\xampp\htdocs\WebAnime_CI4_Replica\app\Config\bootstrap.php';
require 'c:\xampp\htdocs\WebAnime_CI4_Replica\app\ReplicaCi4\Models\Database.php';
require 'c:\xampp\htdocs\WebAnime_CI4_Replica\app\ReplicaCi4\Controllers\Api\JikanProxy.php';

$_GET['endpoint'] = 'top/anime?filter=bypopularity&limit=2';
$proxy = new \ReplicaCi4\Controllers\Api\JikanProxy();
$proxy->handle();

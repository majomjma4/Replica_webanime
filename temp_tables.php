<?php
require 'c:\xampp\htdocs\WebAnime_CI4_Replica\app\Config\bootstrap.php';
require 'c:\xampp\htdocs\WebAnime_CI4_Replica\app\ReplicaCi4\Models\Database.php';

$db = new \ReplicaCi4\Models\Database();
$c = $db->getConnection(false);
$tables = $c->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
echo json_encode($tables);

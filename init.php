<?php
use Core\App;
use Core\DB\Connect;
use Core\Cache;

Connect::init('config.php');
Cache::init();

$app = new App();
$app->start();
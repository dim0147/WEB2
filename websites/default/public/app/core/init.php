<?php 
    session_start();
    require __DIR__ . '/../config/config.php';
    require __DIR__ . '/../Database/Database.php';
    require __DIR__  . '/PayPal-PHP-SDK/autoload.php';
    require __DIR__ . '/../Controller/Controller.php';
    require __DIR__ . '/helper.php';
    require __DIR__ . '/View.php';
    require __DIR__ . '/Request.php';
    require __DIR__ . '/Route.php';
    
<?php
    //Lien lạc chức năng (call fncs)
    require_once(realpath(dirname(__FILE__) . '/..') . '/Functions/functions.php');

    //Tạo request móc nối với CSDL
    session_start();
    define('SERVER', '127.0.0.1');
    define('USER', 'kkyler');
    define('PASS', '123456');
    define('DB', 'mini_football_ground_management');
    define('ROOT', dirname(__FILE__));
    define('HOST', 'http://localhost:80');
?>
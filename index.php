<?php

session_start();
require './_app/config.php';
$core = new Core;
$core->RunController();
?>


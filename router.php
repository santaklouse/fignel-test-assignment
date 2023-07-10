<?php

require_once 'main.php';

// router.php
if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
    return FALSE;
}

$main = new Main();
$main
    ->showSources()
    ->render(Main::ROOT);

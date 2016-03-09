<?php

define("CONFIG_FILE", "config.ini");

$settings = parse_ini_file(CONFIG_FILE, true);

foreach ($settings as $value) {
    if (!isset($value)) {
        echo CONFIG_FILE . " has missing value(s)";
        exit(1);
    }
}


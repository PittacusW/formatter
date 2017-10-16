<?php

include_once '../vendor/autoload.php';

use Contal\Formatter;

$uglyCode = file_get_contents('uglyCode.php');
$beautifulCode = Formatter::format($uglyCode);
$fp = fopen('beautifulCode.php', 'w');
fwrite($fp, $beautifulCode);
fclose($fp);
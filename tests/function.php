<?php

include_once '../src/Formatter.php';

use Contal\Formatter;

$uglyCode = file_get_contents('uglyCode.php');
$beautifulCode = Formatter::format($uglyCode);
$fp = fopen('beautifulCode.php', 'w');
fwrite($fp, "static function" . $beautifulCode);
fclose($fp);
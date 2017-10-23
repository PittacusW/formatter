<?php
$array = "";
foreach(getDirContents('src') as $file) {
    $path = pathinfo($file);
    if ($path['extension']=='php') {
		$array .= "<p>include_once '$file';";
	}
}
echo $array;

function getDirContents($dir) {
    $handle = opendir($dir);
	if (!$handle) return array();
	$contents = array();
	while ($entry = readdir($handle)) {
        if ($entry == '.' || $entry == '..') continue;
        $entry = $dir . '/' . $entry;
        $path = pathinfo($entry);
        if (array_key_exists('extension', $path)) {
            if (is_file($entry) && $path['extension']=='php') {
                $contents[] = $entry;
			}
        }
        elseif (is_dir($entry)) {
            $contents = array_merge($contents, getDirContents($entry));
        }
    }
	closedir($handle);
	return $contents;
}